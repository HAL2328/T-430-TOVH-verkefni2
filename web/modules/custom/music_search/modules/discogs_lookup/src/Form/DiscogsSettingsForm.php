<?php

namespace Drupal\discogs_lookup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\discogs_lookup\DiscogsTokenService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a settings form for discogs Lookup.
 */
class discogsSettingsForm extends ConfigFormBase {

  /**
   * The discogs token service.
   *
   * @var DiscogsTokenService
   */
  protected DiscogsTokenService $tokenService;

  /**
   * Constructs a discogsSettingsForm object.
   *
   * @param DiscogsTokenService $tokenService
   *   The discogs token service.
   */
  public function __construct(DiscogsTokenService $tokenService) {
    $this->tokenService = $tokenService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('music_search.discogs_lookup.token_service')
    );
  }

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

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Discogs Client ID'),
      '#default_value' => $config->get('client_id'),
      '#required' => TRUE,
    ];

    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Discogs Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#required' => TRUE,
    ];

    $form['api_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Discogs API Token'),
      '#default_value' => $config->get('api_token'),
      '#description' => $this->t('This token is automatically generated and stored.'),
      '#disabled' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $clientId = $form_state->getValue('client_id');
    $clientSecret = $form_state->getValue('client_secret');

    // Generate the access token.
    $accessToken = $this->tokenService->fetchAccessToken($clientId, $clientSecret);

    if ($accessToken) {
      // Save the values and the token to configuration.
      $this->config('discogs_lookup.settings')
        ->set('client_id', $clientId)
        ->set('client_secret', $clientSecret)
        ->set('api_token', $accessToken)
        ->save();

      $this->messenger()->addMessage($this->t('Discogs API token generated and saved successfully.'));
    }
    else {
      $this->messenger()->addError($this->t('Failed to generate Discogs API token. Please check your Client ID and Client Secret.'));
    }

    parent::submitForm($form, $form_state);
  }
}
