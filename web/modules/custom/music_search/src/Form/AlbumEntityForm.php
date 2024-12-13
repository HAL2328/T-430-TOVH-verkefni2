<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\music_search\Entity\AlbumInterface;


class AlbumEntityForm extends ContentEntityForm {

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $session = \Drupal::request()->getSession();
    $selected_fields = $session->get('selected_fields', []);
    // Add input fields for Album
    $form['album'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Album Information'),
    ];
    $form['album']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Album Title'),
      '#default_value' => $selected_fields['name'] ?? '',
      '#required' => TRUE,
      '#parents' => ['album_title'],
    ];
    $form['album']['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label (Publisher)'),
      '#default_value' => $selected_fields['label'] ?? '',
      '#parents' => ['album_label'],
    ];
    $form['album']['release_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Release Date'),
      '#default_value' => $selected_fields['release_date'] ?? '',
      '#date_date_format' => 'd-m-Y',
      '#parents' => ['album_release_date'],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $album = $this->getEntity();

    /** @var AlbumInterface $album */
    $album->setTitle($form_state->getValue('name'));
    $album->setAlbumPublisher($form_state->getValue('label'));
    $album->setPublicationYear($form_state->getValue('release_date'));

    // Save the album entity.
    $album->save();
    \Drupal::logger('music_search')->info('The album "@title" has been saved.', [
      '@title' => $album->label(),
    ]);



    // Redirect after saving
    if ($album->id()) {
      $form_state->setRedirectUrl($album->toUrl('canonical'));
    } else {
      $form_state->setRedirect('<front>');
    }
  }
}
