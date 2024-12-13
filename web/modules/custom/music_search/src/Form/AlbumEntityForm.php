<?php

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

class AlbumEntityForm extends ContentEntityForm {
  /**
   * Messenger service.
   *
   * @var MessengerInterface
   */
  protected $messenger;

  // The form submission handler
  /**
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    // Get the entity being saved (in this case, an Album entity)
    $album = $this->getEntity();

    // Save the entity
    $status = parent::submitForm($form, $form_state);

    // Provide a success message based on the save status
    switch ($status) {
      case SAVED_NEW:
        // Message for newly created Album
        $this->messenger()->addMessage($this->t('Created the %label Album.', [
          '%label' => $album->label(),
        ]));
        break;

      default:
        // Message for updated Album
        $this->messenger()->addMessage($this->t('Saved the %label Album.', [
          '%label' => $album->label(),
        ]));
    }

    // Redirect after saving
    $form_state->setRedirectUrl($album->toUrl('canonical'));
  }
}
