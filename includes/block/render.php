<?php

defined('ABSPATH') or die();

/**
 * Note about legacy render/new controls:
 * 
 * If new controls are not actvated for the current block:
 * - The old {{ control-name }} syntax works
 * - Old controls are used to render values
 * - New syntax can also be used:
 *    - <Field value /> for render
 *    - <Field sub_value_name /> for subvalues
 * 
 * If new controls are actvated for the current block:
 * - Only the new syntax works
 * - New controls are used in builder and to render values
 */

$plugin->render = function($post, $data) use($plugin, $html, $template_system) {
  
  $post = $plugin->init_render( $post, $data );

  $fields = $data['fields'] ?? [];
  $is_legacy = $plugin->block_use_new_controls( $post->ID ) !== true;

  /**
   * For each field register sass, js and control variable in template system
   */
  foreach( $fields as $field ) {
    
    if( empty($field) ) continue;
    
    $args  = $field['attributes'];
    $value = $field['value'];

    $type = $args['type'];
    $name = $args['name'];
    
    $control = $is_legacy
      ? $plugin->get_legacy_control( $type ) 
      : $plugin->get_control( $type ); 

    if( $control === false ) continue;
    
    /**
     * @see ./vendor/tangible/template-system/template/tags/get-set/control
     */
    if( $control->has_context('template') ) {
      $control_value = $control->render( $value, $args, 'template' );
      $html->set_control_variable( $name, $control_value );
    }

    /**
     * @see ./vendor/tangible/template-system/template/tags/get-set/sass
     */
    if( $control->has_context('style') ) {

      $sass_value = $control->render( $value, $args, 'style' );
        
      $sass_name = str_replace(' ', '-', $name);
      $sass_type = $is_legacy
        ? $plugin->get_legacy_sass_variable_type( $sass_value, $type )
        : $control->get_sass_type();

      /**
       * When type is map, 2 variables are defined:
       * - $control-name -> Regular variable with the default value (if any)
       * - $control-name-map -> Map with all values
       */
      if( $sass_type === 'map' && ! empty($sass_value) ) {

        $sass_map_name = $sass_name . '-map';
        $html->set_sass_variable( $sass_map_name, $sass_value, [ 'type' => $sass_type ]  );

        $sass_value = $control->render( $value, $args, 'style', true );
        $sass_type = $control->get_sass_map_default_type(); 
      }

      if( $sass_type === 'number' && is_int($sass_value) ) {
        $sass_value = (string) $sass_value;
      }
      
      $sass_type = ! empty($control_value) ? $sass_type : 'string';
      $html->set_sass_variable( $sass_name, $sass_value, [ 'type' => $sass_type ]  );
    }
    
    /**
     * @see ./vendor/tangible/template-system/template/tags/get-set/js
     */
    if( $control->has_context('script') ) {

      $js_type = $is_legacy ? 'string' : $control->get_js_type();
      $js_value = $control->render( $value, $args, 'script' );

      $html->set_js_variable( $name, $js_value, [ 'type' => $js_type ] );
    }

  }
  
  $template_output = $template_system->render_template_post( $post, $data );

  $plugin->reset_render( $post );

  return $template_output;
};

$plugin->init_render = function($post, $data) use($plugin) {

  $plugin->current_block_wrapper = $data['wrapper'];
  
  /**
   * Legacy render using {{ control-name }} syntax
   * 
   * Value rendered with this syntax will use old controls 
   * 
   * @see ./includes/legacy/render/
   */
  if( ! $plugin->block_use_new_controls( $post->ID ) ) {
    $post->post_content = $plugin->init_legacy_render( $post, $data );
  }
  
  return $post;
};

$plugin->reset_render = function($post) use($html, $plugin) {

  $html->clear_sass_variables();
  $html->clear_js_variables();
  $html->clear_control_variables();
  
  $plugin->current_block_wrapper = false;

  if( ! $plugin->block_use_new_controls( $post->ID ) ) {
    $plugin->reset_legacy_render();
  }
};

/**
 * Encapsulate blocks style in the current block wrapper
 * 
 * @see /vendor/tangible/template-system/system/render/style.php
 */
add_filter('tangible_template_post_style', function($style, $post) use($plugin) {

  if( $post->post_type !== 'tangible_block' || empty($plugin->current_block_wrapper) ) {
    return $style;
  } 
  
  return '.' . $plugin->current_block_wrapper . " {\n" . $style . "\n}";
}, 10, 2);
