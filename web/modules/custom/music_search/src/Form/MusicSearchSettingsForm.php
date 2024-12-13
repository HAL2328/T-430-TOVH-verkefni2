<?php
namespace Drupal\music_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MusicSearchSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['music_search.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Add a text field for the Spotify API key
    $form['spotify_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify API Key'),
      '#default_value' => $this->config('music_search.settings')->get('spotify_api_key'),
      '#description' => $this->t('Enter your Spotify API key here.'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the API key in configuration
    $config = $this->config('music_search.settings');
    $config->set('spotify_api_key', $form_state->getValue('spotify_api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  public function getFormId()
  {
    // TODO: Implement getFormId() method.
  }
}


