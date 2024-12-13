<?php


namespace Drupal\music_search\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form to choose fields to save for an entity from Discogs or Spotify.
 */
class EntityFieldSelectorForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return 'entity_field_selector_with_sources_form';
  }

  /**
   * Builds the form for selecting fields to save.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array
  {
    // Example combined details from Discogs and Spotify for a single entity.
    $entity_details = $form_state->get('entity_details') ?: $this->getDefaultEntityDetails();

    $form['entity_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Entity Details'),
      '#open' => TRUE,
      '#description' => $this->t('Choose the source (Discogs or Spotify) for each detail field.'),
    ];

    // Build form for each field.
    foreach ($entity_details as $field => $values) {
      // Add a fieldset for each field.
      $form['entity_details'][$field] = [
        '#type' => 'fieldset',
        '#title' => ucfirst(str_replace('_', ' ', $field)),
      ];

      // Show both Discogs and Spotify values as options for this field.
      $form['entity_details'][$field]['discogs'] = [
        '#type' => 'item',
        '#title' => $this->t('Discogs Value'),
        '#markup' => is_array($values['discogs']) ? implode(', ', $values['discogs']) : $values['discogs'],
      ];
      $form['entity_details'][$field]['spotify'] = [
        '#type' => 'item',
        '#title' => $this->t('Spotify Value'),
        '#markup' => is_array($values['spotify']) ? implode(', ', $values['spotify']) : $values['spotify'],
      ];

      // Radio buttons to choose between Discogs and Spotify.
      $form['entity_details'][$field]['source'] = [
        '#type' => 'radios',
        '#title' => $this->t('Select Source'),
        '#options' => [
          'discogs' => $this->t('Use Discogs Value'),
          'spotify' => $this->t('Use Spotify Value'),
        ],
        '#required' => TRUE,
        '#default_value' => 'spotify', // Default to Spotify, for example.
      ];
    }

    // Add a submit button.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Selected Fields'),
    ];

    return $form;
  }

  /**
   * Provide a default set of entity details for example purposes.
   *
   * Simulates a single entity's data from both Discogs and Spotify.
   */
  protected function getDefaultEntityDetails(): array
  {
    return [
      'name' => [
        'discogs' => 'The Rolling Stones (Discogs)',
        'spotify' => 'The Rolling Stones (Spotify)',
      ],
      'genres' => [
        'discogs' => ['Rock', 'Blues'],
        'spotify' => ['Blues Rock', 'Classic Rock'],
      ],
      'popularity' => [
        'discogs' => '85',
        'spotify' => '87',
      ],
      'followers' => [
        'discogs' => '10,123,456',
        'spotify' => '12,345,678',
      ],
      'image_url' => [
        'discogs' => 'https://discogs.com/rolling_stones_image.png',
        'spotify' => 'https://spotify.com/rolling_stones_image.png',
      ],
    ];
  }

  /**
   * Handles form submission.
   * @throws EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    // Get all selected values from the form.
    $selected_fields = $form_state->getValues()['entity_details'];

    // Prepare data to save.
    // Use the chosen source for each field.
    $data_to_save = array_map(function ($choices) {
      return $choices['source'] == 'discogs'
        ? $choices['discogs']
        : $choices['spotify'];
    }, $selected_fields);


    $entity_type = 'artist';

    // Save to the appropriate entity type.
    try {
      $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    } catch (InvalidPluginDefinitionException|PluginNotFoundException $e) {

    }
    $entity = $storage->create($data_to_save);
    $entity->save();


    $this->messenger()->addMessage($this->t('The %type entity was successfully saved with the selected fields.', [
      '%type' => ucfirst($entity_type),
    ]));
  }
}
