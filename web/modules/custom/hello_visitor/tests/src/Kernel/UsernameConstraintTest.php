<?php

namespace Drupal\Tests\hello_visitor\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\user\Entity\User;

/**
 * Tests the username validation constraint.
 *
 * @group hello_visitor
 * @coversClass \Drupal\hello_visitor\Plugin\Validation\Constraint\UserNameConstraint
 * @coversClass \Drupal\hello_visitor\Plugin\Validation\Constraint\UserNameConstraintValidator
 */
class UsernameConstraintTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['user', 'hello_visitor'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Install required schemas for the User module. This will create the
    // database tables required when creating a new user entity.
    $this->installEntitySchema('user');

    // You can also install configuration if necessary.
    // $this->installConfig(['user']);.
  }

  /**
   * Tests the username validation constraint.
   */
  public function testUsernameValidation() {
    // Create a user entity.
    $user = User::create();

    // Set the username to the restricted value.
    $user->setUsername('hello_visitor');

    // Validate the user entity.
    $violations = $user->validate();

    // Check that there is a violation for the username field.
    $this->assertCount(1, $violations->getByField('name'));
    $this->assertEquals('Invalid user name. Cannot use "hello_visitor" as the user name.', $violations->getByField('name')[0]->getMessage());
  }

}
