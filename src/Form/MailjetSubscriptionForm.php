<?php

/**
 * @file
 * Contains configuration form for mailjet integration
 */
namespace Drupal\dropsolid_mj\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Mailjet\Client as MjClient;
use \Mailjet\Resources;

/**
 * Provides Mailjet subscription form.
 */
class MailjetSubscriptionForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropsolid_mj_subscribe';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dropsolid_mj.config');

    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('E-mail address'),
      '#required' => TRUE
    );

    $form['actions'] = array(
      '#type' => 'actions'
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $config->get('block_submit')
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // pick configuration and form values
    $config = $this->config('dropsolid_mj.config');
    $apikey_public = $config->get('apikey_public');
    $apikey_private = $config->get('apikey_private');
    $values = $form_state->getValues();

    // if all settings are in place try to subscribe user
    if($apikey_public && $apikey_private && $config->get('mj_contactlist_id')) {

      $mj = new MjClient($apikey_public, $apikey_private);
      $body = array(
        'Email' => $values['email'],
        'Action' => 'addnoforce'
      );
      $response = $mj->post(Resources::$ContactslistManagecontact, array('id' => $config->get('mj_contactlist_id') ,'body' => $body));

      if(!$response->success()){
        drupal_set_message($response->getReasonPhrase(),'warning');
      }
      else{
        drupal_set_message($this->t('You\'ve been successfuly subscribed to our mailing list!'));
      }
      
    }
  }
}