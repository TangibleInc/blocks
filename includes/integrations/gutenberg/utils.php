<?php

namespace Tangible\Blocks\Integrations\Gutenberg\Dynamic;

defined('ABSPATH') or die();

/**
 * Attributes will contain only the information gutenberg needs to know for state management.
 *
 * Information needed for generating the settings fields will be passed in window.Tangible.blocks
 */
function to_attributes( array $data ) {

  $plugin = tangible_blocks();

  $attributes = [
    'config' => array_values([
      'type'    => 'array',
      'default' => $data
    ]),
    'name' => [
      'type'    => 'string',
      'default' => $data['name'],
    ],
    'block_id' => [
      'type' => 'string'
    ],
    // Post ID as "content ID" for backward compatibility
    'content_id' => [
      'type'    => 'integer',
      'default' => (int) $data['content_id'],
    ],
    /**
     * Universal ID - Unique and immutable across sites
     * @see /includes/template/universal-id/index.php
     */
    'universal_id' => [
      'type'    => 'string',
      'default' => isset($data['universal_id'])
        ? $data['universal_id']
        : ''
      ,
    ],
    /**
     * Current post ID inside Gutenberg
     */
    'current_post_id' => [
      'type'    => 'number',
      'default' => 0,
    ],
  ];

  $block_id = $plugin->get_block_id( $data );
  $fields   = $plugin->get_block_controls( $data );

  if( empty($fields) ) return $attributes;

  foreach( $fields as $field ) {

    if( ! is_array($field) ) continue;
    
    $field_args = $plugin->get_builder_args( $field, 'gutenberg', $block_id );

    if( $field_args === false ) continue;

    $attributes[ $field['name'] ] = $field_args;
  }

  return $attributes;
}
