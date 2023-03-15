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

  function get_value($value, array $args, string $context) {
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

  function render($value, array $args, string $context, bool $force_default = false) {

    if( ! $this->has_context($context) ) return '';

    $value = $this->get_value( $value, $args, $context );
    
    if( ! is_array($value) ) return $value;

    $is_template = $context === 'template'; 
    $is_sass_map = $context === 'style' && $this->get_sass_type() === 'map';

    /**
     * If we can't return all the value, return just the default (if any) 
     */
    if( (! $is_template && ! $is_sass_map) || $force_default ) {
      return $value['value'] ?? '';
    } 
    
    return $is_sass_map
      ? $this->get_sass_map($value)
      : $value;
  }

  function get_js_type() : string {
    return 'string';
  }

  /**
   * If string value will be used inside of quotes
   * 
   * If map, each map value type can be defined using the get_sass_map_type method
   */
  function get_sass_type() : string {
    return 'string';
  }

  /**
   * When map key value is different than string, it must be defined here
   */
  function get_sass_map_types() : array {
    return [];
  }

  function get_sass_map_default_type() : string {
    return $this->get_sass_map_types()['value'] ?? 'string';
  }

  /**
   * @see https://sass-lang.com/documentation/values/maps
   */
  function get_sass_map(array $values) : string {
    
    $types = $this->get_sass_map_types();
    $map = [];

    foreach( $values as $key => $value ) {
      
      $type = $types[ $key ] ?? 'string';
      $value = $type === 'string' ? '"' . $value .  '"' : $value;

      $map []= '"' . $key . '": ' . $value;
    }

    return '(' . implode(',', $map) . ')';
  }

}

Base::$plugin = $plugin;
Base::$fields = $fields;
