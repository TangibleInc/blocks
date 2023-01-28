<?php

defined('ABSPATH') or die();

/**
 * Helper to return formated values in all builder
 */
$plugin->format_control_value = function(
  $value,
  $builder, 
  $args,
  $settings,
  $block_id
) use($plugin) {

  $use_legacy_controls = $plugin->block_use_new_controls( $block_id ) !== true;
  
  $control = $use_legacy_controls
    ? $plugin->get_legacy_control( $args['type'] )
    : $plugin->get_control( $args['type'] );

  if( ! $control ) return false;

  $formated_value = $control->format_data( $value, $builder, $args, $settings );

  if( ! $use_legacy_controls ) return $formated_value;

  $formated_value['legacy'] = $control->format_legacy_data( 
    $value, $builder, $args, $settings 
  );

  return $formated_value;
};
