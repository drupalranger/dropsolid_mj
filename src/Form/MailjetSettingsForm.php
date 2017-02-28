<?php

/**
 * @file
 * Contains configuration form for mailjet integration
 */
namespace Drupal\dropsolid_mj\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use \Mailjet\Client as MjClient;
use \Mailjet\Resources;

/**
 * Configure mailjet integration.
 */
class MailjetSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropsolid_mj_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dropsolid_mj.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dropsolid_mj.config');
    $apikey_public = $config->get('apikey_public');
    $apikey_private = $config->get('apikey_private');
    $form['api_settings'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('API settings')
    );
    $form['api_settings']['apikey_public'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('API Public Key'),
      '#default_value' => $apikey_public
    );

    $form['api_settings']['apikey_private'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('API Secret Key'),
      '#default_value' => $apikey_private
    );

    if($apikey_public && $apikey_private){
      $mj = new MjClient($apikey_public, $apikey_private);

      $response = $mj->get(Resources::$Contactslist);
      if($response->success()){
        $form['api_settings']['mj_contactlist_id'] = array(
          '#type' => 'select',
          '#title' => $this->t('Mailjet contact list'),
          '#description' => $this->t('Choose contact list for subscriptions'),
          '#options' => array(),
          '#required' => TRUE,
          '#default_value' => $config->get('mj_contactlist_id')
        );
        $lists = $response->getData();
        foreach($lists as $list){
          $form['api_settings']['mj_contactlist_id']['#options'][$list['ID']] = $list['Name'];
        }
      }
      else{
        drupal_set_message($this->t('Mailjet contact list is not configured, please provide API keys and submit form to load you contact lists from Mailjet account'),'warning');
      }
    }

    $form['block_settings'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Subscription block settings'),
    );

    $form['block_settings']['block_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Block title'),
      '#default_value' => $config->get('block_title')
    );

    $form['block_settings']['block_description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Block description'),
      '#default_value' => $config->get('block_description')
    );

    $form['block_settings']['block_submit'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Submit button text'),
      '#default_value' => $config->get('block_submit')
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $config = \Drupal::configFactory()->getEditable('dropsolid_mj.config')
      ->set('apikey_public', $values['apikey_public'])
      ->set('apikey_private', $values['apikey_private'])
      ->set('block_title', $values['block_title'])
      ->set('block_description', $values['block_description'])
      ->set('block_submit', $values['block_submit']);

    if(isset($values['mj_contactlist_id'])){
     $config->set('mj_contactlist_id', $values['mj_contactlist_id']);
    }

    $config->save();
    parent::submitForm($form, $form_state);
  }
}