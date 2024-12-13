<?php

namespace Drupal\spotify_lookup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\spotify_lookup\SpotifyTokenService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a settings form for Spotify Lookup.
 */
class SpotifySettingsForm extends ConfigFormBase {

  /**
   * The Spotify token service.
   *
   * @var SpotifyTokenService
   */
  protected SpotifyTokenService $tokenService;

  /**
   * Constructs a SpotifySettingsForm object.
   *
   * @param SpotifyTokenService $tokenService
   *   The Spotify token service.
   */
  public function __construct(SpotifyTokenService $tokenService) {
    $this->tokenService = $tokenService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('music_search.spotify_lookup.token_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['spotify_lookup.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'spotify_lookup_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('spotify_lookup.settings');

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client ID'),
      '#default_value' => $config->get('client_id'),
      '#required' => TRUE,
    ];

    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#required' => TRUE,
    ];

    $form['api_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify API Token'),
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
      $this->config('spotify_lookup.settings')
        ->set('client_id', $clientId)
        ->set('client_secret', $clientSecret)
        ->set('api_token', $accessToken)
        ->save();

      $this->messenger()->addMessage($this->t('Spotify API token generated and saved successfully.'));
    }
    else {
      $this->messenger()->addError($this->t('Failed to generate Spotify API token. Please check your Client ID and Client Secret.'));
    }

    parent::submitForm($form, $form_state);
  }
}
