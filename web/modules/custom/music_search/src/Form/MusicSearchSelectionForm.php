<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

/**
 * Provides a form for selecting a music item from search results.
 */
class MusicSearchSelectionForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'music_search_selection_form';
  }

  /**
   * Builds the form.
   *
   * @param array $form
   *   An associative array containing the structure of the form elements.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   * @param array $results
   *   An associative array of search results, keyed by provider.
   *   Example:
   *   [
   *     'spotify' => [
   *       ['name' => 'Song A', 'type' => 'song', 'uri' => 'spotify:track:xyz', 'image' => '...'],
   *       ...
   *     ],
   *     'discogs' => [
   *       ['name' => 'Album B', 'type' => 'album', 'uri' => 'discogs:...', 'image' => '...'],
   *       ...
   *     ]
   *   ]
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $results = []): array
  {
    \Drupal::logger('music_search_selection_form')->debug('The provided data for selection form: @results', [
      '@results' => print_r($results, TRUE),
    ]);

    // Initialize an array for options and providers.
    $provider_options = [];

    // Build an array of options grouped by provider.
    foreach ($results as $provider => $items) {
      $provider_items = [];
      foreach ($items as $item) {
        // Encode details into a JSON string.
        $encoded = json_encode([
          'provider' => $provider,
          'type' => $item['type'],
          'uri' => $item['uri'],
        ]);

        // Prepare the label with an optional image.
        $label_text = $item['name'] . ' (' . $item['type'] . ')';
        if (!empty($item['artist'])) {
          $label_text =  $item['artist'] . ' - ' . $label_text;
        }
        $image_html = '';
        if (!empty($item['image'])) {
          $image_html = '<img src="' . $item['image'] . '" alt="" width="50" height="50" style="vertical-align: middle; margin-right: 5px;">';
        }

        $provider_items[$encoded] = Markup::create($image_html . $label_text);
      }

      $provider_options[$provider] = $provider_items;
    }

    // If no results, provide a message.
    if (empty($provider_options)) {
      $form['no_results'] = [
        '#markup' => $this->t('No results found.'),
      ];
      return $form;
    }

    // Build the radios grouped by provider.
    $form['selected_item'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['provider-columns'],
      ],
    ];

    foreach ($provider_options as $provider => $items) {
      $form['selected_item'][$provider] = [
        '#type' => 'radios',
        '#title' => $this->t($provider),
        '#options' => $items,
        '#required' => FALSE, // Let the overall form enforce selection.
        '#attributes' => [
          'class' => ['provider-column'],
        ],
      ];
    }

    // Add a single submit button.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Get Details'),
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array &$form
   *   The form structure.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    // Initialize an array to store all selected values.
    $selected_items = [];

    // Loop through each provider's radio button group to collect selected items.
    foreach ($form['selected_item'] as $provider_key => $provider_group) {
      if (is_array($provider_group) && isset($provider_group['#options'])) {
        $selected_value = $form_state->getValue($provider_key);
        if (!empty($selected_value)) {
          $decoded_value = json_decode($selected_value, TRUE);
          if (!empty($decoded_value['provider']) && !empty($decoded_value['type']) && !empty($decoded_value['uri'])) {
            $selected_items[] = $decoded_value;
          }
        }
      }
    }

    if (!empty($selected_items)) {
      // Log the selected items for debugging.
      \Drupal::logger('search_selection')->debug('The selected objects: @selected', [
        '@selected' => print_r($selected_items, TRUE),
      ]);
      $this->getRequest()->getSession()->set('selected_items', $selected_items);
      // Redirect to the detail query route with all selected items.
      $form_state->setRedirect('music_search.detail_query', []);
    }
    else {
      $this->messenger()->addError($this->t('You must select at least one item.'));
    }
  }


}
