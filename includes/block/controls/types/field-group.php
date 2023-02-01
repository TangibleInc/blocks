<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class FieldGroup extends Base {

  public string $type = 'field_group';

  function get_control_args(string $builder, array $args): array {
    $args['fields'] = $args['controls']; 
    return parent::get_control_args($builder, $args);
  }

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

  function get_field_value($value, string $name, array $args, string $context) {

    $control = $this->get_field_control( $name, $args );

    if( empty($control) ) return [ 'value' => '' ];
    
    $value = $control->get_value( $value, $args, $context );

    if( ! is_array($value) ) $value  = [ 'value' => $value ];

    $value['type'] = $control->type;
    $value['name'] = $name;

    return $value;
  }

  function get_value($fields, array $args, string $context) {

    if( is_string($fields) ) $fields = json_decode($fields);

    $formated_fields = [];

    foreach( $fields as $name => $value ) {
      $formated_fields []= $this->get_field_value( $value, $name, $args, $context );
    }

    return $formated_fields;
  }

}
