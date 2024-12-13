<?php
namespace Drupal\music_search\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents an Artist entity.
 */
interface ArtistInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Artist name.
   *
   * @return string
   *   The name of the artist.
   */
  public function getNafnListamanns() : string;

  /**
   * Sets the Artist name.
   *
   * @param string $nafn_listamanns
   *   The name of the artist.
   *
   * @return $this
   */
  public function setNafnListamanns(string $nafn_listamanns) : ArtistInterface;

  /**
   * Gets the Artist start date.
   *
   * @return string
   *   The start date of the artist.
   */
  public function getStartDate() : string;

  /**
   * Sets the Artist start date.
   *
   * @param string $start_date
   *   The start date of the artist.
   *
   * @return $this
   */
  public function setStartDate(string $start_date) : ArtistInterface;

  /**
   * Gets the Artist end date.
   *
   * @return string
   *   The end date of the artist.
   */
  public function getEndDate() : string;

  /**
   * Sets the Artist end date.
   *
   * @param string $end_date
   *   The end date of the artist.
   *
   * @return $this
   */
  public function setEndDate(string $end_date) : ArtistInterface;

  /**
   * Gets the Artist end year.
   *
   * @return int
   *   The end year of the artist.
   */
  public function getEndYear() : int;

  /**
   * Sets the Artist end year.
   *
   * @param int $end_year
   *   The end year of the artist.
   *
   * @return $this
   */
  public function setEndYear(int $end_year) : ArtistInterface;

  /**
   * Gets the Artist description.
   *
   * @return string
   *   The description of the artist.
   */
  public function getDescription() : string;

  /**
   * Sets the Artist description.
   *
   * @param string $description
   *   The description of the artist.
   *
   * @return $this
   */
  public function setDescription(string $description) : ArtistInterface;

  /**
   * Gets the Artist members if it´s a band.
   *
   * @return string
   *   The members of the band.
   */

  public function getMembers() : string;

  /**
   * Sets the members of the band.
   *
   * @param string $members
   *   The remote id of the artist.
   *
   * @return $this
   */
  public function setMembers(string $members) : ArtistInterface;

  /**
   * Gets the Artists collection of songs.
   *
   * @return string
   *   The song collection for the artist.
   */

  public function getArtistCollection() : string;

  /**
   * Sets the Artists collection of songs.
   *
   * @param string $artist_collection
   *   The song collection for the artist.
   *
   * @return $this
   */

  public function setArtistCollection(string $artist_collection) : ArtistInterface;

  /**
   * Gets the band photo.
   *
   * @return string
   *   The photo for the band.
   */

  public function getBandPhoto(): string;

   /**
    * Sets the band photo.
    *
    * @param string $band_photo
    *   The photo for the band.
    *
    * @return $this
    */
  public function setBandPhoto(string $band_photo): ArtistInterface;


    /**
     * Gets the artist portrait.
     *
     * @return string
     *   The portrait for the artist.
     */
  public function getArtistPortrait(): string;

  /**
   * Sets the artist portrait.
   *
   * @param string $artist_portrait
   *   The portrait for the artist.
   *
   * @return $this
   */
  public function setArtistPortrait(string $artist_portrait): ArtistInterface;

  /**
   * Gets the band formed date.
   *
   * @return string
   *   The formed date for the artist.
   */
  public function getFormed(): string;

  /**
   * Sets the band formed date.
   *
   * @param string $formed
   *   The formed date for the artist.
   *
   * @return $this
   */
  public function setFormed(string $formed): ArtistInterface;

  /**
   * Gets the website url.
   *
   * @return string
   *   The website url for the artist.
   */
  public function getWebsiteUrl(): string;

  /**
   * Sets the website url.
   *
   * @param string $website_url
   *   The website url for the artist.
   *
   * @return $this
   */
  public function setWebsiteUrl(string $website_url): ArtistInterface;
}
