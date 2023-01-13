<?php

defined('ABSPATH') or die();

/**
 * Helper to return formated values in all builder (include legacy value)
 */
$plugin->format_control_value = function($value, $builder, $args, $settings) use($plugin) {

  $control = $plugin->get_control( $args['type'] );
  
  if( ! $control ) return false;

  return $control->format_data( $value, $builder, $args, $settings );
};
