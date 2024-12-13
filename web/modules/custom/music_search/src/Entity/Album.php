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
 *      "uuid" = "uuid",
 *   },
 *   handlers = {
 *    "form" = {
 *      "add" = "Drupal\music_search\Form\AlbumEntityForm",
 *      },
 *   },
 *   links = {
 *     "canonical" = "/album/{album}",
 *     "add-form" = "/album/add",
 *   },
 *   fieldable = TRUE,
 *   entity_type = "node",
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
  public function getAlbumPublisher(): string {
    return $this->get('field_publisher')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAlbumPublisher(string $publisher): AlbumInterface {
    $this->set('field_publisher', $publisher);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPublicationYear(): int {
    return $this->get('field_year_of_release')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublicationYear(int $publication_year): AlbumInterface {
    $this->set('field_year_of_release', $publication_year);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time the entity was created.'))
      ->setRevisionable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time the entity was last edited.'))
      ->setRevisionable(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Album Title'))
      ->setRequired(TRUE);


    $fields['field_publisher'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Album Publisher'))
      ->setRequired(FALSE);

    $fields['field_year_of_release'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Publication Year'))
      ->setRequired(FALSE);

    return $fields;
  }
}
