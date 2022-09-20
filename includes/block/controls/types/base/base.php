<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Base {

  use Elementor;

  static $plugin;

  public array $context = ['template', 'style', 'script'];

  
  function register_control(string $builder, array $args): array {
    return $attribute;
  }

  function format_value($value, string $builder, array $args, $settings) {
    return $value;
  }

  function get_value($formated_value, array $args, string $context) {
    return $formated_value;
  }

  /**
   * Make sure required value are defined, event if empty
   */
  function sanitize_args(array $args): array {
    return wp_parse_args($args, [
      'type'  => '',
      'name'  => '',
      'label' => ''
    ]);
  }

  function format_data($value, string $builder, array $args, $settings) {
    return [
      'attributes' => $this->sanitize_args( $args ),
      'value'      => $this->format_value( $value, $builder, $args, $settings )
    ];
  }

  function has_context(string $context) {
    return in_array($context, $this->context);
  }

  function render( $value, array $args, string $context ) {

    if( ! $this->has_context($context) ) return '';

    $value = $this->get_value( $value, $args, $context );
    
    if( ! is_array($value) ) return $value;

    /**
     * Loopable values will only worked in template. 
     * 
     * Return default value (if any) for script and style 
     */
    if( $context !== 'template' ) return $value['value'] ?? '';

    return $value;
  }

}

Base::$plugin = $plugin;
