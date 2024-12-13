<?php

namespace Drupal\spotify_lookup;

use Drupal\music_search\SearchServiceInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Drupal\spotify_lookup\SpotifyUriExtractor;


/**
 * Service to interact with Spotify's API.
 */
class SpotifyLookupService implements SearchServiceInterface
{

  /**
   * The HTTP client.
   *
   * @var ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * The configuration factory.
   *
   * @var ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The Spotify result parser.
   *
   * @var SpotifyResultParser
   */
  protected SpotifyResultParser $resultParser;

  /**
   * Constructs a SpotifyLookupService object.
   *
   * @param ClientInterface $httpClient
   *   The HTTP client.
   * @param ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param SpotifyResultParser $resultParser
   *   The result parser service.
   */
  public function __construct(ClientInterface $httpClient, ConfigFactoryInterface $configFactory, SpotifyResultParser $resultParser)
  {
    $this->httpClient = $httpClient;
    $this->configFactory = $configFactory;
    $this->resultParser = $resultParser;
  }

  /**
   * {@inheritdoc}
   */
  public function search(string $type, string $term): array
  {
    // Map 'song' to 'track'.
    $spotifyType = $type === 'song' ? 'track' : $type;

    // Get the stored API token.
    $config = $this->configFactory->get('spotify_lookup.settings');
    $accessToken = $config->get('api_token');

    if (!$accessToken) {
      \Drupal::logger('spotify_lookup')->error('No API token available for Spotify.');
      return [];
    }

    // Spotify API URL.
    $url = 'https://api.spotify.com/v1/search';

    try {
      // Send the request to Spotify API.
      $response = $this->httpClient->get($url, [
        'query' => [
          'q' => $term,
          'type' => $spotifyType,
        ],
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
        ],
      ]);


      // Decode the JSON response.
      $data = json_decode($response->getBody(), TRUE);

      // Extract the items for the specified type.
      $items = $data["{$spotifyType}s"]['items'] ?? [];

      // Use the parser to generate markup.
      return $this->resultParser->parseResults($items, $spotifyType);
    } catch (GuzzleException $e) {
      \Drupal::logger('spotify_lookup')->error('Spotify API error: @message', ['@message' => $e->getMessage()]);

      return [];
    }
  }

  public function getDetails(array $params): array
  {
    if (empty($params['uri']) || empty($params['type']) || empty($params['provider'])) {
      return [
        '#markup' => $this->t('Missing params.'),
      ];
    }

    $url = 'https://api.spotify.com/v1/';

    $url = $url . strtolower($params['type']) . "s/";

    $id = (new SpotifyUriExtractor())->extractTrackId($params['uri']);

    $url = $url . $id;

    // Get the stored API token.
    $config = $this->configFactory->get('spotify_lookup.settings');
    $accessToken = $config->get('api_token');

    if (!$accessToken) {
      \Drupal::logger('spotify_lookup')->error('No API token available for Spotify.');
      return [];
    }

    try {
      // Send the request to Spotify API.
      $response = $this->httpClient->get($url, [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
        ],
      ]);
      $data = json_decode($response->getBody(), TRUE);

      // Parse out the details we want before returning
      return $this->resultParser->parseDetails($data, $params['type']);
    } catch (GuzzleException $e) {
      \Drupal::logger('spotify_lookup')->error('Spotify API error: @message', ['@message' => $e->getMessage()]);

      return [];
    }
  }

}

