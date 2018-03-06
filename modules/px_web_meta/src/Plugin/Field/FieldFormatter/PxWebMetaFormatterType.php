<?php

namespace Drupal\px_web_meta\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'px_web_meta_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "px_web_meta_formatter_type",
 *   label = @Translation("PX Web Graph (Highcharts) formatter type"),
 *   field_types = {
 *     "px_web_meta_field_type"
 *   }
 * )
 */
class PxWebMetaFormatterType extends FormatterBase {

  public static $currentId;

  public static function getNextId() {
    PxWebMetaFormatterType::$currentId += 1;
      return PxWebMetaFormatterType::$currentId;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $markup = "PxWebMetaFormatterType";
      $markup .= "<br/>pxFileUrl: " . $item->pxFileUrl;
      $markup .= "<br/>lastUpdated: " . $item->lastUpdated;
      $markup .= "<br/>nextUpdate: " . $item->nextUpdate;
      $markup .= "<br/>contact: " . $item->contact;

      $id = PxWebMetaFormatterType::getNextId();

      $storageName = "pxMetaPlaceholder".$id;
      $elements[$delta] = array(
        '#type' => 'markup',
        '#markup' => $markup
      );
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
