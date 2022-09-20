<?php

defined('ABSPATH') or die();

$plugin->register_legacy_control('number', [
  'elementor'       => $plugin->get_elementor_control_type('NUMBER'),
  'beaver-builder'  => 'unit',
  'gutenberg'       => 'integer',
])
  ->elementor(function($field, $type) {
    $default = isset( $field['default'] )
      ? (int) $field['default']
      : 0
    ;
    $min = isset( $field['min'] )
      ? (int) $field['min']
      : ''
    ;
    $max = isset( $field['max'] )
      ? (int) $field['max']
      : ''
    ;    
    $min && $default < $min ? $default = $min : '';
    $max && $default > $max ? $default = $max : '';
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => $default,
      'min'     => $min,
      'max'     => $max
    ];
  })
  ->beaver_builder(function($field, $type){
    $default = isset( $field['default'] )
      ? (int) $field['default']
      : 0
    ;
    $min = isset( $field['min'] )
      ? (int) $field['min']
      : ''
    ;
    $max = isset( $field['max'] )
      ? (int) $field['max']
      : ''
    ;
    $min && $default < $min ? $default = $min : '';
    $max && $default > $max ? $default = $max : '';
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => $default,
      'slider' => array(
        'min'   => $min,
        'max'   => $max,
      ),
    ];
  })
  ->gutenberg(function($field, $type){
    $default = isset( $field['default'] )
      ? (int) $field['default']
      : 0
    ;
    $min = isset( $field['min'] )
      ? (int) $field['min']
      : ''
    ;
    $max = isset( $field['max'] )
      ? (int) $field['max']
      : ''
    ;
    is_integer($min) && $default < $min ? $default = $min : '';
    is_integer($max) && $default > $max ? $default = $max : '';
    return [
      'type'    => $type,
      'default' => $default,
    ];
  })
  ->filter_value(function($value, $builder, $field, $settings) {
    if( $builder !== 'beaver-builder' ) return $value;

    if(isset($field['max']) && (int) $value > (int) $field['max']) return (int) $field['max'];

    if(isset($field['min']) && (int) $value < (int) $field['min']) return (int) $field['min'];
    
    return (int) $value;
  })
  ->legacy_render(function($value, $block) {
    return (int) $value;
  });
