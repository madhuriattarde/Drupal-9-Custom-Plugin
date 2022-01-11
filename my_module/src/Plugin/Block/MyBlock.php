<?php

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a Custom Block.
 *
 * @Block(
 *   id = "my_module_block",
 *   admin_label = @Translation("My Custom Block"),
  * )
 */
class MyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // return [
    //   '#markup' => $this->t('This is Custom Block!'),
    // ];
    $form = \Drupal::formBuilder()->getForm('Drupal\my_module\Form\MyForm');
    //return $form;
    $build = array();
     $current_user ='HI';
     
    $account = \Drupal::currentUser();
    $uid = \Drupal::currentUser()->id();
    $user = \Drupal\user\Entity\User::load($uid);
    $current_user = ucfirst($user->get('name')->value);


    $build['#title'] = 'Hello ' . $current_user;
    $build['content'] = $form;
    return $build;
  }

}