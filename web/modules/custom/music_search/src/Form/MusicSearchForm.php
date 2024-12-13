<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Provides the Music Search Form.
 */
class MusicSearchForm extends FormBase {
  /**
   * Session service for persisting searches.
   *
   * @var SessionInterface
   */
  protected SessionInterface $session;

  /**
   * Constructs a MusicSearchForm object.
   *
   * @param SessionInterface $session
   *   The session service.
   */
  public function __construct(SessionInterface $session) {
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('session')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'music_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['providers'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Search Providers'),
      '#options' => [
        'spotify' => $this->t('Spotify'),
        'discogs' => $this->t('Discogs'),
      ],
      '#required' => TRUE,
    ];

    $form['search_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Search Type'),
      '#options' => [
        'artist' => $this->t('Artist'),
        'album' => $this->t('Album'),
        'song' => $this->t('Song'),
      ],
      '#default_value' => 'artist',
      '#required' => TRUE,
    ];

    $form['search_term'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search Term'),
      '#description' => $this->t('Enter the term to search for.'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Get values from the search form.
    $searchType = $form_state->getValue('search_type');
    $searchTerm = $form_state->getValue('search_term');
    $providers = array_filter($form_state->getValue('providers'));

    // Store search parameters in session.
    $this->session->set('music_search.search_params', [
      'type' => $searchType,
      'term' => $searchTerm,
      'providers' => $providers,
    ]);

    // Insert parameters into query for the redirect.
    $query = [
      'type' => $searchType,
      'term' => $searchTerm,
      'providers' => implode(',', $providers),
    ];

    // Redirect to the results page with query parameters.
    $form_state->setRedirect('music_search.results', [], ['query' => $query]);
  }
}
