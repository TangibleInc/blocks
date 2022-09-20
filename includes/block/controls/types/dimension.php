<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Dimension extends Base {

  /**
   * Register control
   * 
   * Elementor:     @see https://developers.elementor.com/elementor-controls/dimensions-control/
   * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#dimension-field
   * Gutenberg :    @see https://developer.wordpress.org/block-editor/reference-guides/components/box-control/
   */

  function register_control(string $builder, array $args): array {

    $units = $args['units'] ?? false;
    $units = $units ? explode(',', str_replace(' ', '', $units)) : ['px'];
    
    $default   = $this->get_default_value( $builder, $args );
    $is_linked = $this->are_values_linked( $args );
    
    switch($builder) {

      case 'elementor':

        $control = [
          'type'        => $this->get_elementor_control_type('DIMENSIONS'),
          'label'       => $args['label'] ?? '',
          'size_units'  => $units,
          'default'     => $default,
        ];

        if( ! $is_linked ) return $control;

        $control['allowed_dimensions'] = ['top']; // We only want one value when linked
        $control['description'] = 'Left, right and bottom values will be the same than the top value';
        
        return $control;

      case 'beaver-builder':

        $default_key = $is_linked ? 'default' : 'placeholder';

        return [
          'type'         => $is_linked ? 'unit' : 'dimension',
          'label'        => $args['label'] ?? '',
          'units'        => $units,
          'default_unit' => $args['default_unit'] ?? '',
          $default_key   => $default
        ];

      case 'gutenberg':
        return [
          'type'    => 'object',
          'default' => $default
        ];
      
      default: return [];
    }
  }

  function get_default_value(string $builder, array $args) {

    $default = $args['default'] ?? false;

    if( $default === false ) {
      $default = $builder === 'gutenberg' 
        ? [ '', '', '', '' ]
        : [ '0', '0', '0', '0']
      ;
    } else {
      $default = explode( ',', str_replace(' ', '', $default) );
    }

    switch($builder) {
      
      case 'elementor':
        return [
          'top'       => $default[0],
          'right'     => $default[1] ?? $default[0],
          'bottom'    => $default[2] ?? $default[0],
          'left'      => $default[3] ?? $default[0],
          'unit'      => $args['default_unit'] ?? '',
          'isLinked'  => $this->are_values_linked( $args ),
        ];

      case 'beaver-builder':
        if( $this->are_values_linked( $args ) ) return $default[0];
        return [
          'top'       => $default[0],
          'right'     => $default[1] ?? $default[0],
          'bottom'    => $default[2] ?? $default[0],
          'left'      => $default[3] ?? $default[0],
        ];
      
      case 'gutenberg':
        return [
          'top'   => $default[0] ?? '0',
          'right' => $default[1] ?? $default[0],
          'bottom'=> $default[2] ?? $default[0],
          'left'  => $default[3] ?? $default[0],
          'unit'  => $args['default_unit'] ?? 'px',
        ];

      default: return [];
    }
  } 

  /**
   * Convert data we get from builder in similar array for get_value()
   */
  function format_value($value, string $builder, array $args, $settings) {

    $is_linked = $this->are_values_linked( $args );

    $default = $args['default'] ?? '';
    $default = explode(',', str_replace(' ', '', $default));

    if( $builder === 'beaver-builder' ) {
      $value = [
        'top'   => $is_linked ? $value : ($settings->{ $args['name'] . '_top' } ?? ''), 
        'right' => $is_linked ? $value : ($settings->{ $args['name'] . '_right' } ?? ''), 
        'bottom'=> $is_linked ? $value : ($settings->{ $args['name'] . '_bottom' } ?? ''), 
        'left'  => $is_linked ? $value : ($settings->{ $args['name'] . '_left' } ?? ''), 
        'unit'  => $settings->{ $args['name'] . '_unit' } ?? false
      ];
    }
    else if( $is_linked ) {
      $value['left']   = $value['top']; 
      $value['right']  = $value['top']; 
      $value['bottom'] = $value['top']; 
    }
      
    return [
      'top'   => $this->format_numeric_value( $value['top'], $default[0] ?? ''  ),
      'right' => $this->format_numeric_value( $value['right'], $default[1] ?? '' ),
      'bottom'=> $this->format_numeric_value( $value['bottom'], $default[2] ?? '' ),
      'left'  => $this->format_numeric_value( $value['left'], $default[3] ?? '' ),
      'unit'  => $this->format_unit( $value['unit'], $args )
    ];
  }

  function get_value($dimensions, array $args, string $context) {
    
    $dimensions['value'] = implode( ' ', [
      intval($dimensions['top'])    . $dimensions['unit'],
      intval($dimensions['right'])  . $dimensions['unit'],
      intval($dimensions['bottom']) . $dimensions['unit'],
      intval($dimensions['left'])   . $dimensions['unit'],
    ]);

    return $dimensions;
  }

  /**
   * Helpers 
   */

  function format_unit($unit, $args) {
    return in_array( $unit, $this->get_allowed_units($args) )
      ? $unit
      : 'px'
    ;
  }

  function get_allowed_units($args) {

    $allowed_units = $args['units'] ?? 'px'; 
    $allowed_units = explode( ',', $allowed_units ); 

    $default_unit = $args['default_unit'] ?? false;

    if( $default_unit && ! in_array($default_unit, $allowed_units) ) {
      $allowed_units []= $default_unit;
    }

    return $allowed_units;
  }

  function format_numeric_value($value, $default) {
    
    if( is_numeric($value) ) return (int) $value;
    
    return is_numeric($default) ? $default : 0;
  }

  function are_values_linked(array $args): bool {
    return isset($args['multiple_values']) && $args['multiple_values'] === 'false';
  }

}

$plugin->register_control('dimension', new Dimension);
