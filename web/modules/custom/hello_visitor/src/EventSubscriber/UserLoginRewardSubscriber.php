<?php

namespace Drupal\hello_visitor\EventSubscriber;

use Drupal\hello_visitor\Event\UserLoggedInEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Logs a message when users log in.
 */
class UserLoginRewardSubscriber implements EventSubscriberInterface {

  protected LoggerChannelInterface $logger;
  protected AccountProxyInterface $currentUser;

  public function __construct(LoggerChannelFactoryInterface $logger_factory, AccountProxyInterface $currentUser) {
    $this->logger = $logger_factory->get('hello_visitor'); // Get the correct logger
    $this->currentUser = $currentUser;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory'),
      $container->get('current_user')
    );
  }

  public static function getSubscribedEvents() {
    return [
      UserLoggedInEvent::EVENT_NAME => 'logUserLogin',
    ];
  }

  public function logUserLogin(UserLoggedInEvent $event) {
    $account = $event->getAccount();
    $this->logger->notice('User @name has logged in. and have received 10 coins as reward', [
      '@name' => $account->getDisplayName(),
    ]);
  }
}
