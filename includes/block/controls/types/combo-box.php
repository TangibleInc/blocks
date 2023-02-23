<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ComboBox extends BaseList {

  public string $type = 'combo_box';

  function has_async_choices(array $args) : bool {
    return isset($args['is_async']) && $args['is_async'] === 'true';
  }
  
  /**
   * Field value is a json object when async mode is true
   */
  function get_async_values($value, array $args) : array {

    $is_multiple = $this->has_multiple_values($args);

    if( is_string($value) ) $value = json_decode($value);
    
    return ! $is_multiple
      ? [ esc_html($value->value ?? '') ]
      : array_map(
          function($item) {
            return esc_html($item->value ?? '');
          },
          (array) $value
        );
  }

  function get_value($value, array $args, string $context) {

    $is_async = $this->has_async_choices($args);
    
    $values = $is_async 
      ? $this->get_async_values($value, $args)
      : explode(',', $value);

    // We can't validate values in async mode because allowed values are not stored in $args     
    if( ! $is_async ) {
      $values = $this->get_valid_values($values, $args);
    } 

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
