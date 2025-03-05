<?php

namespace Drupal\hello_visitor\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Drupal\user\UserInterface;

/**
 * Event fired when a user logs in.
 */
class UserLoggedInEvent extends Event {
  const EVENT_NAME = 'hello_visitor.user_logged_in';

  protected UserInterface $account;

  public function __construct(UserInterface $account) {
    $this->account = $account;
  }

  public function getAccount(): UserInterface {
    return $this->account;
  }
}
