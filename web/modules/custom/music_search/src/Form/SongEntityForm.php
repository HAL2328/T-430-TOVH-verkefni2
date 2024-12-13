<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Messenger\MessengerInterface;

class SongEntityForm extends ContentEntityForm {

  /**
   * Messenger service.
   *
   * @var MessengerInterface
   */
  protected $messenger;

  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    // Get the Song entity
    $song = $this->getEntity();

    // Save the Song entity
    $status = parent::submitForm($form, $form_state);

    // Provide a message based on the save status
    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Song.', [
          '%label' => $song->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Song.', [
          '%label' => $song->label(),
        ]));
    }

    // Redirect after save
    $form_state->setRedirectUrl($song->toUrl('canonical'));
  }
}
