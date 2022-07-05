<?php

defined('ABSPATH') or die();

$render_data = [
  'content_id' => $module::$tangible_block['content_id'],
  'universal_id' => isset($module::$tangible_block['universal_id'])
    ? $module::$tangible_block['universal_id']
    : ''
  ,
  'fields'     => [],
  'wrapper'    => 'fl-node-' . $module->node
];

$plugin = tangible_blocks();
$fields = $plugin->get_block_controls( $render_data );

foreach( $fields as $field ) {

  if (!is_array($field)) continue;

  $value = isset($settings->{ $field['name'] }) ? $settings->{ $field['name'] } : '';

  $control = $plugin->get_control( $field['type'] );
  if( $control === false ) continue;

  $render_data['fields'][ $field['name'] ] = $control->get_builder_value( $value, 'beaver-builder', $field, $settings );

  // A control can have more than one value
  $sub_values = $control->get_builder_sub_values( 'beaver-builder', $field, $settings );

  $render_data['fields'] = is_array($sub_values)
    ? array_merge( $render_data['fields'], $sub_values )
    : $render_data['fields']
  ;
}

$post = $plugin->get_block_post_from_settings( $render_data );

if (!empty($post)) {
  $template_system = tangible_template_system();
  echo $template_system->render_template_post( $post, $render_data );
}
