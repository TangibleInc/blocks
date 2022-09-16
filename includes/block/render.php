<?php

defined('ABSPATH') or die();

$plugin->render = function($post, $data) use($plugin, $html) {

  $post = $plugin->init_render( $post, $data );

  $fields = $data['fields'] ?? [];
  
  /**
   * Register sass, js and control variable in template system
   * 
   * @see ./vendor/tangible/template-system/template/tags/get-set/sass
   * @see ./vendor/tangible/template-system/template/tags/get-set/js
   * @see ./vendor/tangible/template-system/template/tags/get-set/js
   */
  
  foreach( $fields as $field ) {
    
    $type = $field['attributes']['type'];
    $name = $field['attributes']['name'];
    
    $control = $plugin->get_control( $type ); 

    if( $control === false ) continue;

    if( $control->has_context('template') ) {
      $control_value = $plugin->format_control_variable($control, $field);
      $html->set_control_variable( $name, $control_value );
    }

    if( $control->has_context('style') ) {

      $value = $control->apply_render( $field['main_value'], $field, 'style' );
        
      $sass_name = str_replace(' ', '-', $name);
      $sass_type = $plugin->get_sass_variable_type( $value, $type );

      if( $sass_type === 'number' && is_int($value) ) {
        $value = (string) $value;
      }

      $html->set_sass_variable( $sass_name, $value, [ 'type' => $sass_type ]  );
    }

    if( $control->has_context('script') ) {
      $value = $control->apply_render( $field['main_value'], $field, 'script' );
      $html->set_js_variable( $name, $value );
    }

  }
  
  $template_system = tangible_template_system();
      
  $template_output = $template_system->render_template_post( $post, $data );

  $plugin->reset_render();

  return $template_output;
};

$plugin->init_render = function( $post, $data ) use($plugin) {

  $plugin->current_block_wrapper = $data['wrapper'];
  
  /**
   * Legacy render using {{ control-name }} syntax
   * 
   * @see /legacy/render.php
   */
  $post->post_content = $plugin->init_legacy_render( $post, $data );
  
  return $post;
};

$plugin->reset_render = function() use($html, $plugin) {

  $html->clear_sass_variables();
  $html->clear_js_variables();
  $html->clear_control_variables();
  
  $plugin->current_block_wrapper = false;

  $plugin->reset_legacy_render();

};

$plugin->get_sass_variable_type = function($value, $control_type) use($plugin) {
  
  if( $control_type === 'dimension' && ! empty($value) ) return 'dimension';
  if( $plugin->is_valid_color($value) ) return 'color';
  if( $plugin->is_valid_gradient($value) ) return 'color';
  if( is_numeric($value) ) return 'number';

  return 'string';
};

$plugin->format_control_variable = function($control, $field) {

  $data = [ 
    'value' => $control->apply_render( $field['main_value'], $field, 'template' ) 
  ];
  
  if( empty($field['sub_values']) ) return $data;
  
  foreach( $field['sub_values'] as $key => $value ) {
    $data[$key] = $value;
  }
  
  return $data;
};

/**
 * Encapsulate blocks style in the current block wrapper
 * 
 * @see /vendor/tangible/template-system/system/render/style.php
 */
add_filter( 'tangible_template_post_style', function($style, $post) use($plugin) {

  if( $post->post_type !== 'tangible_block' || empty($plugin->current_block_wrapper) ) {
    return $style;
  } 
  
  return '.' . $plugin->current_block_wrapper . " {\n" . $style . "\n}";

}, 10, 2 );
