<?php

namespace Tangible\Blocks\Integrations\Gutenberg\Dynamic;

defined('ABSPATH') or die();

/**
 * Attributes will contain only the information gutenberg needs to know for state management.
 *
 * Information needed for generating the settings fields will be passed in window.Tangible.blocks
 */
function format_attributes( array $block ) {

  $plugin = tangible_blocks();

  $attributes = [
    'config' => array_values([
      'type'    => 'array',
      'default' => $block
    ]),
    'name' => [
      'type'    => 'string',
      'default' => $block['name'],
    ],
    'block_id' => [
      'type' => 'string'
    ],
    // Post ID as "content ID" for backward compatibility
    'content_id' => [
      'type'    => 'integer',
      'default' => (int) $block['content_id'],
    ],
    /**
     * Universal ID - Unique and immutable across sites
     * @see /includes/template/universal-id/index.php
     */
    'universal_id' => [
      'type'    => 'string',
      'default' => $block['universal_id'] ?? '',
    ],
    /**
     * Current post ID inside Gutenberg
     */
    'current_post_id' => [
      'type'    => 'number',
      'default' => 0,
    ],
  ];

  $block_id = $plugin->get_block_id( $block );
  $fields   = $plugin->get_block_controls( $block );

  if( empty($fields) ) return $attributes;

  foreach( $fields as $field ) {

    if( ! is_array($field) ) continue;
    
    /**
     * For gutenberg we must specify the type of data saved (string, array, int... etc)
     * 
     * This type used to be different according to the controls, but that's not longer the case. It will 
     * now always be passed as a string (if the control expect an array or an object it will be passed as a
     * JSON string)
     * 
     * In new controls, the get_builder_args() is now used to format the field data we enqueue
     * 
     * @see ./enqueue.php
     * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#type-validation
     */
    $field_args = $block['legacy_controls'] === true 
      ? $plugin->get_builder_args( $field, 'gutenberg', $block_id )
      : array_merge( 
          $field, 
          [ 'type' => 'string' ] 
        );

    if( empty($field_args) ) continue;

    $attributes[ $field['name'] ] = $field_args;
  }

  return $attributes;
}
