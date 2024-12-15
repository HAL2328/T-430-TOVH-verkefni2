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
   *
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
      $container->get('music_search.service'),
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

    $search_results = $this->musicSearchService->search(
      explode(',', $params['providers']),
      $params['type'],
      $params['term']
    );
    $seperated_results = [
      'spotify' => [],
      'discogs' => [],
    ];
    foreach ($search_results as $result) {
      if ($seperated_results['provider'] == 'spotify') {
        $seperated_results['spotify'][] = $result;
      } elseif ($seperated_results['provider'] === 'discogs') {
        $seperated_results['discogs'][] = $result;
      }
    }
    return \Drupal::formBuilder()->getForm(\Drupal\music_search\Form\MusicSearchSelectionForm::class, $seperated_results);
  }

  /**
   * Handles the detail query for a selected item.
   */
  public function detailQuery(): array
  {
    // Fetch queries.
    $params = \Drupal::request()->query->all();

    if (!$params) {
      return [
        '#markup' => $this->t('No item selected.'),
      ];
    }

    $details = $this->musicSearchService->getDetails($params);
    \Drupal::logger('music_search.service')->debug('Detail query result passed to controller: @details', [
        '@details' => print_r($details, TRUE),
      ]
    );

    if (empty($details)) {
      return [
        '#markup' => $this->t('No details found for this item.'),
      ];
    }

    $type = $parms['type'] ?? 'artist';

    // Build the form.
    return \Drupal::formBuilder()->getForm(\Drupal\music_search\Form\EntityFieldSelectorForm::class, $type, $details);
  }

  public function createContent() {
    // This method receives the chosen fields from the form submission.
    $request = \Drupal::request();
    $type = $request->query->get('type');
    $fields = $request->query->get('fields');

    // Store fields in session or directly redirect with query parameters.
    $session = \Drupal::request()->getSession();
    $session->set('music_search.data', $fields);

    // Redirect to node/add/<type>
    return $this->redirect('node.add', ['node_type' => $type]);
  }

  /*
   * Helper function to save values temporarily in session.
   */
  public function setSessionValue(): array
  {
    $request = \Drupal::request();
    $selectedFields = $request->get('fields');

    if (!empty($selectedFields)) {
      // Store the `fields` array into the session under `selected_fields` key.
      $this->session->set('selected_fields', $selectedFields);

      // Optional: Log the operation for debugging purposes.
      \Drupal::logger('music_search')->notice('Selected fields stored in session: @fields', [
        '@fields' => print_r($selectedFields, TRUE),
      ]);
    }

    // If no fields were selected, inform the user.
    if (empty($selectedFields)) {
      return [
        '#markup' => $this->t('No fields were selected.'),
      ];
    }

    // Render a success message or redirect.
    return [
      '#markup' => $this->t('The selected fields were successfully stored in the session.'),
    ];
  }
}


