<?php

$plugin->register_custom_control([
  'type' => 'select2',
])
  ->enqueue(function($script_name) use($plugin, $interface) {
    $interface->enqueue('select');
  })
  ->render(function($value, $field) {
    
    $is_multiple = isset($field['multiple']) && $field['multiple'] === 'true';

    $allowed_value = isset($field['options']) 
      ? array_keys($field['options'])
      : []
    ;

    if( !$is_multiple ) {
      return in_array($value, $allowed_value) 
        ? $value
        : ''
      ;
    }
    
    $values = explode(',',$value);
    if( empty($values) ) return '';

    $values = array_filter($values, function($value) use($allowed_value) {
      return in_array($value, $allowed_value); 
    });
    
    return implode(',', $values);
  });

  