<?php

namespace Drupal\px_web_meta\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'px_web_meta_field_type' field type.
 *
 * @FieldType(
 *   id = "px_web_meta_field_type",
 *   label = @Translation("PX Web meta"),
 *   description = @Translation("This is a PX Web meta"),
 *   default_widget = "px_web_meta_widget_type",
 *   default_formatter = "px_web_meta_formatter_type"
 * )
 */
class PxWebMetaFieldType extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    
    $properties['pxFileUrl'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Leinkja til PX-fyrispurning'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    $properties['lastUpdated'] = DataDefinition::create('any')
        ->setLabel(t('Computed date'))
        ->setDescription(t('The computed last updated DateTime object.'))
        ->setComputed(TRUE)
        ->setClass('\\Drupal\\datetime\\DateTimeComputed')
        ->setSetting('date source', 'value');

    $properties['nextUpdate'] = DataDefinition::create('any')
        ->setLabel(t('Computed date'))
        ->setDescription(t('The computed next update DateTime object.'))
        ->setComputed(TRUE)
        ->setClass('\\Drupal\\datetime\\DateTimeComputed')
        ->setSetting('date source', 'value');

    $properties['contact'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('contact'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'pxFileUrl' => [
          'type' => $field_definition->getSetting('is_ascii') === TRUE ? 'text' : 'text',
          'length' => 65000,
          'binary' => $field_definition->getSetting('case_sensitive'),
        ],
        'lastUpdated' => [
            'type' => 'varchar',
            'mysql_type' => 'datetime',
        ],
        'nextUpdate' => [
            'type' => 'varchar',
            'mysql_type' => 'datetime',
        ],
        'contact' => [
          'type' => $field_definition->getSetting('is_ascii') === TRUE ? 'text' : 'text',
          'length' => 65000,
          'binary' => $field_definition->getSetting('case_sensitive'),
        ]
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $pxFileUrl = $this->get('pxFileUrl')->getValue();
    $lastUpdated = $this->get('lastUpdated')->getValue();
    $nextUpdate = $this->get('nextUpdate')->getValue();
    $contact = $this->get('contact')->getValue();
    
    return empty($pxFileUrl) && empty($lastUpdated) && empty($nextUpdate) && empty($contact);
  }

}
