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
    return new static(
      $container->get('request_stack')->getCurrentRequest()->get('type'),
      $container->get('request_stack')->getCurrentRequest()->get('details')
    );
  }

  public function getFormId(): string
  {
    return 'entity_field_selector_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL, $details = NULL): array
  {
    // If parameters are passed directly, otherwise use defaults.
    $type = $type ?: $this->type;
    $details = $details ?: $this->details;

    // Validate the details structure.
    if (!is_array($details)) {
      return [
        '#markup' => $this->t('Invalid details provided for this form.'),
      ];
    }

    // Define fields based on type.
    $fields_map = [
      'artist' => [
        'title' => 'Name',
        'field_artist_picture' => 'Image URL',
        'field_website' => 'Website',
        'body' => 'body',
        'field_date_of_birth' => 'date_of_birth',
        'field_date_of_death' => 'date_of_death',
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
    $services = array_keys($details); // e.g., ['spotify', 'discogs']

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
        // Normalize the machine name to get the correct key for the provider.
        $normalized_key = $this->normalizeKey($machine_name);

        // Fetch the value from the details array using the normalized key.
        $value = $details[$service][$normalized_key] ?? 'N/A';
        $row[$service] = ['#markup' => Markup::create($value)];
        $radio_options[$value] = ucfirst($service);
      }

      // Add the "None" option for users who might not want to select a value.
      $radio_options[''] = $this->t('None');

      // Add the radio button column.
      $row['use_this'] = [
        '#type' => 'radios',
        '#options' => $radio_options,
        '#default_value' => array_key_first($radio_options), // Default to the first service's value.
      ];

      $form['fields_table'][$machine_name] = $row;
    }

    // Submit button to create the content.
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

  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
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

  protected function normalizeKey($key): string
  {
    // Maps keys from machine names to the keys used in the details array.
    $key_mappings = [
      'title' => 'name',
      'field_website' => 'website',
      'body' => 'body',
      'field_date_of_birth' => 'date_of_birth',
      'field_date_of_death' => 'date_of_death',
    ];

    return $key_mappings[$key] ?? $key;
  }
}
