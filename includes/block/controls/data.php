<?php

/**
 * Controls data
 */

$plugin->get_block_controls = function( $settings ) use ($plugin, $template_system) {

  // Backward compatibility, just in case - Migrating from post/content ID to universal ID
  if (is_numeric($settings)) $settings = [ 'content_id' => $settings ];

  $post = $plugin->get_block_post_from_settings( $settings );

  $block = $template_system->get_template_fields( $post );

  $block_data = $plugin->get_block_data( $block );
  $fields = [];

  if( !isset($block_data['tabs']) ) return [];

  foreach( $block_data['tabs'] as $tab ) {
    foreach( $tab['sections'] as $section ) {
      foreach( $section['fields'] as $field ) $fields []= $field;
    }
  }

  return $fields;
};

/**
 * Convert saved control to data expected by given builder
 */
$plugin->get_builder_args = function($field_data, $builder) use($plugin) {

  if( !in_array($builder, ['elementor', 'beaver-builder', 'gutenberg']) ) return false;

  $type = !empty($field_data['type']) ? $field_data['type'] : false;

  if( empty($type) || empty($plugin->controls[ $type ])) return false;

  $control = $plugin->get_control( $type );

  return $control !== false
    ? $control->get_field_data( $field_data, $builder )
    : []
  ;
};

$plugin->get_control = function($name) use($plugin) {
  return is_string($name) && isset($plugin->controls[ $name ])
    ? $plugin->controls[ $name ]
    : false
  ;
};

/**
 * Safely get Elementor control type for field registration
 * @see /fields/*.php
 */

$plugin->has_elementor_controls_manager = class_exists('Elementor\\Controls_Manager');
$plugin->elementor_group_control_prefix = 'group_control_';

$plugin->get_elementor_group_control_type = function($type) use ($plugin) {
  return $plugin->has_elementor_controls_manager
    ? $plugin->elementor_group_control_prefix . call_user_func( '\Elementor\Group_Control_' . $type . '::get_type' )
    : $type // No need for valid value, since Elementor not active
    ;
};

$plugin->is_elementor_group_control = function($name) use($plugin) {
  return strpos( $name, $plugin->elementor_group_control_prefix ) === 0;
};

$plugin->get_elementor_control_type = function($type) use ($plugin) {
  return $plugin->has_elementor_controls_manager
    ? constant( 'Elementor\\Controls_Manager::' . $type )
    : $type // No need for valid value, since Elementor not active
    ;
};
