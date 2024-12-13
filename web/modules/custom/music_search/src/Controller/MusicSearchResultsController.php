<?php

namespace Drupal\music_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Drupal\music_search\MusicSearchService;

/**
 * Handles displaying search results.
 */
class MusicSearchResultsController extends ControllerBase {

  /**
   * The session service.
   *
   * @var SessionInterface
   */
  protected SessionInterface $session;

  /**
   * The music search service.
   *
   * @var MusicSearchService
   */
  protected MusicSearchService $musicSearchService;

  /**
   * Constructs a MusicSearchResultsController object.
   *
   * @param SessionInterface $session
   *   The session service.
   * @param MusicSearchService $musicSearchService
   *   The music search service.
   */
  public function __construct(SessionInterface $session, MusicSearchService $musicSearchService) {
    $this->session = $session;
    $this->musicSearchService = $musicSearchService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('session'),
      $container->get('music_search.service')
    );
  }

  /**
   * Displays the search results.
   *
   * @return array
   *   A render array.
   */
  public function resultsPage(): array {
    // Fetches all queries from the current request.
    $params = \Drupal::request()->query->all();

    // Fetch session data if query parameters are missing.
    if (empty($params)) {
      $params = $this->session->get('music_search.search_params', []);
    }

    if (empty($params)) {
      return [
        '#markup' => $this->t('No search parameters provided.'),
      ];
    }

    $results = $this->musicSearchService->search(
      explode(',', $params['providers']),
      $params['type'],
      $params['term']
    );

    return [
      '#theme' => 'music_search_results',
      '#results' => $results,
      '#cache' => [
        'max-age' => 0,
      ],
      '#attached' => [
        'library' => [
          'music_search/music_search_results_css',
        ],
      ],
    ];
  }

  /**
   * Handles the detail query for a selected item.
   */
  public function detailQuery(): array {
    // Fetches all queries from the current request.
    $params = \Drupal::request()->query->all();


    if (!$params) {
      return [
        '#markup' => $this->t('No item selected.'),
      ];
    }

    $details = $this->musicSearchService->getDetails($params);
    if (empty($details['spotify'])) {
      return [
        '#markup' => $this->t('No details found for this item.'),
      ];
    }
    $entity_type = $details['spotify']['entity_type'] ?? 'album';
    \Drupal::logger('music_search')->notice('Details received: <pre>@details</pre>', ['@details' => print_r($details, TRUE)]);
    // Render the details on a new page.
    // Dummy Discogs data for testing.

    $details['discogs'] = [
      'name' => 'Discogs Artist Name',
      'genres' => ['Electro', 'Experimental'],
      'profile' => 'A famous artist known for blending genres like pop and electronic music.',
      'image_url' => 'https://discogs.com/artist-image.jpg',
    ];

    return [
      '#theme' => 'entity_field_selector',
      '#entity_type' => $entity_type,
      '#details' => $details,
      '#cache' => [
        'max-age' => 0,
        ],
      '#attached' => [
        'library' => [
          'music_search/entity_field_selector_css',
        ],
      ],
    ];
    //return [
    //  '#theme' => 'music_search_item_detail',
    //  '#details' => $details['spotify'],
    //];
  }

  /*
   * Helper function to save values temporarily in session.
   */
  public function setSessionValue($key, $value) {
    $this->session->set($key, $value);
  }
}


