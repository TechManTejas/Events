<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Plugin\Validation\Constraint;

use Drupal\Core\Validation\Attribute\Constraint;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * Provides a UserNameConstraint constraint.
 *
 * @see https://www.drupal.org/node/2015723.
 */
#[Constraint(
  id: 'HelloVisitorUserNameConstraint',
  label: new TranslatableMarkup('User name cannot be hello_visitor', [], ['context' => 'Validation'])
)]
final class UserNameConstraint extends EqualTo {

  /**
   * Message to display for invalid username.
   *
   * @var string
   */
  public string $message = 'Invalid user name. Cannot use "hello_visitor" as the user name.';

  /**
   * The value to compare.
   *
   * @var string
   */
  public mixed $value = 'hello_visitor';

}
