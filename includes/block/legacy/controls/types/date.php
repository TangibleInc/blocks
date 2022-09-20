<?php

defined('ABSPATH') or die();

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/date-time-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#date-field
 */

$plugin->register_legacy_control('date', [
  'elementor'       => $plugin->get_elementor_control_type('DATE_TIME'),
  'beaver-builder'  => 'date',
  'gutenberg'       => 'string',
])
  ->elementor(function($field, $type) {
    return [
      'label'         => $field['label'],
      'picker_options'=> [ 'enableTime' => false ], // False because only date on beaver-builder
      'type'          => $type,
      'default'       => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->beaver_builder(function($field, $type){
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->gutenberg(function($field, $type){
    return [
      'type'    => $type,
      'default' => isset($fields['default']) ? $field['default'] : ''
    ];
  })
  ->legacy_render(function($value, $block) {

    $tdate = \tangible_date();
    $date = $tdate( $value );
    $format = isset($block['format']) ? $block['format'] : 'd/m/Y';

    return $date->format( $format );
  });
