<?php


namespace Drupal\music_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a block to display the Entity Field Selector Form.
 *
 * @Block(
 *   id = "entity_field_selector_block",
 *   admin_label = @Translation("Entity Field Selector Form Block")
 * )
 */
class EntityFieldSelectorBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    // Render the form in the block.
    return \Drupal::formBuilder()->getForm('Drupal\music_search\Form\EntityFieldSelectorWithSourcesForm');
  }

}
