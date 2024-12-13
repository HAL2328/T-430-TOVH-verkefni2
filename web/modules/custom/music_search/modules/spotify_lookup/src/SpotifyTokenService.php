<?php

namespace Drupal\spotify_lookup;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Service to manage Spotify API tokens.
 */
class SpotifyTokenService {

  /**
   * HTTP client.
   *
   * @var ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * Constructs a SpotifyTokenService object.
   *
   * @param ClientInterface $httpClient
   *   The HTTP client.
   */
  public function __construct(ClientInterface $httpClient) {
    $this->httpClient = $httpClient;
  }

  /**
   * Fetches an access token from Spotify.
   *
   * @param string $clientId
   *   The Spotify Client ID.
   * @param string $clientSecret
   *   The Spotify Client Secret.
   *
   * @return string|null
   *   The access token or NULL if the request fails.
   */
  public function fetchAccessToken(string $clientId, string $clientSecret): ?string {
    $url = 'https://accounts.spotify.com/api/token';

    try {
      $response = $this->httpClient->post($url, [
        'headers' => [
          'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
        ],
        'form_params' => [
          'grant_type' => 'client_credentials',
        ],
      ]);

      $data = json_decode($response->getBody(), TRUE);
      return $data['access_token'] ?? NULL;
    }
    catch (GuzzleException $e) {
      \Drupal::logger('spotify_lookup')->error('Failed to fetch Spotify access token: @message', ['@message' => $e->getMessage()]);
      return NULL;
    }
  }
}
