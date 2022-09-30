<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base as ElementorBase;
use Tangible\Blocks\Integrations\Beaver\Dynamic as beaver;
use FLBuilder;

class Repeater extends Base {

  /**
   * Elementor: @see https://developers.elementor.com/docs/controls/classes/control-repeater/
   */
  function register_control(string $builder, array $args): array {
    
    $label    = $args['label'] ?? '';
    $default  = $args['default'] ?? '';   
    $controls = $args['controls'] ?? '';
    
    switch($builder) {
      case 'elementor':
        return [
          'type'     => 'repeater',
          'label'    => $label,
          'default'  => $default,
          'controls' => is_array($controls) ? $controls : [],
        ];
      case 'beaver-builder':
        return [
          'type'     => 'form',
          'label'    => $label,
          'form'     => $this->register_beaver_form( $args ),
          'multiple' => true
        ];
      case 'gutenberg':
        return [
          'type'    => 'array',
          'default' => [],
        ];
    }
  }

  function get_initial_item_structure(array $args): array {
    
    $block_id = $args['block_id'] ?? false;
    $controls = $args['controls'] ?? [];
    $response = [];

    if( ! is_array($controls) ) return $response;

    $controls = array_map(function($control) use($block_id) {
      return array_merge(
        [ 'name' => $control['name'] ], 
        self::$plugin->get_builder_args( $control, 'gutenberg', $block_id )
      );
    }, $controls);

    foreach( $controls as $control ) {
      
      if( ! empty($control['default']) ) {
        $initial_value = $control['default'];
      } else switch($control['type']) {
          case 'string' : $initial_value = ''; 
          case 'array'  : $initial_value = [];
          case 'integer': $initial_value = 0;
        default: $initial_value = ''; 
      }
      $response[ $control['name'] ] = $initial_value;
    }

    return $response;
  }

  /**
   * We need to format data for each sub control
   */
  function sanitize_args(array $args): array {

    $args = parent::sanitize_args($args);

    if( ! is_array($args['controls'] ?? '') ) return $args;

    $args['controls'] = array_map(function($args) {
      
      $control = self::$plugin->get_control( $args['type'] ?? '' );
      
      if( empty($control) ) return $args;

      return $control->sanitize_args( $args );
    }, $args['controls']);

    $args['item_structure'] = $this->get_initial_item_structure( $args );

    return $args;
  }

  /**
   * @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#form-field
   */
  function register_beaver_form(array $args) {

    $block_id = $args['block_id'] ?? false;
    $name     = $args['name'] ?? false;
    
    if( ! $block_id || ! $name ) return false;

    $form_name = '_tangible_repeater_' . $block_id . '_' . $name;

    $label    = $args['label'] ?? '';
    $controls = $args['controls'] ?? [];

    FLBuilder::register_settings_form($form_name, [
      'title' => $label,
        'tabs' => [
        'general' => [
          'title'    => $label,
          'sections' => [
            'controls' => [
              'title'  => $label,
              'fields' => beaver\format_setting_fields( $block_id, $controls )
            ]
          ]
        ]
      ]
    ]);

    return $form_name;
  }

  function format_value($value, string $builder, array $args, $settings) {

    $controls = $args['controls'] ?? '';
    $controls = is_array($controls) ? $controls : [];

    if( $builder === 'beaver-builder' ) {
      $value = $this->format_beaver_builder_value( $value, $controls );
    }

    $items = is_array($value) ? $value : [];
    
    $response = array_map(function($item) use($controls, $builder, $settings) {
      
      $data = [];

      foreach( $controls as $control ) {

        $prefix = $builder === 'elementor' ? ElementorBase::$control_prefix : ''; 
        $child_value = $item[ $prefix . $control['name'] ] ?? '';
        
        $child_value = self::$plugin->format_control_value(
          $child_value, 
          $builder, 
          $control, 
          $builder === 'beaver-builder' ? (object) $item : $item
        );

        $data[ $control['name'] ] = $child_value; 
      }
      
      return $data;

    }, $items);

    return $response;
  }

  /**
   * Beaver builder return an array of object, we want an
   * array of arrays
   */
  function format_beaver_builder_value($value, $controls) {
    
    if( empty($value) ) return [];

    $value = array_map(function($item) {
      return self::$plugin->object_to_array($item);
    }, $value);

    $gallery_controls = [];

    /**
     * When used in a form, the beaver builder gallery control will
     * not return an array as it should, but a string
     * 
     * Until it's not fixed, we need to convert the string to an array
     * manually
     */

    foreach( $controls as $control ) {
      if( $control['type'] !== 'gallery' ) continue;
      $gallery_controls []= $control['name'];
    }

    if( empty($gallery_controls) ) return $value;

    return array_map(function($fields) use($gallery_controls) {

      foreach( $fields as $name => $field ) {

        if( ! in_array($name, $gallery_controls) ) continue;
        if( empty($field) || ! is_string($field) ) continue;

        $fields[ $name ] = json_decode($field);
      }

      return $fields;
    }, $value);
  }

  function get_value($items, array $args, string $context) {

    $response = [];
    $controls = $args['controls'] ?? '';
        
    return array_map(function($item) use($controls, $context) {
      
      $data = [];

      foreach( $controls as $control ) {

        $child_control = self::$plugin->get_control( $control['type'] ?? '' );
        $child_name = $control['name'] ?? '';
        $child_data = $item[ $child_name ];
        
        if( ! $child_control ) continue;
        
        $data[ $child_name ] = $child_control->render( 
          $child_data['value'], 
          $child_data['attributes'], 
          $context 
        );
      }

      return $data;

    }, $items);
  }

}

$plugin->register_control('repeater', new Repeater);
