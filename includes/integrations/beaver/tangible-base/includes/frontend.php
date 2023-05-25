<?php

defined('ABSPATH') or die();

$render_data = [
  'content_id'    => $module::$tangible_block['content_id'],
  'universal_id'  => $module::$tangible_block['universal_id'] ?? '',
  'fields'        => [],
  'wrapper'       => 'fl-node-' . $module->node,
  'builder'       => 'beaver-builder'
];

$plugin = tangible_blocks();
$fields = $plugin->get_block_controls( $render_data );
$post   = $plugin->get_block_post_from_settings( $render_data );

if( empty($post) ) return;

foreach( $fields as $field ) {

  if ( ! is_array($field) ) continue;

  $name  = $field['name'];
  $value = $settings->{ $name } ?? '';

  $render_data['fields'][ $name ] = $plugin->format_control_value(
    $value, 'beaver-builder', $field, $settings, $post->ID ?? false 
  );
}

echo $plugin->render( $post, $render_data );
