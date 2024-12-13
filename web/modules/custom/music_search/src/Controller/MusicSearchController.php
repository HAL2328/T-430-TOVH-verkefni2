<?php

namespace Drupal\music_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;

class MusicSearchController extends ControllerBase {

  /**
   * @var FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructor.
   *
   * @param FormBuilderInterface $formBuilder
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   *   The form builder service.
   */
  public function __construct(FormBuilderInterface $formBuilder) {
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Render the music search form page.
   *
   * @return array
   *   A render array containing the form and additional markup.
   */
  public function musicSearchPage(): array {
    $form = $this->formBuilder->getForm('Drupal\music_search\Form\MusicSearchForm');

    return [
      'form' => $form,
    ];
  }
}
