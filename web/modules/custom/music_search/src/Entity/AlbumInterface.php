<?php

namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents an Album entity.
 */
interface AlbumInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the title of the Album entity.
   *
   * @return string
   *   The title of the Album entity.
   */
  public function getTitle(): string;

  /**
   * Sets the title of the Album entity.
   *
   * @param string $title
   *   The title of the Album entity.
   *
   * @return $this
   */
  public function setTitle(string $title): AlbumInterface;

  /**
   * Gets the genres of the Album entity.
   *
   * @return array
   *   The genres of the Album entity.
   */
  public function getAlbumGenres(): array;

  /**
   * Sets the genres of the Album entity.
   *
   * @param array $genres
   *   The genres of the Album entity.
   *
   * @return $this
   */
  public function setAlbumGenres(array $genres): AlbumInterface;

  /**
   * Gets the performer of the Album entity.
   *
   * @return array
   *   The performer of the Album entity.
   */
  public function getPerformer(): array;

  /**
   * Sets the performer of the Album entity.
   *
   * @param array $performers
   *   The performer of the Album entity.
   *
   * @return $this
   */
  public function setPerformer(array $performers): AlbumInterface;

  /**
   * Gets the songs of the Album entity.
   *
   * @return array
   *   The songs of the Album entity.
   */
  public function getAlbumSongs(): array;

  /**
   * Sets the songs of the Album entity.
   *
   * @param array $songs
   *   The songs of the Album entity.
   *
   * @return $this
   */
  public function setAlbumSongs(array $songs): AlbumInterface;

  /**
   * Gets the description of the Album entity.
   *
   * @return string
   *   The description of the Album entity.
   */
  public function getAlbumDescription(): string;

  /**
   * Sets the description of the Album entity.
   *
   * @param string $description
   *   The description of the Album entity.
   *
   * @return $this
   */
  public function setAlbumDescription(string $description): AlbumInterface;

  /**
   * Gets the cover photo of the Album entity.
   *
   * @return string
   *   The cover photo of the Album entity.
   */
  public function getCoverPhoto(): string;

  /**
   * Sets the cover photo of the Album entity.
   *
   * @param string $cover_photo
   *   The cover photo of the Album entity.
   *
   * @return $this
   */
  public function setCoverPhoto(string $cover_photo): AlbumInterface;

  /**
   * Gets the album publisher.
   *
   * @return string
   *   The album publisher.
   */
  public function getAlbumPublisher(): string;

  /**
   * Sets the album publisher.
   *
   * @param string $publisher
   *   The album publisher.
   *
   * @return $this
   */
  public function setAlbumPublisher(string $publisher): AlbumInterface;

  /**
   * Gets the publication year of the Album entity.
   *
   * @return int
   *   The publication year of the Album entity.
   */
  public function getPublicationYear(): int;

  /**
   * Sets the publication year of the Album entity.
   *
   * @param int $publication_year
   *   The publication year of the Album entity.
   *
   * @return $this
   */
  public function setPublicationYear(int $publication_year): AlbumInterface;
}
