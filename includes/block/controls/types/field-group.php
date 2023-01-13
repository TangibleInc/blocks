<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class FieldGroup extends Base {

  public string $type = 'field_group';

  function get_control_args(string $builder, array $args): array {
    $args['fields'] = $args['controls']; 
    return parent::get_control_args($builder, $args);
  }

  function get_field_control(string $name, array $args) {}

  function get_field_value(string $name, array $args) {}

  function get_value($fields, array $args, string $context) {
    return $fields;
  }

}
