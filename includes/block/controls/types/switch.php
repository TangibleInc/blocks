<?php

defined('ABSPATH') or die();

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/switcher-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#select-field
 * Gutenberg: @see https://developer.wordpress.org/block-editor/reference-guides/components/toggle-control/
 */

$plugin->register_control('switch', [
  'elementor'       => $plugin->get_elementor_control_type('SWITCHER'),
  'beaver-builder'  => 'select',
  'gutenberg'       => 'string',
])
  ->default(function($value, $builder, $field) {

    if( isset($field['default']) ) return $value;

    return isset($field['value_on'])
      ? $field['value_on']
      : $value
    ;
  })
  ->elementor(function($field, $type) {
    return [
      'label'       => $field['label'],
      'type'        => $type,
      'return_value'=> isset($field['value_on']) ? $field['value_on'] : 'on',
      'return_off'  => isset($field['value_off']) ? $field['value_off'] : 'off', // Not used by elementor, but needed to pass info in js 
      'label_on'    => isset($field['label_on']) ? $field['label_on'] : '',
      'label_off'   => isset($field['label_off']) ? $field['label_off'] : '',
      'default'     => isset($field['default']) ? $field['default'] : 'on'
    ];
  })
  ->beaver_builder(function($field, $type) {
    // This is until Beaver develops a proper toggle switch
    $value_on = isset($field['value_on']) ? $field['value_on'] : 'on';
    $value_off = isset($field['value_off']) ? $field['value_off'] : 'off';
    $label_on = isset($field['label_on']) ? $field['label_on'] : __( 'On', 'tangible-blocks' );
    $label_off = isset($field['label_off']) ? $field['label_off'] : __( 'Off', 'tangible-blocks' );
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'options' => [
        $value_on   => $label_on,
        $value_off  => $label_off
      ],
      'multi-select'=> false,
      'default'     => isset($field['default']) ? $field['default'] : 'on'
    ];
  })
  ->gutenberg(function($field, $type) {
    return [
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : 'on'
    ];
  })
  ->filter_value(function($value, $builder, $field, $settings) {

    if ( $builder === 'elementor' && empty($value) ){
      return !empty( $field['value_off'] ) ? $field['value_off'] : 'off';
    }
    return $value;
  })
  ->render(function($value, $field) {
    
    $alowed_values = ['on', 'off'];
    
    if( isset($field['value_on']) ) $alowed_values []= $field['value_on'];
    if( isset($field['value_off']) ) $alowed_values []= $field['value_off'];

    return in_array($value, $alowed_values) ? $value : '';
  });
