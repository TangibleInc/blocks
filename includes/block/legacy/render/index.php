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
  
  foreach( $fields as $name => $field ) {
        
    if( empty($field['sub_values']) ) continue;
     
    $type = $field['attributes']['type'];
    $name = $field['attributes']['name'];

    $control = $plugin->get_legacy_control( $type ); 
    
    if( $control === false || ! $control->has_context('style') ) {
      continue;
    }
    
    foreach( $field['sub_values'] as $sub_value_name => $sub_value ) {
      
      $sub_value_name = $name . '-' . $sub_value_name;
      
      $sass_name = str_replace(' ', '-', $sub_value_name);
      $sass_type = $plugin->get_sass_variable_type( $sub_value, false );
      
      if( $sass_type === 'number' && is_int($sub_value) ) {
        $sub_value = (string) $sub_value;
      }

      $html->set_sass_variable( $sass_name, $sub_value, [ 'type' => $sass_type ] );
    }

  }

};

/**
 * We don't need this function anymore, keep to avoid error if called
 */
$plugin->replace_control_values = function($content, $data, $context) {
  return $content;
};

