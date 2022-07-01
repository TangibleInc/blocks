<?php

namespace Tangible\Template\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

/**
 * @see https://beaverplugins.com/docs/toolbox/the-alias-settings-form/
 */
function create_module($block) {

  $generated_name = uniqid( 'TangibleBlock_' );
  eval( 'class ' . $generated_name . ' extends \Tangible\Template\Integrations\Beaver\Dynamic\Base { static $tangible_block; }' );
  $generated_name::$tangible_block = $block;

  /**
   * Universal ID - Unique and immutable across sites
   * @see /includes/template/universal-id/index.php
   */
  $block_slug = !empty($block['universal_id'])
    ? $block['universal_id']
    : $block['content_id'] // Backward compatibility
  ;

  $class_name = 'TangibleBlock_' . $block_slug;
  $class_name = 'Tangible\Template\Integrations\Beaver\Dynamic\\' . $class_name;

  if( class_exists($class_name) ) return;

  class_alias( $generated_name, $class_name );

  \FLBuilder::register_module( $class_name, to_settings( $block ) );
}

function to_settings($block) {

  $settings = [];
  $visibility_by = [
    'tabs'     => [],
    'sections' => [],
    'fields'   => [],
  ];

  if( empty($block['tabs']) ) return $settings;

  $plugin = \tangible_blocks();

  foreach( $block['tabs'] as $tab ) {

    $settings[ $tab['name'] ] = [
      'title'     => $tab['label'] === 'default' ? __( 'General', '' ) : $tab['label'],
      'sections'  => []
    ];

    if( empty($tab['sections']) ) return $settings;

    // Pass visibility conditions to JS
    if( isset($tab['conditions']) ) {
      $visibility_by['tabs'][ $tab['name'] ] = $tab['conditions'];
    }

    $setting_section = &$settings[ $tab['name'] ]['sections'];

    foreach( $tab['sections'] as $section ) {

      $setting_section[ $section['name'] ] = [
        'title' => $section['label'],
        'fields'  => []
      ];

      // Pass visibility conditions to JS
      if( isset($section['conditions']) ) {
        $visibility_by['sections'][ $section['name'] ] = $section['conditions'];
      }

      $fields = &$setting_section[ $section['name'] ]['fields'];

      foreach( $section['fields'] as $field ) {

        $args = $plugin->get_builder_args($field, 'beaver-builder');

        if( $args === false ) continue;

        $fields[ $field['name'] ] = $args;

        // Pass visibility conditions to JS
        if( isset($field['conditions']) ) {
          $visibility_by['fields'][ $field['name'] ] = $field['conditions'];
        }
      }
    } // Each section
  } // Each tab


  // Register tabs/sections/fields visibility for this block

  $id = $block['content_id'];
  $visibility = &$plugin->beaver_dynamic_config['visibility'];

  foreach (['tabs', 'sections', 'fields'] as $key) {
    if (!isset($visibility[ $key ])) $visibility[ $key ] = [];
    $visibility[ $key ][ $id ] = $visibility_by[ $key ];
  }

  return $settings;
}