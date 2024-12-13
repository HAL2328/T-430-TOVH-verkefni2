<?php

namespace Drupal\music_search;

/**
 * Mock service for Discogs.
 */
class DiscogsMockService implements SearchServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function search(string $type, string $term): array {
    // Mocked markup response.
    $mockResults = [
        'markup' => '<li><strong>Mocked Discogs ' . htmlspecialchars($type, ENT_QUOTES) . ' 1</strong>: ' . htmlspecialchars($term, ENT_QUOTES) . '</li>',
        'markup' => '<li><strong>Mocked Discogs ' . htmlspecialchars($type, ENT_QUOTES) . ' 2</strong>: ' . htmlspecialchars($term, ENT_QUOTES) . '</li>',
    ];

    return $mockResults;
  }

  public function getDetails(array $params): array
  {
    $mockResults = [
      'markup' => '<li><strong>Mocked Discogs ' . htmlspecialchars($params['type'], ENT_QUOTES) . ' 1</strong>: ' . htmlspecialchars($params['uri'], ENT_QUOTES) . '</li>',
      'markup' => '<li><strong>Mocked Discogs ' . htmlspecialchars($params['type'], ENT_QUOTES) . ' 2</strong>: ' . htmlspecialchars($params['uri'], ENT_QUOTES) . '</li>',
    ];
    return $mockResults;
  }
}
