<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class AlbumEntityForm extends ContentEntityForm {

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    // Add input fields for Album
    $form['album'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Album Information'),
    ];
    $form['album']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Album Title'),
      '#required' => TRUE,
      '#parents' => ['album_title'],
    ];
    $form['album']['album_cover'] = [
      '#type' => 'url',
      '#title' => $this->t('Cover Image URL'),
      '#default_value' => '',
      '#parents' => ['album_image_url'],
    ];
    $form['album']['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label (Publisher)'),
      '#default_value' => '',
      '#parents' => ['album_label'],
    ];
    $form['album']['genres'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Genres'),
      '#description' => $this->t('Comma-separated list of genres.'),
      '#default_value' => '',
      '#parents' => ['album_genres'],
    ];
    $form['album']['release_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Release Date'),
      '#default_value' => '',
      '#date_date_format' => 'd-m-Y',

      '#parents' => ['album_release_date'],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $album = $this->getEntity();


    $album_type = $form_state->getValue('album_title');
    $album_image_url = $form_state->getValue('album_image_url');
    $album_label = $form_state->getValue('album_label');
    $album_genres = $form_state->getValue('album_genres');
    $album_release_date = $form_state->getValue('album_release_date');

    // Save the entity
    $status = parent::submitForm($form, $form_state);

    // Optional logging for debugging (you can remove or keep this)
    \Drupal::logger('music_search')->info('The album "@type" has been saved.', [
      '@type' => $album_type,
    ]);

    // Redirect after saving
    if ($album) {
      $form_state->setRedirectUrl($album->toUrl('canonical'));
    }
    else {
      $form_state->setRedirect('<front>');
    }
  }
}
