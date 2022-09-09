<?php

defined('ABSPATH') or die();

/**
 * Legacy render:
 * - Render control variable using deprecated synatax: {{ control-name }}
 * - Define subvalues as sass variables
 */

require_once __DIR__ . '/render.php';

$plugin->init_legacy_render = function($post, $data) use($plugin) {

  $plugin->legacy_render_data = $data;

  add_filter( 'tangible_template_post_style',  $plugin->legacy_style_render, 5, 2 );
  add_filter( 'tangible_template_post_script', $plugin->legacy_script_render, 5, 2 );

  $plugin->define_subvalue_variables($data);

  return $plugin->legacy_render( $post->post_content, 'template' );
};  

$plugin->reset_legacy_render = function() use($plugin) {

  $plugin->legacy_render_data    = false;
  
  remove_filter( 'tangible_template_post_style', $plugin->legacy_style_render, 5 ); 
  remove_filter( 'tangible_template_post_style', $plugin->legacy_script_render, 5 ); 
};

/**
 * Subvalues used to be defined as sass variables 
 */
$plugin->define_subvalue_variables = function($data) use($plugin, $html) {

  $fields = $data['fields'] ?? [];
  
  $sub_values = array_filter($fields, function($field) {
    return isset($field['is_sub_value']) && $field['is_sub_value'] === true;
  });
  
  foreach( $sub_values as $sub_value_name => $field ) {
        
    $type = $field['block']['type'];

    $control = $plugin->get_control( $type ); 

    if( $control === false || ! $control->has_context('style') ) {
      continue;
    }

    $value = $control->apply_render( $field['value'], $field, 'style' );
    $sass_name = str_replace(' ', '-', $sub_value_name);
    
    $sass_type = $plugin->get_sass_variable_type( $value, false );

    if( $sass_type === 'number' && is_int($value) ) {
      $value = (string) $value;
    }

    $html->set_sass_variable( $sass_name, $value, [ 'type' => $sass_type ] );
  }

};

/**
 * We don't need this function anymore, keep to avoid error if called
 */
$plugin->replace_control_values = function($content, $data, $context) {
  return $content;
};
