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
    if (isset($items['error'])) {
      return $items;
    }


    $formattedResults = [];
    $baseSpotifyUrl = 'https://open.spotify.com';



    foreach ($items as $item) {
      // Extract main details.
      $name = $item['name'] ?? 'Unknown';
      $image = $item['images'][0]['url'] ?? ''; // Use the first image, if available.
      $uri = $item['uri'] ?? ''; // Spotify URI.
      $id = explode(':', $uri)[2] ?? null;
      $artist = $item['artists'][0]['name'] ?? null;

      // Extract artist details if available.


      // Add the parsed data to the results.
      $formattedResults[] = [
        'name' => $name,
        'image' => $image,
        'uri' => $uri,
        'artist' => $artist,
        'type' => ucfirst($type),
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
      'type' => 'artist'
    ];
    return $result;
  }

  private function parseTrackDetails(array $item): array {
    $result = [
      'name' => $item['name'],
      'duration' => (int) ($item['duration_ms'] / 1000)?? null,
      'type' => 'song'
    ];
    return $result;
  }

  private function parseAlbumDetails(array $item): array {
    $result = [
      'name' => $item['name'] ?? null,
      'year' => $item['release_date'] ?? null,
      'type' => 'album'
    ];
    return $result;
  }
}


