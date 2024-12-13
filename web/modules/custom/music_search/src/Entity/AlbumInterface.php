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
