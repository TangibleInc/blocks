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

  if( ! isset($block_data['tabs']) ) return [];

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
$plugin->get_builder_args = function(array $args, string $builder, $block_id) use($plugin) {

  if( ! in_array($builder, ['elementor', 'beaver-builder', 'gutenberg']) ) {
    return false;
  } 
  
  $control = $plugin->get_control( 
    $args['type'] ?? '' 
  );

  if( $control === false ) return [];

  $args['block_id'] = $block_id;

  return $control->register_control( $builder, $args );
};

