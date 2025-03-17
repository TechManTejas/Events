<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hello Visitor Entity Hook.
 */
class HelloVisitorEntityHooks {

  /**
   * Implements hook_entity_type_alter().
   */
  #[Hook('entity_type_alter')]
  public function entityTypeAlter(array &$entity_types) : void {
    // Add validation constraint to user entities.
    $entity_types['user']->addConstraint('HelloVisitorUserNameConstraint');
  }

}
