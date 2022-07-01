<?php

defined('ABSPATH') or die();

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/select-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#select-field
 */

$plugin->register_control('select', [
  'elementor'       => $plugin->get_elementor_control_type('SELECT2'),
  'beaver-builder'  => 'select',
  'gutenberg'       => 'string',
])
  ->elementor(function($field, $type) {

    $options = isset($field['options']) ? $field['options'] : [];
    $is_multiple = isset($field['multiple']) ? $field['multiple'] : false;

    $default = isset($field['default']) 
      ? $field['default'] 
      : (count($options) > 0 && !$is_multiple ? array_keys($options)[0] : '')
    ;

    return [
      'label'   => $field['label'],
      'type'    => $type,
      'options' => $options,
      'multiple'=> $is_multiple,
      'default' => $default
    ];
  })
  ->beaver_builder(function($field, $type) {

    $options = isset($field['options']) ? $field['options'] : [];
    $is_multiple = isset($field['multiple']) ? $field['multiple'] : false;

    $default = isset($field['default']) 
      ? $field['default'] 
      : (count($options) > 0 && !$is_multiple ? array_keys($options)[0] : '')
    ;

    return [
      'label'       => $field['label'],
      'type'        => $type,
      'options'     => $options, 
      'multi-select'=> $is_multiple,
      'default'     => $default
    ];
  })
  ->gutenberg(function($field, $type) {

    $options = isset($field['options']) ? $field['options'] : [];
    $is_multiple = isset($field['multiple']) ? $field['multiple'] : false;

    $default = isset($field['default']) 
      ? $field['default'] 
      : (count($options) > 0 && !$is_multiple ? array_keys($options)[0] : '')
    ;

    return [
      'type'    => !empty($field['multiple']) && $field['multiple'] === 'true' ? 'array' : $type,
      'multiple'=> isset($field['multiple']) ? $field['multiple'] : false,
      'default' => $default
    ];
  })
  ->render(function($value, $block) {

    $allowed_values = isset($block['options']) ? array_keys($block['options']) : [];

    if( isset($block['default']) ) $allowed_values []= $block['default'];

    if( !is_array($value) ) return in_array($value, $allowed_values) ? $value : '';

    $valid_data = count( array_intersect($value, $allowed_values) ) === count($value);
    return $valid_data ? implode(',', $value) : '';    
  });
  
