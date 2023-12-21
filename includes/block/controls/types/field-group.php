<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class FieldGroup extends Base {

  public string $type = 'field_group';

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($fields, array $args) : array {
    
    $sub_values = [];
    foreach( $fields as $name => $value ) {

      $control_args = $this->get_fields_args( $name, $args );
      $control = self::$plugin->get_control( $control_args['type'] ?? '' );

      if( $control === false ) continue;

      $sub_values[ $name ] = $control->get_sass_variable_definition( 
        $control->get_value( $value, $control_args, 'style' ),
        $control_args 
      );
    }

    return [
      'type'  => 'map',
      'value' => $sub_values
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

  function get_field_value($value, string $name, array $args, string $context) {

    $control = $this->get_field_control( $name, $args );

    if( empty($control) ) return [ 'value' => '' ];
    
    $field_args = $this->get_fields_args( $name, $args );
    $value = $control->get_value( $value, $field_args, $context );

    if( ! is_array($value) || ! isset($value['value']) ) {
      $value = [ 'value' => $value ];
    } 

    $value['type'] = $control->type;
    $value['name'] = $name;

    return $value;
  }

  function get_value($fields, array $args, string $context) {

    if( is_string($fields) ) $fields = json_decode($fields);
    if( $context === 'style' ) return (array) $fields;

    $formated_fields = [];

    if( empty($fields) ) return $formated_fields;

    // Maybe it makes more sense to return an associative array for this?
    foreach( $fields as $name => $value ) {
      $formated_fields[] = $this->get_field_value( $value, $name, $args, $context );
    }

    return $formated_fields;
  }

}
