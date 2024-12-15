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

    // Create the table header dynamically based on available services.
    $services = array_keys($details); // e.g., ['spotify', 'discogs', 'apple_music']
    $header = array_merge([$this->t('Field')], array_map([$this, 't'], $services), [$this->t('Use This')]);

    $form['fields_table'] = [
      '#type' => 'table',
      '#header' => $header,
    ];

    // Loop through each field and create a row in the table.
    foreach ($fields as $machine_name => $label) {
      $row = [
        'field_label' => ['#plain_text' => $label],
      ];

      $radio_options = [];

      // Add a column for each service dynamically.
      foreach ($services as $service) {
        $value = $details[$service][$this->normalizeKey($machine_name)] ?? 'N/A';
        $row[$service] = ['#markup' => Markup::create($value)];
        $radio_options[$value] = ucfirst($service);
      }

      // Add the "None" option.
      $radio_options[''] = $this->t('None');

      // Add the radio button column.
      $row['use_this'] = [
        '#type' => 'radios',
        '#options' => $radio_options,
        '#default_value' => array_key_first($radio_options), // Default to the first service's value.
      ];

      $form['fields_table'][$machine_name] = $row;
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

  protected function normalizeKey($key) {
    // Maps keys from machine names to the keys used in the details array.
    $key_mappings = [
      'title' => 'name',
      'field_year_of_release' => 'release_date',
      'field_album_cover' => 'image_url',
      'field_artist_picture' => 'image',
      'field_song_album' => 'album',
      'field_song_artist' => 'artist',
      'field_album_description' => 'description',
      'field_album_publisher' => 'publisher',
      'field_album_songs' => 'songs',
      'field_album_genres' => 'genres',
      'field_date_of_birth' => 'date_of_birth',
      'field_date_of_death' => 'date_of_death',
      'field_website' => 'website',
      'field_duration' => 'duration',
    ];

    return $key_mappings[$key] ?? $key;
  }
}
