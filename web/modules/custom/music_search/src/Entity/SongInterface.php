<?php

namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents an Artist entity.
 */
interface SongInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the title of the Song entity.
   *
   * @return string
   *   The title of the Song entity.
   */
  public function setTitle(string $title): SongInterface;

  /**
   * Gets the title of the Song entity.
   *
   * @return string
   *   The title of the Song entity.
   */
  public function getTitle(): string;

  /**
   * Gets the genres of the Song entity.
   *
   * @return array
   *   The genres of the Song entity.
   */
  public function getSongGenres(): array;

  /**
   * Sets the genres of the Song entity.
   *
   * @param array $genres
   *   The genres of the Song entity.
   *
   * @return $this
   */
  public function setSongGenres(array $genres): SongInterface;

  /**
   * Gets the length of the Song entity.
   *
   * @return string
   *   The length of the Song entity.
   */
  public function getLength(): string;

  /**
   * Sets the length of the Song entity.
   *
   * @param string $length
   *   The length of the Song entity.
   *
   * @return $this
   */
  public function setLength(string $length): SongInterface;

  /**
   * Gets the music video of the Song entity.
   *
   * @return string
   *   The music video of the Song entity.
   */
  public function getMusicVideo(): string;

  /**
   * Sets the music video of the Song entity.
   *
   * @param string $music_video
   *   The music video of the Song entity.
   *
   * @return $this
   */
  public function setMusicVideo(string $music_video): SongInterface;

  /**
   * Gets the Spotify ID of the Song entity.
   *
   * @return string
   *   The Spotify ID of the Song entity.
   */
  public function getSpotifyId(): string;

  /**
   * Sets the Spotify ID of the Song entity.
   *
   * @param string $spotify_id
   *   The Spotify ID of the Song entity.
   *
   * @return $this
   */
  public function setSpotifyId(string $spotify_id): SongInterface;

}
