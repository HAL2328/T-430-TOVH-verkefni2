<?php

namespace Drupal\music_search\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
class FieldSelectorController {

  /**
   * Handles form submission and saves the selected fields in session storage.
   */
  public function handleFormSubmission(Request $request): RedirectResponse
  {
    // Retrieve submitted data from the form
    $submitted_fields = $request->request->all('fields');
    \Drupal::logger('Chosen Fields')->notice('Fields Chosen: <pre>@submitted_fields</pre>', ['@submitted_fields' => print_r($submitted_fields, TRUE)]);

    // Save the selected fields into the session
    $session = \Drupal::request()->getSession();
    $session->set('selected_fields', $submitted_fields);

    \Drupal::messenger()->addMessage(t('Your field selections have been saved in session storage.'));

    // Redirect to the appropriate entity form based on the entity type
    $entity_type = $request->query->get('entity_type', 'album'); // Default to 'album'
    switch ($entity_type) {
      case 'artist':
        $redirect_url = Url::fromRoute('entity.artist.add_form')->toString();
        break;
      case 'song':
        $redirect_url = Url::fromRoute('entity.song.add_form')->toString();
        break;
      default:
        $redirect_url = Url::fromRoute('entity.album.add_form')->toString();
    }

    return new RedirectResponse($redirect_url);
  }

}
