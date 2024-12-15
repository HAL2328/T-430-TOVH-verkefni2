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

    \Drupal::logger('discogs_parser')->debug('Items passed to parser: @items', [
      '@items' => print_r($items, TRUE),
    ]);

    foreach ($items as $item) {
      $id = $item['id'] ?? null;
      if ($type === 'album' && $item['type'] !== 'master') {
        continue;
      }

      if ($type === 'album' && isset($item['master_id'])) {
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
      'duration' => $item['duration'] ?? null,
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
      'year_of_release' => $item['released'] ?? null,
      'url' => $item['resource_url'] ?? null,
      'album_description' => $item['notes'] ?? null,
      'type' => 'album',
    ];
  }
}
