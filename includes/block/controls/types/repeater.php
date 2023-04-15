<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Repeater extends Base {

  public string $type = 'repeater';

  function get_control_args(string $builder, array $args) : array {

    $args = parent::get_control_args($builder, $args);

    /**
     * TODO: Look how to get directly the right structure from the L&L template?
     */
    $args['data']['fields'] = $args['data']['controls'] ?? [];
    unset($args['data']['controls']);

    return $args;
  }

  /**
   * SCSS config (variable is a list of map)
   */

  function get_sass_type() : string {
    return 'list';
  }

  function get_sass_list_item_type() : string {
    return 'map';
  }

  function get_sass_map_types(array $args) : array {
    
    $controls = $args['controls'] ?? [];
    $types = [];

    foreach( $controls as $data ) {
      
      $control = self::$plugin->get_control( $data['type'] ?? '' );
      $name = $data['name'] ?? false;

      if( ! $control || ! $name ) continue; 
      
      $type = $control->get_sass_type();

      if( $type === 'map' ) {
        $type = $control->get_sass_map_types([]); // TODO: Pass $args for nested repeater
      }
      
      $types[ $name ] = $type; 
    }

    return $types;
  }

  /**
   * Render
   */

  function get_field_control(string $name, array $args) {
    
    $control = array_values(
      array_filter(
        $args['controls'] ?? [],
        function($control) use($name) {
          return ($control['name'] ?? '') === $name;
        }
      )
    )[0] ?? false;

    return ! empty($control)
      ? self::$plugin->get_control( $control['type'] ?? '' )
      : false
    ;
  }

  function get_item_value($value, string $name, array $args, string $context) {

    $control = $this->get_field_control( $name, $args );

    if( empty($control) ) return [ 'value' => '' ];
    
    return $control->get_value( $value, $args, $context );
  }

  function get_item_values(object $item, array $args, string $context) {

    $values = [];
    
    foreach( $item as $name => $value ) {

      if( $name === 'key' ) continue;
      
      $values[ $name ] = $this->get_item_value( $value, $name, $args, $context );
    }

    return $values;
  }

  function get_value($items, array $args, string $context) {

    if( is_string($items) ) $items = json_decode($items);
    if( ! is_array($items) ) return [];

    $item_values = [];

    foreach( $items as $item ) {
      $item_values []= $this->get_item_values( $item, $args, $context );
    }

    return $item_values;
  }

}
