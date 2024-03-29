<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Select extends BaseList {

  public string $type = 'select';

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($value, array $args) : array {
    return [
      'type'  => 'string',
      'value' => is_array($value) 
        ? implode(',', array_map(function($item) { return $item['id']; }, $value )) // multiple => true
        : $value
    ];
  }

  function get_value($value, array $args, string $context) {

    $values = $this->has_multiple_values($args)
      ? (is_array($value) ? $value : (array) json_decode($value))
      : [ $value ];

    $values = $this->get_valid_values($values, $args);

    if( count($values) === 1 ) return $values[0];

    return array_map(
      function($item) { 
        return [ 
          'id'    => esc_html($item),
          'value' => esc_html($item) // Default value
        ];
      },
      $values
    );
  }

}
