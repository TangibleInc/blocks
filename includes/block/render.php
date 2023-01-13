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
   * @see ./vendor/tangible/template-system/template/tags/get-set/control
   */
  
  foreach( $fields as $field ) {
    
    $args  = $field['attributes'];
    $value = $field['value'];

    $type = $args['type'];
    $name = $args['name'];
    
    $control = $plugin->get_control( $type ); 

    if( $control === false ) continue;

    if( $control->has_context('template') ) {
      $control_value = $control->render( $value, $args, 'template' );
      $html->set_control_variable( $name, $control_value );
    }

    if( $control->has_context('style') ) {

      $control_value = $control->render( $value, $args, 'style' );
        
      $sass_name = str_replace(' ', '-', $name);
      $sass_type = $plugin->get_sass_variable_type( $control_value, $type );

      if( $sass_type === 'number' && is_int($control_value) ) {
        $control_value = (string) $control_value;
      }

      $html->set_sass_variable( $sass_name, $control_value, [ 'type' => $sass_type ]  );
    }
    
    if( $control->has_context('script') ) {

      $js_type = $plugin->get_js_variable_type( $value, $type );
       
      $control_value = $control->render( $value, $args, 'script' );

      $html->set_js_variable( $name, $control_value, [ 'type' => $js_type ] );
    }

  }
  
  $template_system = tangible_template_system();
      
  $template_output = $template_system->render_template_post( $post, $data );

  $plugin->reset_render();

  return $template_output;
};

$plugin->init_render = function($post, $data) use($plugin) {

  $plugin->current_block_wrapper = $data['wrapper'];
  
  /**
   * Legacy render using {{ control-name }} syntax
   * 
   * @see /legacy/render.php
   */
  // $post->post_content = $plugin->init_legacy_render( $post, $data );
  
  return $post;
};

$plugin->reset_render = function() use($html, $plugin) {

  $html->clear_sass_variables();
  $html->clear_js_variables();
  $html->clear_control_variables();
  
  $plugin->current_block_wrapper = false;

  // $plugin->reset_legacy_render();

};

$plugin->get_sass_variable_type = function($value, $control_type) use($plugin) {
  
  if( $control_type === 'dimension' && ! empty($value) ) return 'dimension';
  if( $plugin->is_valid_color($value) ) return 'color';
  if( $plugin->is_valid_gradient($value) ) return 'color';
  if( is_numeric($value) ) return 'number';

  return 'string';
};

$plugin->get_js_variable_type = function($value, $control_type) {
  return 'string';
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
