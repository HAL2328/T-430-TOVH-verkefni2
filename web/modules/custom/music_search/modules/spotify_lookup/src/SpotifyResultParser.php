<?php

namespace Drupal\spotify_lookup;

/**
 * Parses Spotify API results into a structured array.
 */
class SpotifyResultParser {

  /**
   * Parses Spotify API results.
   *
   * @param array $items
   *   The raw Spotify API items.
   * @param string $type
   *   The type of items (artist, album, track).
   *
   * @return array
   *   A structured array of results for display or further use.
   */
  public function parseResults(array $items, string $type): array {
    $formattedResults = [];
    $baseSpotifyUrl = 'https://open.spotify.com';

    foreach ($items as $item) {
      // Extract main details.
      $name = $item['name'] ?? 'Unknown';
      $image = $item['images'][0]['url'] ?? ''; // Use the first image, if available.
      $uri = $item['uri'] ?? ''; // Spotify URI.
      $id = explode(':', $uri)[2] ?? null;

      // Extract artist details if available.
      $artists = [];
      if (isset($item['artists'])) {
        foreach ($item['artists'] as $artist) {
          $artists[] = [
            'name' => $artist['name'] ?? 'Unknown',
            'url' => $artist['external_urls']['spotify'] ?? '#',
          ];
        }
      }

      // Add the parsed data to the results.
      $formattedResults[] = [
        'name' => $name,
        'image' => $image,
        'uri' => $uri,
        'type' => ucfirst($type),
        'artists' => $artists,
        'spotify_url' => $id ? $baseSpotifyUrl . '/' . $type . '/' . $id : null,
      ];
    }

    return $formattedResults;
  }

  public function parseDetails(array $item, string $type): array {
    return match ($type) {
      'Artist' => $this->parseArtistDetails($item),
      'Album' => $this->parseAlbumDetails($item),
      'Track' => $this->parseTrackDetails($item),
      default => [],
    };

  }

  private function parseArtistDetails(array $item): array {
    $result = [
      'name' => $item['name'] ?? null,
      'image' => isset($item['images'][0]['url']) ? $item['images'][0]['url'] : null,
      'url' => $item['external_urls']['spotify'] ?? null,
      'type' => 'artist'
    ];
    return $result;
  }

  private function parseTrackDetails(array $item): array {
    $result = [
      'name' => $item['name'],
      'album' => $item['album']['name'] ?? null,
      'artists' => $item['artists'] ?? null,
      'duration' => $item['duration_ms'] ?? null,
      'spotify_id' => $item['id'] ?? null,
      'type' => 'song'
    ];
    return $result;
  }

  private function parseAlbumDetails(array $item): array {
    $tracks = [];
    if (!empty($item['tracks']['items'])) {
      foreach($item['tracks']['items'] as $track) {
        $tracks[] = $track['name'];
      }
    }

    $result = [
      'name' => $item['name'] ?? null,
      'release_date' => $item['release_date'] ?? null,
      'artists' => $item['artists'][0]['name'] ?? null,
      'tracks' => $tracks,
      'date' => $item['external_urls']['spotify'] ?? null,
      'label' => $item['label'] ?? null,
      'genres' => $item['genres'] ?? null,
      'image' => $item['images'][0]['url'] ?? null,
      'type' => 'album'
    ];
    return $result;
  }
}


