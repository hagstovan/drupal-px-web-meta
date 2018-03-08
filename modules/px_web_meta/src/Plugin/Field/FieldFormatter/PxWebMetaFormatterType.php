<?php

namespace Drupal\px_web_meta\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\px_web_graph\Utilities;

/**
 * Plugin implementation of the 'px_web_meta_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "px_web_meta_formatter_type",
 *   label = @Translation("PX Web Meta formatter type"),
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
      $lastUpdated = null;
      $nextUpdate = null;
      $contect = null;

      try  {
        //Load the data
        $utilities = new Utilities();
        $pxFile = $utilities->getPxFile($item->pxFileUrl);

        $lastUpdatedWrapper = $pxFile->keyword("LAST-UPDATED");
        if($lastUpdatedWrapper && count($lastUpdatedWrapper->values) > 0) {
          $lastUpdated = $lastUpdatedWrapper->values[0];
        }

        $nextUpdateWrapper = $pxFile->keyword("NEXT-UPDATE")->values[0];
        $nextUpdate = $nextUpdateWrapper;

        $contectWrapper = $pxFile->keyword("CONTACT")->values[0];
        $contect = $contectWrapper;
      } catch (Exception $e) { }

      //Use stored values if not found
      if(!$lastUpdated)
        $lastUpdated = $item->lastUpdated;
      if(!$nextUpdate)
        $nextUpdate = $item->nextUpdate;
      if(!$contect)
        $contect = $item->contact;

      $markup = "PxWebMetaFormatterType";
      $markup .= "<br/>pxFileUrl: " . $item->pxFileUrl;
      $markup .= "<br/>lastUpdated: " . $lastUpdated;
      $markup .= "<br/>nextUpdate: " . $nextUpdate;
      $markup .= "<br/>contact: " . $contect;

      $id = PxWebMetaFormatterType::getNextId();

      $storageName = "pxMetaPlaceholder".$id;
      $elements[$delta] = array(
        '#type' => 'markup',
        '#markup' => $markup,
        '#lastUpdated' => $item->lastUpdated,
        '#nextUpdate' => $item->nextUpdate,
        '#contact' => $item->contact
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
