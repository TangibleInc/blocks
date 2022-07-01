<?php

defined('ABSPATH') or die();

/**
 * Replace {{ controls }} by given value in builder/shorcode
 */
$plugin->replace_control_values = function($content, $data, $context) use($plugin) {

  if ( empty($data['fields']) ) return $content;

  $define_sass_variables = '';

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

    if ($context !== 'style') continue;

    // Define as Sass variable

    // Ensure valid variable name
    $name = str_replace(' ', '-', $name);

    $is_unquoted = in_array($block['type'], ['dimension']) && !empty($value);

    // Wrap value in quotes if not color or number
    if (! ( $plugin->is_valid_color($value) || is_numeric($value) || $plugin->is_valid_gradient($value) || $is_unquoted )) {
      $value = '"' . str_replace('"', '\"', $value) . '"';
    }

    $define_sass_variables .= '$' . $name . ': ' . $value . ';';
  }

  $content = str_replace( '{{ wrapper-class }}', $data['wrapper'], $content );

  if( $context !== 'style' ) return $content;

  // If style, encapsulate into a wrapper class to affect only the current block

  return $define_sass_variables . "\n." . $data['wrapper'] . " {\n" . $content . "\n}";
};
