<?php

namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\music_search\Entity;


/**
 * Defines the Artist entity.
 *
 * @ContentEntityType(
 *   id = "artist",
 *   label = @Translation("Artist"),
 *   base_table = "node",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "nid",
 *     "label" = "title",
 *   },
 *   forms = {
 *    "default" = "Drupal\music_search\Form\ArtistEntityForm",
 *    "add" = "Drupal\music_search\Form\ArtistEntityForm",
 *    "edit" = "Drupal\music_search\Form\ArtistEntityForm",
 *    "delete" = "Drupal\core\Entity\EntityDeleteForm",
 *   },
 *   links = {
 *      "canonical" = "/artist/{artist}",
 *      "edit-form" = "/artist/{artist}/edit",
 *      "delete-form" = "/artist/{artist}/delete",
 *   },
 *   fieldable = TRUE,
 *   entity_type = "artist",
 *   bundle_entity_type = "node"
 * )
 */

class Artist extends ContentEntityBase implements ArtistInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getStartDate(): string {
    return $this->get('field_start_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStartDate(string $start_date): ArtistInterface {
    $this->set('field_start_date', $start_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndDate(): string {
    return $this->get('field_end_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEndDate(string $end_date): ArtistInterface {
    $this->set('field_end_date', $end_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndYear(): int {
    return $this->get('field_end_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEndYear(int $end_year): ArtistInterface {
    $this->set('field_end_year', $end_year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription(): string {
    return $this->get('field_description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription(string $description): ArtistInterface {
    $this->set('field_description', $description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMembers(): string {
    return $this->get('field_members')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMembers(string $members): ArtistInterface {
    $this->set('field_members', $members);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistCollection(): string {
    return $this->get('field_artist_collection')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setArtistCollection(string $artist_collection): ArtistInterface {
    $this->set('field_artist_collection', $artist_collection);
    return $this;
  }

  /**
   * {@inheritdoc}
   * @param string $band_photo
   */
  public function getBandPhoto(): string
  {
    return $this->get('field_band_photo')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBandPhoto(string $band_photo): ArtistInterface {
    $this->set('field_band_photo', $band_photo);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNafnListamanns(): string {
    return $this->get('field_nafn_listamanns')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNafnListamanns(string $nafn_listamanns): ArtistInterface {
    $this->set('field_nafn_listamanns', $nafn_listamanns);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistPortrait(): string {
    return $this->get('field_artist_portrait')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setArtistPortrait(string $artist_portrait): ArtistInterface {
    $this->set('field_artist_portrait', $artist_portrait);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormed(): string {
    return $this->get('field_formed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFormed(string $formed): ArtistInterface {
    $this->set('field_formed', $formed);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWebsiteUrl(): string {
    return $this->get('field_website_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWebsiteUrl(string $website_url): ArtistInterface {
    $this->set('field_website_url', $website_url);
    return $this;
  }



  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['field_start_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start Date'))
      ->setRequired(TRUE)
      ->setSetting('datetime_type', 'date')
      ->setSetting('date_format', 'Y-m-d');

    $fields['field_end_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('End Date'))
      ->setSetting('datetime_type', 'date')
      ->setSetting('date_format', 'Y-m-d');

    $fields['field_end_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('End Year'))
      ->setSetting('min', 1900)
      ->setSetting('max', 2100);

    $fields['field_description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setRequired(TRUE);

    $fields['field_members'] = BaseFieldDefinition::create('text')
      ->setLabel(t('Members'))
      ->setRequired(FALSE);

    $fields['field_artist_collection'] = BaseFieldDefinition::create('text')
      ->setLabel(t('Artist Collection'))
      ->setRequired(FALSE);

    $fields['field_band_photo'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Band Photo'))
      ->setRequired(FALSE);


    $fields['field_nafn_listamanns'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Listamanns Name'))
      ->setRequired(TRUE);

    // Artist portrait field (image)
    $fields['field_artist_portrait'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Artist Portrait'))
      ->setRequired(FALSE);

    // Formed field (date or text)
    $fields['field_formed'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Formed'))
      ->setSetting('datetime_type', 'date')
      ->setSetting('date_format', 'Y-m-d');

    // Website URL field (URL)
    $fields['field_website_url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Website URL'))
      ->setRequired(FALSE);

    return $fields;
  }


}
