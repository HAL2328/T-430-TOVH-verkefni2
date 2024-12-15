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
      // General fields for all types
      $id = $item['id'] ?? null;
      if (strtolower($type) === 'track' && isset($item['master_id'])) {
        $id = $item['master_id'];
      }
      $title = $item['title'] ?? 'Unknown';
      $image = $item['cover_image'] ?? '';
      $resourceUrl = $item['resource_url'] ?? null;

      // Extract type-specific fields
      $details = match ($type) {
        'artist' => [
          'artist_name' => $title,
          'profile_url' => $resourceUrl,
        ],
        'album' => [
          'release_title' => $title,
          'year' => $item['year'] ?? null,
        ],
        'track' => [
          'track_name' => $title,
          'duration' => $item['duration'] ?? null,
        ],
        default => [],
      };

      // Combine fields into a single result array
      $formattedResults[] = array_merge([
        'uri' => $id,
        'name' => $title,
        'image' => $image,
        'artist' => $details['artist_name'] ?? 'Unknown',
        'discogs_url' => $id ? "$baseDiscogsUrl/$type/$id" : null,
        'type' => $type,
      ], $details);
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
      'genres' => $item['genres'] ?? [],
      'images' => $item['images'][0]['uri'] ?? null,
      'discogs_url' => $item['resource_url'] ?? null,
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
      'release_date' => $item['released'] ?? null,
      'genres' => $item['genres'] ?? [],
      'discogs_url' => $item['resource_url'] ?? null,
      'body' => $item['notes'] ?? null,
      'type' => 'album',
    ];
  }
}
