<?php

namespace Drupal\spotify_lookup;

/**
 * Service to extract Spotify track IDs from URIs.
 */
class SpotifyUriExtractor {

  /**
   * Extracts the track ID from a Spotify URI.
   *
   * Given a string like "spotify:track:{TRACK_ID}",
   * this method returns "{TRACK_ID}".
   *
   * @param string $spotify_uri
   *   The Spotify URI string.
   *
   * @return string|null
   *   The extracted track ID, or NULL if it can't be found.
   */
  public function extractTrackId(string $spotify_uri): ?string {
    // A simple approach is to split by ':' and check the last segment.
    // Expected format: spotify:track:{TRACK_ID}
    $parts = explode(':', $spotify_uri);

    // We expect 3 parts: ['spotify', 'track', TRACK_ID]
    if (count($parts) === 3 && $parts[0] === 'spotify') {
      return $parts[2];
    }

    // If it doesn't match the expected format, return NULL.
    return NULL;
  }

}
