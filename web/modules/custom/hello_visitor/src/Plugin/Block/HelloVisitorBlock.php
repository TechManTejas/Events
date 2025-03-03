<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a hello world block.
 */
#[Block(
  id: 'hello_visitor_hello_visitor',
  admin_label: new TranslatableMarkup('Hello Visitor'),
  category: new TranslatableMarkup('Custom')
)]
class HelloVisitorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('Hello, Visitor!'),
    ];
    return $build;
  }

}