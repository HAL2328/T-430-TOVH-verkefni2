<?php

namespace Drupal\music_search\Twig;

use Drupal\music_search\Controller\MusicSearchResultsController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SessionTwigExtension extends AbstractExtension {
  protected $sessionController;

  public function __construct(MusicSearchResultsController $sessionController) {
    $this->sessionController = $sessionController;
  }

  public function getFunctions() {
    return [
      new TwigFunction('set_session', [$this, 'setSession']),
    ];
  }

  public function setSession($key, $value) {
    $this->sessionController->setSessionValue($key, $value);
  }
}
