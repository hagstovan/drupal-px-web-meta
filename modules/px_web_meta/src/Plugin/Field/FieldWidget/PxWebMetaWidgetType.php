<?php

namespace Drupal\px_web_meta\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Plugin implementation of the 'px_web_meta_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "px_web_meta_widget_type",
 *   label = @Translation("PX Web Graph (Highcharts) widget type"),
 *   field_types = {
 *     "px_web_meta_field_type"
 *   },
 *   multiple_values = TRUE
 * )
 */
class PxWebMetaWidgetType extends WidgetBase {

    private $id = 0;
    public static $currentId;

    public static function getNextId() {
      PxWebMetaWidgetType::$currentId += 1;
        return PxWebMetaWidgetType::$currentId;
    }
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // If cardinality is 1, ensure a label is output for the field by wrapping
    // it in a details element.
    if($this->id == 0){
        $this->id  = PxWebMetaWidgetType::getNextId();
    }

    $wrapperClass = 'px-web-meta-'.$this->id;
    $element += array(
      '#attributes' => ['class' => [$wrapperClass]],
      '#attached' => [
        'drupalSettings' => [
          'wrapperClass' => $wrapperClass
        ],
        'library' => [
            'px_web_meta/px_web_meta_form_actions',
            'px_web_meta/px.min',
            'px_web_meta/underscore-min'
        ],
      ],
    );

    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += array(
        '#type' => 'fieldset',
      );
    }
    $element['pxFileUrl'] = [
      '#type' => 'textfield',
      '#title' => 'Fyrispurningur úr hagtalsgrunni (PX-fíluslag)',
      '#suffix' => '<div class="load-px-file-url-button"></div>',
      '#attributes' => ['class' => ['edit-field-px-file-url']],
      '#default_value' => isset($items[$delta]->pxFileUrl) ? $items[$delta]->pxFileUrl : "",
    ];

    $element['lastUpdated'] = [
        '#type' => 'date',
        '#title' => 'Seinast dagført',
        '#prefix' => '<div class="edit-field-last-updated-outer">',
        '#suffix' => '</div>',
        '#default_value' => isset($items[$delta]->lastUpdated) ? (new DrupalDateTime($items[$delta]->lastUpdated))->format("Y-m-d") : ''
    ];

    $element['nextUpdate'] = [
      '#type' => 'date',
        '#title' => 'Næsta dagføring',
        '#prefix' => '<div class="edit-field-last-next-update-outer">',
        '#suffix' => '</div>',
        '#default_value' => isset($items[$delta]->nextUpdate) ? (new DrupalDateTime($items[$delta]->nextUpdate))->format("Y-m-d") : ''
    ];

    $element['contact'] = [
      '#type' => 'textfield',
      '#title' => 'Ábyrgdari',
      '#attributes' => ['class' => ['edit-field-contact']],
      '#default_value' => isset($items[$delta]->contact) ? $items[$delta]->contact : "",
    ];

    return $element;
  }
}
