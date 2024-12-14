<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

/**
 * Provides a form for selecting a music item from search results.
 */
class MusicSearchSelectionForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'music_search_selection_form';
  }

  /**
   * Builds the form.
   *
   * @param array $form
   *   An associative array containing the structure of the form elements.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
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
  public function buildForm(array $form, FormStateInterface $form_state, array $results = []) {
    $options = [];

    // Build an array of options for the radio buttons.
    foreach ($results as $provider => $items) {
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
            $label_text .= ' - Artist: ' . $item['artist'];
        }
        $image_html = '';
        if (!empty($item['image'])) {
          // Add a small square image (e.g., 50x50).
          // Adjust width/height as desired.
          $image_html = '<img src="' . $item['image'] . '" alt="" width="50" height="50" style="vertical-align: middle; margin-right: 5px;">';
        }

        // Combine the image and label into safe markup.
        $options[$encoded] = Markup::create($image_html . $label_text);
      }
    }

    // If no results, provide a message.
    if (empty($options)) {
      $form['no_results'] = [
        '#markup' => $this->t('No results found.'),
      ];
      return $form;
    }

    $form['selected_item'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select an item'),
      '#options' => $options,
      '#required' => TRUE,
    ];

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
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected = json_decode($form_state->getValue('selected_item'), TRUE);

    if (!empty($selected['provider']) && !empty($selected['type']) && !empty($selected['uri'])) {
      // Redirect to the detail query route with these parameters.
      $form_state->setRedirect('music_search.detail_query', [
        'provider' => $selected['provider'],
        'type' => $selected['type'],
        'uri' => $selected['uri'],
      ]);
    }
    else {
      $this->messenger()->addError($this->t('Invalid selection.'));
    }
  }

}
