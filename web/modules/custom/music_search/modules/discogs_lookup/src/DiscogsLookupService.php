<?php

namespace Drupal\discogs_lookup;

use Drupal\music_search\SearchServiceInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Service to interact with Discogs's API.
 */
class DiscogsLookupService implements SearchServiceInterface
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
   * The Discogs result parser.
   *
   * @var DiscogsResultParser
   */
  protected DiscogsResultParser $resultParser;

  /**
   * Constructs a DiscogsLookupService object.
   *
   * @param ClientInterface $httpClient
   *   The HTTP client.
   * @param ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param DiscogsResultParser $resultParser
   *   The result parser service.
   */
  public function __construct(ClientInterface $httpClient, ConfigFactoryInterface $configFactory, DiscogsResultParser $resultParser)
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
    $discogsType = $type === 'song' ? 'track' : $type;

    // Get the stored API token.
    $config = $this->configFactory->get('discogs_lookup.settings');
    $consumerKey = $config->get('consumer_key');
    $consumerSecret = $config->get('consumer_secret');

    \Drupal::logger('discogs_lookup')->debug('Consumer Key: @key, Consumer Secret: @secret', [
      '@key' => $consumerKey,
      '@secret' => $consumerSecret,
    ]);

    if (!$consumerKey || !$consumerSecret) {
      \Drupal::logger('discogs_lookup')->error(
        'No client Key-Secret pair available for Discogs.'
      );
      return [];
    }

    // Discogs API URL.
    $url = 'https://api.discogs.com/database/search';

    try {
      // Send the request to Discogs API.
      $response = $this->httpClient->get($url, [
        'query' => [
          'q' => $term,
          'type' => $discogsType,
          'key' => $consumerKey,
          'secret' => $consumerSecret,
        ],
      ]);

      \Drupal::logger('discogs_lookup')->debug('Raw API Response: @response', [
        '@response' => $response->getBody()->getContents(),
      ]);

      // Decode the JSON response.
      $data = json_decode($response->getBody(), TRUE);

      // Extract the items for the specified type.
      $items = $data['results'] ?? [];

      \Drupal::logger('discogs_lookup')->debug('Extracted items: @items', [
        '@items' => print_r($items, TRUE),
      ]);


      // Use the parser to generate markup.
      return $this->resultParser->parseResults($items, $discogsType);
    } catch (GuzzleException $e) {
      \Drupal::logger('discogs_lookup')->error('Discogs API error: @message', ['@message' => $e->getMessage()]);

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

    $url = 'https://api.discogs.com/v1/';

    $url = $url . strtolower($params['type']) . "s/";

    // Get the stored API token.
    $config = $this->configFactory->get('discogs_lookup.settings');
    $accessToken = $config->get('api_token');

    if (!$accessToken) {
      \Drupal::logger('discogs_lookup')->error('No API token available for Discogs.');
      return [];
    }

    try {
      // Send the request to Discogs API.
      $response = $this->httpClient->get($url, [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
        ],
      ]);
      $data = json_decode($response->getBody(), TRUE);

      // Parse out the details we want before returning
      return $this->resultParser->parseDetails($data, $params['type']);
    } catch (GuzzleException $e) {
      \Drupal::logger('discogs_lookup')->error('Discogs API error: @message', ['@message' => $e->getMessage()]);

      return [];
    }
  }

}

