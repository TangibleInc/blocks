<?php

defined('ABSPATH') or die();

$plugin->render = function($post, $data) use($plugin, $html) {

  /**
   * Legacy render using {{ control-name }} syntax
   * 
   * @see /legacy/render.php
   */
  $post->post_content = $plugin->init_legacy_render( $post, $data );
  
  $fields = $data['fields'] ?? [];
  
  $fields = array_filter($fields, function($field) {
    return ! ($field['is_sub_value'] ?? false);
  });

  /**
   * Register sass and js variable in template system
   * 
   * @see ./vendor/tangible/template-system/template/tags/get-set/sass
   * @see ./vendor/tangible/template-system/template/tags/get-set/js
   */
  
  foreach( $fields as $field ) {
    
    $type = $field['block']['type'];
    $name = $field['block']['name'];
    
    $control = $plugin->get_control( $type ); 

    if( $control === false ) continue;

    if( $control->has_context('style') ) {
      $value = $control->apply_render( $field['value'], $field, 'style' );
      if( ! empty($value) ) $html->set_sass_variables( $name, $value );
    }
    
    if( $control->has_context('script') ) {
      $value = $control->apply_render( $field['value'], $field, 'script' );
      if( ! empty($value) ) $html->set_js_variables( $name, $value );
    }

  }

  $template_system = tangible_template_system();
      
  $template_output = $template_system->render_template_post( $post, $data );

  $html->clear_sass_variables();
  $html->clear_js_variables();

  $plugin->reset_legacy_render();

  return $template_output;
};
