<?php

namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the Album entity as a content type (node type) "album".
 *
 * @ContentEntityType(
 *   id = "album",
 *   label = @Translation("Album"),
 *   base_table = "node",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "nid",
 *     "label" = "title",
 *   },
 *   forms = {
 *   "default" = "Drupal\music_search\Form\AlbumEntityForm",
 *   "add" = "Drupal\music_search\Form\AlbumEntityForm",
 *   "edit" = "Drupal\music_search\Form\AlbumEntityForm",
 *   "delete" = "Drupal\core\Entity\EntityDeleteForm",
 *   },
 *   links = {
 *     "canonical" = "/album/{album}",
 *     "edit-form" = "/album/{album}/edit",
 *     "delete-form" = "/album/{album}/delete",
 *   },
 *   fieldable = TRUE,
 *   entity_type = "album",
 *   bundle_entity_type = "node",
 *   bundle_label = @Translation("Album Content Type"),
 *   bundle_key = "type",
 *   )
 */

class Album extends ContentEntityBase implements AlbumInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle(string $title): AlbumInterface {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAlbumGenres(): array {
    return $this->get('field_album_genres')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setAlbumGenres(array $genres): AlbumInterface {
    $this->set('field_album_genres', $genres);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPerformer(): array {
    return $this->get('field_performer')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setPerformer(array $performers): AlbumInterface {
    $this->set('field_performer', $performers);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAlbumSongs(): array {
    return $this->get('field_album_songs')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setAlbumSongs(array $songs): AlbumInterface {
    $this->set('field_album_songs', $songs);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAlbumDescription(): string {
    return $this->get('field_album_description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAlbumDescription(string $description): AlbumInterface {
    $this->set('field_album_description', $description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCoverPhoto(): string {
    return $this->get('field_cover_photo')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCoverPhoto(string $cover_photo): AlbumInterface {
    $this->set('field_cover_photo', $cover_photo);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAlbumPublisher(): string {
    return $this->get('field_album_publisher')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAlbumPublisher(string $publisher): AlbumInterface {
    $this->set('field_album_publisher', $publisher);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPublicationYear(): int {
    return $this->get('field_publication_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublicationYear(int $publication_year): AlbumInterface {
    $this->set('field_publication_year', $publication_year);
    return $this;
  }





  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Album Title'))
      ->setRequired(TRUE);

    $fields['field_album_genres'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Album Genres'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setRequired(TRUE);

    $fields['field_performer'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Performer(s)'))
      ->setSetting('target_type', 'node')  // Or 'user' if performers are users
      ->setRequired(FALSE);

    $fields['field_album_songs'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Album Songs'))
      ->setSetting('target_type', 'node')  // Reference to song nodes
      ->setRequired(FALSE);

    $fields['field_album_description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Album Description'))
      ->setRequired(FALSE);

    $fields['field_cover_photo'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Cover Photo'))
      ->setSetting('file_directory', 'album_covers')
      ->setRequired(FALSE);

    $fields['field_album_publisher'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Album Publisher'))
      ->setRequired(FALSE);

    $fields['field_publication_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Publication Year'))
      ->setRequired(FALSE);

    return $fields;
  }
}
