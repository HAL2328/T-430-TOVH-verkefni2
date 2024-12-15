<?php

namespace Drupal\discogs_lookup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a settings form for discogs Lookup.
 */
class discogsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['discogs_lookup.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'discogs_lookup_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('discogs_lookup.settings');

    $form['consumer_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Discogs Consumer Key'),
      '#default_value' => $config->get('consumer_key'),
      '#required' => TRUE,
    ];

    $form['consumer_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Discogs Consumer Secret'),
      '#default_value' => $config->get('consumer_secret'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $consumerKey = $form_state->getValue('consumer_key');
    $consumerSecret = $form_state->getValue('consumer_secret');

    $this->config('discogs_lookup.settings')
      ->set('consumer_key', $consumerKey)
      ->set('consumer_secret', $consumerSecret)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
