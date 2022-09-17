<?php

defined('ABSPATH') or die();

/**
 * Helper to return formated values in all builder (include legacy value)
 */
$plugin->format_control_value = function($value, $builder, $args, $settings) use($plugin) {

  $control = $plugin->get_control( $args['type'] );
  $legacy_control = $plugin->get_legacy_control( $args['type'] );

  if( ! $control ) return false;

  $formated_value = $control->format_data( $value, $builder, $args, $settings );

  if( $legacy_control ) {
    $formated_value['legacy'] = $legacy_control->format_legacy_data( 
      $value, $builder, $args, $settings 
    );
  }

  return $formated_value; 
};
