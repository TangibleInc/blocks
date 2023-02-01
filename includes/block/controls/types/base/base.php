<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Base {

  use Elementor;

  static $plugin, $fields;

  public array $context = ['template', 'style', 'script'];
   
  /**
   * We use a prefix when registering a control in builders, to avoid collision with existing ones
   */
  function get_prefixed_type() {
    return 'tangible_block_control_' . $this->type;
  }

  function get_control_args(string $builder, array $args) : array {

    $args['data'] = self::$fields->format_args(
      $args['name'] ?? '', 
      $args, 
      false
    );

    /**
     * When possible, we use the native way to display informations (label, description ...etc) 
     */
    if( $builder === 'beaver-builder' ) {
      unset($args['data']['label']);
    }

    $args = $this->register_control($builder, $args);
    
    /**
     * For beaver-builder and elementor we need to prefix our controls
     * in order to avoid conflict with native ones
     */
    $args['type'] = $builder !== 'gutenberg'
      ? $this->get_prefixed_type()
      : $this->type;
    
    /**
     * When we let multiple set it can trigger a default native behavior we don't want in 
     * some builder (like beaver-builder for example)
     */
    if( isset($args['multiple']) ) unset($args['multiple']);

    return $args;
  }

  function register_control(string $builder, array $args) : array {
    return $args;
  }

  function format_value($value, string $builder, array $args, $settings) {
    return $value;
  }

  function get_value($formated_value, array $args, string $context) {
    return $formated_value;
  }

  /**
   * Can be used to enqueue script dependencies for the control
   */
  function enqueue(string $handle, string $builder) {}

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
Base::$fields = $fields;
