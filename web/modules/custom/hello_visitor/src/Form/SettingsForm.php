<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Hello Visitor settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * Name for module's configuration object.
   */
  const SETTINGS = 'hello_visitor.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return self::SETTINGS;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['default_event_duration'] = [
      '#type' => 'number',
      '#title' => $this->t('Default Event Duration (hours)'),
      '#description' => $this->t('Set the default duration for new events.'),
      '#default_value' => $this->config(self::SETTINGS)->get('default_event_duration') ?? 2,
      '#min' => 1,
    ];

    $form['max_attendees'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum Attendees per Event'),
      '#description' => $this->t('Set the maximum number of attendees allowed for an event.'),
      '#default_value' => $this->config(self::SETTINGS)->get('max_attendees') ?? 100,
      '#min' => 1,
    ];

    $form['event_categories'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Event Categories'),
      '#description' => $this->t('Enter available event categories, one per line.'),
      '#default_value' => $this->config(self::SETTINGS)->get('event_categories'),
    ];

    $form['enable_rsvp'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable RSVP System'),
      '#default_value' => $this->config(self::SETTINGS)->get('enable_rsvp'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(self::SETTINGS)
      ->set('default_event_duration', $form_state->getValue('default_event_duration'))
      ->set('max_attendees', $form_state->getValue('max_attendees'))
      ->set('event_categories', $form_state->getValue('event_categories'))
      ->set('enable_rsvp', $form_state->getValue('enable_rsvp'))
      ->save();

    $this->messenger()->addMessage($this->t('Hello Visitor configuration updated.'));
  }

}
