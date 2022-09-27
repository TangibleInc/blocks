<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base as ElementorBase;

class Repeater extends Base {

  /**
   * Elementor: @see https://developers.elementor.com/docs/controls/classes/control-repeater/
   */
  function register_control(string $builder, array $args): array {
    
    $label    = $args['label'] ?? '';
    $default  = $args['default'] ?? '';
    $controls = $args['controls'] ?? '';
    
    switch($builder) {
      case 'elementor':
        return [
          'type'     => 'repeater',
          'label'    => $label,
          'default'  => $default,
          'controls' => is_array($controls) ? $controls : [],
        ];
      case 'beaver-builder':
        return [];
      case 'gutenberg':
        return [];
    }
  }

  function format_value($value, string $builder, array $args, $settings) {

    if( $builder !== 'elementor' ) return $value;

    $controls = $args['controls'] ?? '';
    $controls = is_array($controls) ? $controls : [];
    $items    = is_array($value) ? $value : [];
        
    $response = array_map(function($item) use($controls, $builder, $settings) {
      
      $data = [];

      foreach( $controls as $control ) {

        $child_value = $item[ ElementorBase::$control_prefix . $control['name'] ];

        $child_value = self::$plugin->format_control_value(
          $child_value, $builder, $control, $item
        );

        $data[ $control['name'] ] = $child_value; 
      }
      
      return $data;

    }, $items);

    return $response;
  }

  function get_value($items, array $args, string $context) {

    $response = [];
    $controls = $args['controls'] ?? '';
        
    return array_map(function($item) use($controls, $context) {
      
      $data = [];

      foreach( $controls as $control ) {

        $child_control = self::$plugin->get_control( $control['type'] ?? '' );
        $child_name = $control['name'] ?? '';
        $child_data = $item[ $child_name ];
        
        if( ! $child_control ) continue;
        
        $data[ $child_name ] = $child_control->render( 
          $child_data['value'], 
          $child_data['attributes'], 
          $context 
        );
      }

      return $data;

    }, $items);
  }

}

$plugin->register_control('repeater', new Repeater);
