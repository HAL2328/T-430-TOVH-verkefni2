<?php

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

class ArtistEntityForm extends ContentEntityForm {

  /**
   * Messenger service.
   *
   * @var MessengerInterface
   */
  protected $messenger;
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    // Get the Artist entity
    $artist = $this->getEntity();

    // Save the Artist entity
    $status = parent::submitForm($form, $form_state);

    // Display a message based on the save status
    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Artist.', [
          '%label' => $artist->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Artist.', [
          '%label' => $artist->label(),
        ]));
    }

    // Redirect to the Artist's page
    $form_state->setRedirectUrl($artist->toUrl('canonical'));
  }
}
