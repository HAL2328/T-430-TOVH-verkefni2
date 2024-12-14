<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

class EntityFieldSelectorForm extends FormBase {

  protected $type;
  protected $details;

  public function __construct($type, $details) {
    $this->type = $type;
    $this->details = $details;
  }

  public static function create($container) {
    // Normally, you'd inject services here if needed. For simplicity, omit it.
    return new static(
      $container->get('request_stack')->getCurrentRequest()->get('type'),
      $container->get('request_stack')->getCurrentRequest()->get('details')
    );
  }

  public function getFormId() {
    return 'entity_field_selector_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL, $details = NULL) {
    // If parameters passed in directly.
    $type = $type ?: $this->type;
    $details = $details ?: $this->details;

    // Validate the details structure.
    if (!is_array($details)) {
      return [
        '#markup' => $this->t('Invalid details provided for this form.'),
      ];
    }

    // Determine which fields to display based on type.
    $fields_map = [
      'artist' => [
        'title' => 'Name',
        'field_artist_picture' => 'Image URL',
        'field_date_of_birth' => 'Date of Birth',
        'field_date_of_death' => 'Date of Death',
        'field_website' => 'Website',
      ],
      'album' => [
        'title' => 'Name',
        'field_album_cover' => 'Cover Photo',
        'field_album_genres' => 'Genres',
        'field_artist' => 'Artist',
        'field_album_description' => 'Description',
        'field_album_publisher' => 'Publisher',
        'field_album_songs' => 'Songs',
        'field_year_of_release' => 'Year of Release',
      ],
      'song' => [
        'title' => 'Name',
        'field_duration' => 'Duration',
        'field_song_album' => 'Album',
        'field_song_artist' => 'Artist',
      ],
    ];

    $fields = $fields_map[$type] ?? [];

    if (empty($fields)) {
      $form['no_fields'] = [
        '#markup' => $this->t('No fields available for this type.'),
      ];
      return $form;
    }

    $form['fields_table'] = [
      '#type' => 'table',
      '#header' => [$this->t('Field'), $this->t('Spotify'), $this->t('Discogs'), $this->t('Use This')],
    ];
    \Drupal::logger('music_search')->notice('Details: ' . print_r($details, TRUE));
    // Loop through each field and create a row in the table.
    foreach ($fields as $machine_name => $label) {
      $spotify_value = $details['spotify'][$this->normalizeKey($machine_name)] ?? 'N/A';

      $discogs_value = $details['discogs'][$this->normalizeKey($machine_name)] ?? 'N/A';

      $form['fields_table'][$machine_name]['field_label'] = [
        '#plain_text' => $label,
      ];
      $form['fields_table'][$machine_name]['spotify'] = [
        '#markup' => Markup::create($spotify_value),
      ];
      $form['fields_table'][$machine_name]['discogs'] = [
        '#markup' => Markup::create($discogs_value),
      ];

      // Radios to choose which source or none.
      $form['fields_table'][$machine_name]['use_this'] = [
        '#type' => 'radios',
        '#options' => [
          $spotify_value => $this->t('Spotify'),
          $discogs_value => $this->t('Discogs'),
          '' => $this->t('None'),
        ],
        '#default_value' => $spotify_value,
      ];
    }

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create Content'),
    ];

    // Pass type and details along if needed.
    $form['type'] = [
      '#type' => 'value',
      '#value' => $type,
    ];

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $type = $form_state->getValue('type');
    $selected_fields = [];

    foreach ($form['fields_table'] as $machine_name => $row) {
      if (is_array($row) && isset($row['use_this'])) {
        $chosen = $form_state->getValue(['fields_table', $machine_name, 'use_this']);
        $selected_fields[$machine_name] = $chosen;
      }
    }

    // Redirect to createContent route with chosen fields.
    $form_state->setRedirect('music_search.create_content', [
      'type' => $type,
    ], [
      'query' => ['fields' => json_encode($selected_fields)],
    ]);
  }

  /**
   * Normalize the machine name or 'title' to match details keys.
   */
  protected function normalizeKey($key) {
    // For simplicity, assume detail arrays use 'name' for title.
    // Adjust logic as needed based on your actual data structure.
    if ($key === 'title') {
      return 'name';
    }
    // For year_of_release could be 'release_date' or something else.
    // If keys differ, map them accordingly here.
    if ($key === 'field_year_of_release') {
      return 'release_date';
    }
    if ($key === 'field_album_cover') {
      return 'image_url';
    }
    if ($key === 'field_artist_picture') {
      return 'image';
    }
    if ($key === 'field_song_album') {
      return 'album';
    }
    if ($key === 'field_song_artist' || $key === 'field_artist') {
      return 'artist';
    }
    if ($key === 'field_album_description') {
      return 'description';
    }
    if ($key === 'field_album_publisher') {
      return 'publisher';
    }
    if ($key === 'field_album_songs') {
      return 'songs';
    }
    if ($key === 'field_album_genres') {
      return 'genres';
    }
    if ($key === 'field_date_of_birth') {
      return 'date_of_birth';
    }
    if ($key === 'field_date_of_death') {
      return 'date_of_death';
    }
    if ($key === 'field_website') {
      return 'website';
    }
    if ($key === 'field_duration') {
      return 'duration';
    }

    // Default fallback: use as is.
    return $key;
  }
}
