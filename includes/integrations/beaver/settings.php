<?php

namespace Tangible\Blocks\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

function format_settings($block) {

  $settings = [];
  $visibility_by = [
    'tabs'      => [],
    'sections'  => [],
    'fields'    => [],
    'repeaters' => [],
  ];

  if( empty($block['tabs']) ) return $settings;

  $plugin = tangible_blocks();
  $block_id = $plugin->get_block_id( $block );

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
      $fields = format_setting_fields( $block, $section['fields'] );
      
      // Pass visibility conditions to JS
      foreach( $section['fields'] as $field ) {
        
        if( empty($field['type']) || ! isset($field['conditions']) ) {
          continue;
        } 

        $visibility_by['fields'][ $field['name'] ] = $field['conditions'];
      }

    } // Each section
  } // Each tab


  // Register tabs/sections/fields visibility for this block

  $visibility = &$plugin->beaver_dynamic_config['visibility'];

  foreach (['tabs', 'sections', 'fields', 'repeaters'] as $key) {
    
    if (!isset($visibility[ $key ])) $visibility[ $key ] = [];
    $visibility[ $key ][ $block_id ] = $visibility_by[ $key ];
  }

  return $settings;
}

function format_setting_fields( $block, $fields) {

  $plugin = tangible_blocks();
  $formated_fields = [];

  foreach( $fields as $field ) {

    if( ! is_array($field) ) continue;

    $args = $plugin->get_builder_args( $field, 'beaver-builder', $block['content_id'] );

    if( $args === false ) continue;

    $formated_fields[ $field['name'] ] = $args;
  }
  
  return $formated_fields;
}
