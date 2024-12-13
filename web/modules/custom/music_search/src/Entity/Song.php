<?php

namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the Song entity as a content type (node type) "song".
 *
 * @ContentEntityType(
 *   id = "song",
 *   label = @Translation("Song"),
 *   base_table = "node",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "nid",
 *     "label" = "title",
 *   },
 *   forms = {
 *     "default" = "Drupal\music_search\Form\SongEntityForm",
 *     "add" = "Drupal\music_search\Form\SongEntityForm",
 *     "edit" = "Drupal\music_search\Form\SongEntityForm",
 *     "delete" = "Drupal\core\Entity\EntityDeleteForm",
 *   },
 *   links = {
 *      "canonical" = "/song/{song}",
 *      "edit-form" = "/song/{song}/edit",
 *      "delete-form" = "/song/{song}/delete",
 *   },
 *   fieldable = TRUE,
 *   entity_type = "song",
 *   bundle_entity_type = "node",
 *   bundle_label = @Translation("Song Content Type"),
 *   bundle_key = "type"
 *  )
 */
class Song extends ContentEntityBase implements SongInterface{
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function setTitle(string $title): songInterface {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSongGenres(): array {
    return $this->get('field_song_genres')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setSongGenres(array $genres): SongInterface {
    $this->set('field_song_genres', $genres);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLength(): string {
    return $this->get('field_length')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLength(string $length): SongInterface {
    $this->set('field_length', $length);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMusicVideo(): string {
    return $this->get('field_music_video')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMusicVideo(string $music_video): SongInterface {
    $this->set('field_music_video', $music_video);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpotifyId(): string {
    return $this->get('field_spotifyid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSpotifyId(string $spotifyid): SongInterface {
    $this->set('field_spotifyid', $spotifyid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Song Title'))
      ->setRequired(TRUE);

    $fields['field_song_genres'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Song Genres'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setRequired(TRUE);

    $fields['field_length'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Song Length'))
      ->setRequired(TRUE);

    $fields['field_music_video'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Music Video'))
      ->setSetting('uri_scheme', 'public')
      ->setRequired(FALSE);

    $fields['field_spotifyid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Spotify ID'))
      ->setRequired(FALSE);

    return $fields;
  }
}
