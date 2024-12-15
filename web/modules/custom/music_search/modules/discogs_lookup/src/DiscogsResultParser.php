<?php
namespace Drupal\discogs_lookup;

/**
 * Parses Discogs API results into a structured array.
 */
class DiscogsResultParser {
  /**
   * Parses Discogs API results.
   *
   * @param array $items
   *   The raw Discogs API items.
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
    $baseDiscogsUrl = 'https://open.discogs.com';

    \Drupal::logger('discogs_parser')->debug('Type is: @type', [
      '@type' => print_r($type, TRUE),
    ]);

    foreach ($items as $item) {
      $id = $item['id'] ?? null;
      if ($type === 'album' && $item['type'] !== 'master') {
        continue;
      }

      if ($type === 'track' && $item['type'] !== 'master') {
        continue;
      }

      if (($type === 'album' || $type === 'track') && isset($item['master_id'])) {
        $id = $item['master_id'];
      }

      $title = $item['title'] ?? 'Unknown';
      $image = $item['cover_image'] ?? '';
      $resourceUrl = $item['resource_url'] ?? null;


      // Combine fields into a single result array
      $formattedResults[] = [
        'uri' => $id,
        'name' => $title,
        'image' => $image,
        'type' => $type,
      ];
    }

    \Drupal::logger('discogs_parser')->debug('Parsed Results in parseResults: @formattedResults', [
      '@formattedResults' => print_r($formattedResults, TRUE),
    ]);

    return $formattedResults;
  }

  /**
   * Parses detailed information for a specific item.
   *
   * @param array $item
   *   The detailed item from Discogs API.
   * @param string $type
   *   The type of item (artist, album, track).
   *
   * @return array
   *   The structured details array.
   */
  public function parseDetails(array $item, string $type): array {
    \Drupal::logger('discogs_parser')->debug('Type passed to parser: @type', [
      '@type' => print_r($type, TRUE),
    ]);
    return match ($type) {
      'artist' => $this->parseArtistDetails($item),
      'album' => $this->parseAlbumDetails($item),
      'track' => $this->parseTrackDetails($item),
      default => [],
    };
  }

  /**
   * Parses artist details.
   */
  private function parseArtistDetails(array $item): array {
    return [
      'name' => $item['name'] ?? 'Unknown',
      'profile' => $item['profile'] ?? null,
      'website' => $item['urls'][0] ?? null,
      'body' => $item['profile'] ?? null,
      'type' => 'artist',
    ];
  }

  /**
   * Parses track details.
   */
  private function parseTrackDetails(array $item): array {
    return [
      'name' => $item['title'] ?? 'Unknown',
      'artists' => $item['artists'] ?? [],
      'duration' => $this->durationToSeconds($item['tracklist'][0]['duration']) ?? null,
      'release' => $item['release']['title'] ?? null,
      'discogs_url' => $item['resource_url'] ?? null,
      'type' => 'track',
    ];
  }

  /**
   * Parses album details.
   */
  private function parseAlbumDetails(array $item): array {
    return [
      'name' => $item['title'] ?? 'Unknown',
      'year' => $item['year'] ?? null,
      'url' => $item['resource_url'] ?? null,
      'album_description' => $item['notes'] ?? null,
      'type' => 'album',
    ];
  }

  private function durationToSeconds($duration) {
    $parts = explode(':', $duration);

    if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
      $minutes = (int)$parts[0];
      $seconds = (int)$parts[1];
      return $minutes * 60 + $seconds;
    }

    return 0;
  }
}
