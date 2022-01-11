<?php
/**
 * @file
 * Contains \Drupal\my_module\Form\WorkForm.
 */
namespace Drupal\my_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class MyForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => t('How are you?'),
      '#required' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser(); $uid = $current_user->id();
    $data = array(
        'uid' => $uid,
        'message' => $form_state->getValue('message'),
        'created' => time()
    );
    // insert data to database
    \Drupal::database()->insert('my_messages')->fields($data)->execute();

    //set message
    \Drupal::messenger()->addStatus('Thank you for sharing!');
  }

}
