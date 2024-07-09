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

    /**
     * For now, we don't rely on tangible-fields visibility conditions in the blocks plugin, 
     * and still rely on plugin specific code for this
     * 
     * Conditions evaluated by the blocks plugin are defined under $args['conditions']), while tangible-fields
     * conditions are declared under $args['condition'])
     * 
     * To prevent any confusion or conflict, we will remove any tangible fields condition defined until it's
     * ready to fully replace the block conditional visbility system
     * 
     * @see Block visibility: ./assets/src/template-control-visibility
     * @see Fields visibility: ./vendor/tangible/fields/assets/src/visibility
     */
    if( isset($args['condition']) ) unset($args['condition']);

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

    if( $context === 'style' ) {
      return $this->get_sass_variable_definition( $value, $args );
    }

    if( ! is_array($value) ) return $value;

    $use_multiple_values = $context === 'template';

    /**
     * If we can't return all the values, return just the default (if any) 
     */
    if( ! $use_multiple_values || $force_default ) {
      return $value['value'] ?? '';
    }

    return $value;
  }

  function get_js_type() : string {
    return 'string';
  }

  /**
   * Returns an array with the arguments needed to define the type of variable we need in 
   * sass, will be a simple string if not defined by child class
   * 
   * @see ./includes/blocks/sass.php
   */
  function get_sass_variable_definition($value, array $args) : array {
    return [ 
      'type' => 'string', 
      'value' => $value
    ];
  }

  /**
   * Three possible variables:
   * - default - ${name}: Always defined, but if list will be empty and if map will attempt to use a default value
   * - map     - ${name}-map: Only defined when map type
   * - list    - ${name}-list: Only defined when list type
   */
  function get_sass_variables(array $definition) : array {

    $variables = [];
    $type = $definition['type'] ?? 'string';
    $value = $definition['value'] ?? '';

    if( $type === 'list' ) {
      $variables['list'] = $definition;
      $variables['default'] = [
        'type'  => 'string',
        'value' => ''
      ];
    }
    else if( $type === 'map' ) {
      $variables['map'] = $definition;
      $default = $value['value'] ?? false;
      $default = ! in_array($default['type'], ['map', 'list']) ? $default : false;
      $variables['default'] = $default 
        ? [
            'type'  => $default['type'],
            'value' => (string) $default['value']
          ]
        : [
          'type'  => 'string',
          'value' => ''
        ];
    }
    else {
      $variables['default'] = $definition;
    }
    
    return $variables;
  }

}

Base::$plugin = $plugin;
Base::$fields = $fields;
