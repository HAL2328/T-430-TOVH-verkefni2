<?php

namespace App\Service;

use GuzzleHttp\Client;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\Discogs; // Use the Discogs specific server
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DiscogsLookupService
{
  private $client;
  private $server;
  private $session;
  private $requestStack;

  public function __construct(string $consumerKey, string $consumerSecret, RequestStack $requestStack, SessionInterface $session)
  {
    $this->server = new Discogs([ // Use the Discogs specific server
      'identifier' => $consumerKey,
      'secret' => $consumerSecret,
      'callback_uri' => 'https://drupalfy.ddev.site:8443/discogs/callback', // Important!
    ]);

    $this->client = new Client([
      'base_uri' => 'https://api.discogs.com/',
    ]);
    $this->requestStack = $requestStack;
    $this->session = $session;
  }

  public function getAuthorizationUrl()
  {
    $temporaryCredentials = $this->server->getTemporaryCredentials();

    // Store the temporary credentials in the session
    $this->session->set('discogs_temp_credentials', serialize($temporaryCredentials));

    return $this->server->getAuthorizationUrl($temporaryCredentials);
  }

  public function handleCallback()
  {
    $request = $this->requestStack->getCurrentRequest();

    if ($request->query->has('oauth_verifier')) {
      $oauthVerifier = $request->query->get('oauth_verifier');
      $tempCredentials = unserialize($this->session->get('discogs_temp_credentials'));
      $this->session->remove('discogs_temp_credentials');


      try {
        $tokenCredentials = $this->server->getTokenCredentials(
          $tempCredentials,
          $oauthVerifier
        );
        $this->session->set('discogs_token', serialize($tokenCredentials));
        return true;
      } catch (\Exception $e) {
        error_log($e->getMessage());
        return false;
      }

    }
    return false;
  }


  public function search(string $query)
  {
    $tokenCredentials = unserialize($this->session->get('discogs_token'));

    if (!$tokenCredentials) {
      return ['error' => 'Not authenticated with Discogs.'];
    }

    try {
      $response = $this->client->get('database/search', [
        'query' => $query,
        'type' => 'artist,release,master',
        'per_page' => 50,
        'headers' => [
          'Authorization' => 'OAuth ' . $tokenCredentials->getAuthToken(),
          'User-Agent' => 'YourAppName/1.0 +http://yourwebsite.com',
        ],
      ]);
      $data = json_decode($response->getBody(), true);
      return $data;
    } catch (\Exception $e) {
      error_log($e->getMessage());
      return ['error' => $e->getMessage()];
    }
  }
}
