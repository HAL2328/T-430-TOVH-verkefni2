<?php

namespace Drupal\discogs_lookup;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Service to manage Discogs API tokens.
 */
class DiscogsTokenService {

  /**
   * HTTP client.
   *
   * @var ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * Constructs a DiscogsTokenService object.
   *
   * @param ClientInterface $httpClient
   *   The HTTP client.
   */
  public function __construct(ClientInterface $httpClient) {
    $this->httpClient = $httpClient;
  }

  /**
   * Fetches an access token from Discogs.
   *
   * @param string $consumerKey
   *   The Discogs Consumer Key.
   * @param string $consumerSecret
   *   The Discogs Consumer Secret.
   *
   * @return string|null
   *   The access token or NULL if the request fails.
   */
  public function fetchAccessToken(string $consumerKey, string $consumerSecret): ?string {
    $url = 'https://api.discogs.com/oauth/request_token';

    try {
      $response = $this->httpClient->post($url, [
        'headers' => [
          'Authorization' => 'OAuth',
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'auth' => [$consumerKey, $consumerSecret, 'oauth'],
      ]);

      // Parse the response body for the access token.
      $data = [];
      parse_str((string) $response->getBody(), $data);

      return $data['oauth_token'] ?? NULL;
    }
    catch (GuzzleException $e) {
      \Drupal::logger('discogs_lookup')->error('Failed to fetch Discogs access token: @message', ['@message' => $e->getMessage()]);
      return NULL;
    }
  }
}
