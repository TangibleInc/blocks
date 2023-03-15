<?php

defined('ABSPATH') or die();

/**
 * Get the type of SASS variable according to the value/control type
 * 
 * Maybe this logic should be move inside the control classes
 */
$plugin->get_legacy_sass_variable_type = function($value, $control_type) use($plugin) {
  
  $is_dimension = in_array($control_type, ['dimension', 'dimensions']);
  if( $is_dimension && ! empty($value) ) return 'dimension'; 

  if( $plugin->is_valid_color($value) ) return 'color';
  if( $plugin->is_valid_gradient($value) ) return 'color';
  
  if( is_numeric($value) ) return 'number';

  return 'string';
};
