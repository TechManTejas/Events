<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a hello world block.
 */
#[Block(
  id: 'hello_visitor_hello_visitor',
  admin_label: new TranslatableMarkup('Hello Visitor'),
  category: new TranslatableMarkup('Custom')
)]
class HelloVisitorBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $currentUser;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Construct a HelloVisitorBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxyInterface $current_user, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $config = $this->getConfiguration();
    $anonymous_message = $config['custom_message'] ?? 'Hello Visitor!';

    if ($this->currentUser->isAuthenticated()) {
      $message = $this->t('Hello, @name! Welcome back.', ['@name' => $this->currentUser->getDisplayName()]);
    }
    else {
      $message = $anonymous_message;
    }

    $build['content'] = [
      '#markup' => $message,
    ];

    $build['content']['#cache'] = [
      'contexts' => ['user'],
      'tags' => $this->entityTypeManager->getStorage('user')->load($this->currentUser->id())->getCacheTags(),
    ];

    return $build;
  }

  /**
   * Block configuration form.
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['custom_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom message for anonymous users'),
      '#default_value' => $config['custom_message'] ?? 'Hello Visitor!',
      '#ajax' => [
        'callback' => '::ajaxPreviewMessage',
        'wrapper' => 'hello-visitor-preview',
      ],
    ];

    $form['preview'] = [
      '#type' => 'markup',
      '#markup' => '<div id="hello-visitor-preview">' . ($config['custom_message'] ?? 'Hello Visitor!') . '</div>',
    ];

    return $form;
  }

  /**
   * AJAX callback to update preview text.
   */
  public function ajaxPreviewMessage(array &$form, FormStateInterface $form_state) {
    return $form['preview'];
  }

  /**
   * Save block configuration.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('custom_message', $form_state->getValue('custom_message'));
  }

}
