<?php

namespace Drupal\music_search;

/**
 * Generic music search service that delegates to specific services.
 */
class MusicSearchService {

  /**
   * The array of specific search services.
   *
   * @var SearchServiceInterface[]
   */
  protected array $searchServices;

  /**
   * Constructs a MusicSearchService object.
   *
   * @param SearchServiceInterface[] $searchServices
   *   An array of specific search service instances.
   */
  public function __construct(array $searchServices) {
    $this->searchServices = $searchServices;
  }

  /**
   * Performs a search across all selected services.
   *
   * @param array $providers
   *   The selected providers (e.g., ["spotify", "discogs"]).
   * @param string $type
   *   The type of search (e.g., "artist", "album", "song").
   * @param string $term
   *   The search term.
   *
   * @return array
   *   A combined array of results from all providers.
   */
  public function search(array $providers, string $type, string $term): array {
    $results = [];

    foreach ($providers as $provider) {
      if (isset($this->searchServices[$provider])) {
        $results[$provider] = $this->searchServices[$provider]->search($type, $term);
      }
    }

    return $results;
  }

  public function getDetails(array $params): array {
    $results = [];
    \Drupal::logger('music_search.params')->debug('ItemsArray: @params',[
      '@params' => print_r($params, TRUE)
    ]);
    foreach ($params as $param) {
      if (isset($this->searchServices[$param['provider']])) {
        \Drupal::logger('music_search.items_array')->debug('ItemsArray: @items',[
          '@param' => print_r($param, TRUE)
        ]);
        $details = $this->searchServices[$param['provider']]->getDetails($param);

        $results[$param['provider']] = $details;
      }
      \Drupal::logger('music_search.detail_query')->debug('Detail query: @results',[
        '@results' => print_r($results, TRUE)
        ]);
    }

    return $results;
  }
}
