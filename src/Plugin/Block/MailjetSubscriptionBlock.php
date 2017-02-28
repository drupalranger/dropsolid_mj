<?php

namespace Drupal\dropsolid_mj\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides Mailjet subscription block.
 *
 * @Block(
 *   id = "dropsolid_mj_subscription_block",
 *   admin_label = @Translation("Dropsolid Mailjet - subscription block"),
 * )
 */
class MailjetSubscriptionBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::configFactory()->get('dropsolid_mj.config')->getRawData();
    $build = array(
      '#theme' => 'dropsolid_mj_subscription_block',
      '#settings' => array(
        'title' => $config['block_title'],
        'description' => $config['block_description'],
      ),
      '#form' =>  \Drupal::formBuilder()->getForm('Drupal\dropsolid_mj\Form\MailjetSubscriptionForm')
    );
    return $build;
  }
}
