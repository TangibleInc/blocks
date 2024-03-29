<?php

namespace Tangible\Blocks\Legacy;

defined('ABSPATH') or die();

/**
 * TODO: Separate code for custom and alias controls in a 2 child class
 */
class Control {

  private $default = false;
  private $sub_values = false;
  private $is_alias = false;
  private $is_custom = false;
  private $context = ['template', 'style', 'script'];

  public array $types;
  public string $name;

  public $initial_type;
  public $alias_values;

  public $get_elementor_data;
  public $get_beaver_builder_data;
  public $get_gutenberg_data;
  public $get_enqueue_callback;
  public $get_sub_value;
  public $filter_value;
  public $render;

  function __construct(string $name, array $types) {

    /*
     * @see Elementor https://developers.elementor.com/add-controls-to-widgets/
     * @see BeaverBuilder https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/
     * @see Gutenberg https://developer.wordpress.org/block-editor/developers/block-api/block-attributes/
     */
    $this->types = $types;
    $this->name = $name;
  }

  /**
   * An alias is a copy of an existing control, but with a different type name and prepopulated values
   *
   * @return void
   */
  function init_alias($alias, $type, $values = []) {

    $this->is_alias = true;

    $this->initial_type = $type;
    $this->alias_values = array_merge([
      'type'  => $this->initial_type,
      'alias' => $alias
    ], $values);
  }

  function init_custom() {
    $this->is_custom = true;
  }

  function sanitize_args($args) {

    /**
     * Ensure required properties
     *
     * This prevents builder callbacks (gutenberg, elementor, etc.) from
     * throwing warnings for undefined index, if incomplete block control
     * definition is passed.
     */

    $args['type']  = $args['type'] ?? '';
    $args['name']  = $args['name'] ?? '';
    $args['label'] = $args['label'] ?? '';

    return $this->is_alias ? array_merge($args, $this->alias_values) : $args;
  }

  function context($context) {
    $this->context = (array) $context;
    return $this;
  }

  function has_context(string $context) {
    return in_array($context, $this->context);
  }

  /**
   * Data needed for generating the control for each builder
   */

  function elementor($callback) {
    $this->get_elementor_data = $callback;
    return $this;
  }

  function beaver_builder($callback) {
    $this->get_beaver_builder_data = $callback;
    return $this;
  }

   // In gutenberg, type is type of data we save instead of type of control like in other builders
  function gutenberg($callback) {
    $this->get_gutenberg_data = $callback;
    return $this;
  }

  /**
   * Method dedicated to custom aliases
   */
  function enqueue($callback) {

    if( ! $this->is_custom ) return $this;

    $this->get_enqueue_callback = $callback;
    return $this;
  }

  function enqueue_callback($handle, $builder) {

    if( empty($this->get_enqueue_callback) || ! is_callable($this->get_enqueue_callback) ) {
      return;
    } 

    // Not sure it belongs here
    $plugin = tangible_blocks();
    $assets_url = $plugin->assets_url . '/build/';
    $version = $plugin->version;

    call_user_func_array(
      $this->get_enqueue_callback,
      [ $handle, $builder ]
    );
  }

  /**
   * @see block/get_field_args
   */
  function register_control($builder, $field) {

    $field = $this->sanitize_args($field);

    switch($builder) {
      case 'elementor':
        $callback = $this->get_elementor_data;
        break;
      case 'beaver-builder':
        $callback = $this->get_beaver_builder_data;
        break;
      case 'gutenberg':
        $callback = $this->get_gutenberg_data;
        break;
    }

    $data = call_user_func_array(
      $callback,
      [$field, $this->get_type($builder)]
    );

    $is_default_value = isset($data['default']) && !empty($data['default']);

    if( ! $is_default_value || ! is_callable($this->default) ) {
      return $data;
    }

    $data['default'] = call_user_func_array(
      $this->default,
      [ $data['default'], $builder, $field ]
    );

    return $data;
  }

  /**
   * Alias used by the new control system
   */
  function get_control_args($builder, $field) {
    return $this->register_control($builder, $field);
  }

  /**
   * Return the right field slug according to the builder
   */
  function get_type($builder) {
    return $this->types[ $builder ] ?? false;
  }

  /**
   * Allow value to be filtered according to the builder's data
   */
  function filter_value($callback) {
    $this->filter_value = $callback;
    return $this;
  }

  function format_data($value, $builder, $data, $settings) {
    
    $value = $this->get_builder_value( $value, $builder, $data, $settings );
    $sub_value = $this->get_builder_sub_values( $builder, $data, $settings );
    
    $formated_value = array_merge(
      [ 'value' => $value ],
      $sub_value ?: []
    );

    return [
      'attributes' => $this->sanitize_args( $data ),
      'value'      => $formated_value
    ];
  }

  function format_legacy_data($value, $builder, $data, $settings) {
    return [
      'attributes'  => $this->sanitize_args( $data ),
      'main_value'  => $this->get_builder_value( $value, $builder, $data, $settings ),
      'sub_values'  => $this->get_builder_sub_values( $builder, $data, $settings )
    ];
  }

  function get_builder_value($value, $builder, $data, $settings) {

    $callback = $this->filter_value ?? false;
    $field = $this->sanitize_args($data);

    return is_callable($callback)
      ? $callback($value, $builder, $field, $settings)
      : $value
    ;
  }

  /**
   * Optional if special render needed
   *
   * @see /builders/render.php
   */
  function legacy_render($callback) {
    $this->render = $callback;
    return $this;
  }

  /**
   * Compatibility when legacy control used in new system
   * 
   * The function is only be called for the new syntax
   */
  function render($value, $args, $context) {

    if( ! in_array($context, $this->context) ) return '';

    $value['value'] = $this->get_value($value['value'], $args, $context);

    // Only template support array as a render, for other contexes we use default output
    return $context === 'template' ? $value : $value['value']; 
  }

  function get_value($value, $args, $context) {

    if( ! in_array($context, $this->context) ) return '';
   
    $field = $this->sanitize_args( $args );
    $callback = $this->render ?? false;

    return is_callable($callback)
      ? $callback($value, $field, $context)
      : $value
    ;
  }

  /**
   * Gives the possibility to use more than one value for a control
   *
   * Each new value can be used in the render using a suffix:
   *
   * { $controlName-$valueName }
   */
  function sub_values($values) {

    if( ! is_array($values) ) return;

    $this->sub_values = $values;
    return $this;
  }

  function get_builder_sub_values($builder, $field, $settings) {

    if( ! is_array($this->sub_values) ) return false;

    $sub_values = [];
    $field = $this->sanitize_args($field);

    foreach( $this->sub_values as $name ) {

      $sub_values[ $name ] = (function($builder, $settings) use($name, $field) {

        $callback = isset($this->get_sub_value) && is_callable($this->get_sub_value)
          ? $this->get_sub_value
          : false
        ;

        if( empty($callback) ) return '';

        return $callback($name, $builder, $field, $settings);

      })($builder, $settings);
    }

    return $sub_values;
  }

  function render_sub_values($callback) {
    $this->get_sub_value = $callback;
    return $this;
  }

  /**
   * Give possibility to adjust default value according to the builder
   */
  function default($callback) {
    $this->default = $callback;
    return $this;
  }

}
