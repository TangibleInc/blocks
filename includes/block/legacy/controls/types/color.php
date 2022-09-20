<?php

defined('ABSPATH') or die();

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/color-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#color-field
 */

$plugin->register_legacy_control('color', [
  'elementor'       => $plugin->get_elementor_control_type('COLOR'),
  'beaver-builder'  => 'color',
  'gutenberg'       => 'string',
])
  ->elementor(function($field, $type) {
    
    $show_alpha = !isset($field['alpha']) || ($field['alpha'] !== false && $field['alpha'] !== 'false');

    return [
      'label'   => $field['label'],
      'type'    => $type,
      'alpha'   => $show_alpha,
      'default' => isset($field['default']) ? $field['default'] : '',
    ];
  })
  ->beaver_builder(function($field, $type) {

    $show_alpha = !isset($field['alpha']) || ($field['alpha'] !== false && $field['alpha'] !== 'false');

    return [
      'label'     => $field['label'],
      'type'      => $type,
      'default'   => isset($field['default']) ? $field['default'] : '',
      'show_alpha'=> $show_alpha
    ];
  })
  ->gutenberg(function($field, $type) {
    return [
      'type'    => $type,
      'default' => isset($fields['default']) ? $field['default'] : ''
    ];
  })
  ->default(function($value, $builder) {

    if($builder !== 'beaver-builder') return $value;

    /**
     * HEX without # for default color in beaver-builder
     */

    return strpos($value, '#') !== false 
      ? str_replace('#', '', $value)
      : $value
    ;
  })
  ->filter_value(function($value, $builder, $data, $settings) use($plugin) {
  
    switch($builder) {
      
      case 'elementor': 

        // Handle global values, @see utils/elementor.php
        $value = $plugin->get_elementor_control_value( $value, $data['name'], $settings );
                
        // Elementor uses an hex value with 8 digit to handle opacity, but it's not working well with $html->sass()
        $has_sharp = strpos($value, '#') !== false;

        return $has_sharp && strlen($value) === 9
          ? $plugin->hex_to_rgba( $value )
          : $value
        ;

      case 'beaver-builder':
    
        // BeaverBuilder is not returning # before an hex value, but can also return rgba values
        return !empty($value) && ctype_xdigit($value)
          ? '#' . $value
          : $value
      ;
    }

    return $value;
  })
  ->legacy_render(function($value, $block) use($plugin) {

    $value = empty($value) && isset($block['default']) 
      ? $block['default']
      : $value
    ;

    $color_format = $plugin->get_color_format($value);

    if( empty($value) || $color_format === false ) return $value;
    
    $show_alpha = !isset($block['alpha']) || ($block['alpha'] !== false && $block['alpha'] !== 'false');

    if( !$show_alpha && in_array($color_format, ['rgb', 'rgba'])) {
      return $plugin->rgba_to_hex($value);
    }

    if( $show_alpha && $color_format === 'hex' ) {
      return $plugin->hex_to_rgba($value);
    }

    return $value;
  });
