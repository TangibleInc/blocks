<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Repeater extends Base {

  public string $type = 'repeater';

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($items, array $args) : array {
    return [
      'type'  => 'list',
      'value' => array_map(
        function($item) use($args) {
          foreach( $args['fields'] as $data ) {

            if( ! ( $name = $data['name'] ?? false ) ) continue;

            $value = $item[ $name ] ?? '';
            $control_args = $this->get_fields_args( $name, $args );

            if( ! ( $control = self::$plugin->get_control( $control_args['type'] ?? '' ) ) ) {
              continue;
            }

            if( ! $control->has_context('style') ) {
              continue;
            }

            $map[ $name ] = $control->get_sass_variable_definition(
              $control->get_value( $value, $control_args, 'style' ),
              $control_args
            );
          }

          return [
            'type'  => 'map',
            'value' => $map
          ];
        },
        $items,
      )
    ];
  }

  function get_fields_args(string $name, array $args) : array {
    return array_values(
      array_filter(
        $args['fields'] ?? [],
        function($control) use($name) {
          return ($control['name'] ?? '') === $name;
        }
      )
    )[0] ?? [];
  }

  function get_field_control(string $name, array $args) {
    $control_args = $this->get_fields_args($name, $args);
    return ! empty($control_args)
      ? self::$plugin->get_control( $control_args['type'] ?? '' )
      : false
    ;
  }

  function get_item_value($value, string $name, array $args, string $context) {

    $control = $this->get_field_control( $name, $args );

    if( empty($control) ) return [ 'value' => '' ];

    return $control->get_value(
      $value,
      $this->get_fields_args($name, $args),
      $context
    );
  }

  function get_item_values(array $item, array $args, string $context) {

    $values = [];

    foreach( $item as $name => $value ) {

      if( $name === 'key' ) continue;

      $values[ $name ] = $this->get_item_value( $value, $name, $args, $context );
    }

    return $values;
  }

  function get_value($items, array $args, string $context) {

    if( is_string($items) ) $items = json_decode( $items, true );
    if( ! is_array($items) ) return [];

    $item_values = [];
    foreach( $items as $item ) {
      $item_values []= $this->get_item_values( (array) $item, $args, $context );
    }

    return $item_values;
  }

}
