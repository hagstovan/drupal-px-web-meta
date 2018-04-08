<?php

namespace Drupal\px_web_meta\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\px_web_meta\Utilities;

use Drupal\node\Entity\Node;
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
      $lastUpdated = $item->lastUpdated;
      $nextUpdate = $item->nextUpdate;
      $contect = $item->contact;

      try  {
        //Load the data
        $utilities = new Utilities();
        $pxFile = $utilities->getPxFile($item->pxFileUrl);

        if($pxFile)  
        {        
          
          $lastUpdatedWrapper = $pxFile->keyword("LAST-UPDATED");
          if($lastUpdatedWrapper && count($lastUpdatedWrapper->values) > 0) {
            $lastUpdated = $lastUpdatedWrapper->values[0];
          }
          
          $nextUpdateWrapper = $pxFile->keyword("NEXT-UPDATE");
          if($nextUpdateWrapper) {
            $nextUpdate = $nextUpdateWrapper->values[0];
          }

          $contectWrapper = $pxFile->keyword("CONTACT"); 
          if($contectWrapper) {
            $contect = $contectWrapper->values[0];
          }

          //Update Database          
          //$entity = $item->getEntity();
          //echo $iten->field_name;
          //var_dump($entity);
          //$node = Node::load($entity->id());

          // echo "<br/>1<br/>";
          // var_dump( $node );
          // $item->lastUpdated = $lastUpdated;
          // $item->nextUpdate = $nextUpdate;
          // $item->contact = $contect;
          // $item->save();

          //$node = node_load($entity->id());
          //entity_metadata_wrapper($entity->getType(), $node);

          //entity_metadata_wrapper('node', $entity->id());

          // $entity->wrapper()
          // $node_wrapper = entity_metadata_wrapper('node', $node);
          // $node_wrapper->lastUpdated->set($lastUpdated);
          // $node_wrapper->nextUpdate->set($nextUpdate);
          // $node_wrapper->contact->set($contect);
          // $node_wrapper->save();
        }
      } catch (Exception $e) { 

        echo "<br/>";
        echo $e;
        

      }

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
        '#lastUpdated' => $lastUpdated,
        '#nextUpdate' => $nextUpdate,
        '#contact' => $contect
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
