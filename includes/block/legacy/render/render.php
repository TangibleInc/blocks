<?php

defined('ABSPATH') or die();

$plugin->legacy_render = function($content, $context) use($plugin) {

  $data = $plugin->legacy_render_data;
  
  if ( empty($data['fields']) ) return $content;

  foreach( $data['fields'] as $name => $field ) {
    
    $attributes = $field['attributes'];
    $value = $field['main_value'];

    // @see control.php

    $control = $plugin->get_legacy_control( $attributes['type'] );
    
    if( $control === false ) continue;
    
    $value = $control->apply_render( $value, $field, $context );
      
    // Custom field may return object

    $value = gettype( $value ) === 'object' ? json_encode( $value ) : wp_kses_post( $value );
    $content = str_replace( "{{ $name }}", $value, $content );

    if( empty($field['sub_values']) ) continue;
    
    foreach( $field['sub_values'] as $sub_value_name => $sub_value ) {
      
      $sub_value_name = $name . '-' . $sub_value_name;

      $value = gettype( $sub_value ) === 'object' ? json_encode( $sub_value ) : wp_kses_post( $sub_value );
      $content = str_replace( "{{ $sub_value_name }}", $sub_value, $content );
    }
  }
  
  $content = str_replace( '{{ wrapper-class }}', $data['wrapper'], $content );
  
  return $content;
};

$plugin->legacy_style_render = function($style, $post) use($plugin) {

  if( $post->post_type !== 'tangible_block' ) return $style;

  return $plugin->legacy_render( $style, 'style' );
};

$plugin->legacy_script_render = function($script, $post) use($plugin) {

  if( $post->post_type !== 'tangible_block' ) return $script;

  return $plugin->legacy_render( $script, 'script' );
};


