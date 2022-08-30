<?php

defined('ABSPATH') or die();

$plugin->legacy_render = function($content, $context) use($plugin) {

  $data = $plugin->legacy_render_data;

  if ( empty($data['fields']) ) return $content;

  foreach( $data['fields'] as $name => $field_data ) {

    $block = $field_data['block'];
    $value = $field_data['value'];

    // @see control.php

    $control = $plugin->get_control( $block['type'] );

    if( $control === false ) continue;

    $value = $control->apply_render( $value, $field_data, $context );

    // Custom field may return object

    $value = gettype( $value ) === 'object' ? json_encode( $value ) : wp_kses_post( $value );
    $content = str_replace( "{{ $name }}", $value, $content );

  }

  $content = str_replace( '{{ wrapper-class }}', $data['wrapper'], $content );

  return $content;
};

