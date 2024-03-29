<?php

namespace Tangible\Blocks\Integrations\Gutenberg\Dynamic;

defined('ABSPATH') or die();

// Common config for all generated blocks
$plugin->gutenberg_dynamic_config = [
  'package' => 'tangible',
  'icon'    => 'embed-generic',
  'category'=> 'common',

  /**
   * Script handle used in
   */
  'handle'  => 'tangible-blocks-gutenberg-integration',
];

require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/render.php';
require_once __DIR__ . '/attributes.php';

/**
 * Register dynamic block server-side render
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/creating-dynamic-blocks/
 */
$plugin->register_dynamic_blocks = function() use($plugin) {

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->gutenberg_dynamic_config;

  foreach( $blocks as $block ) {

    /**
     * Block type - Unique name with namespace to identify this block
     *
     * Using universal ID to support import/export across sites. For backward
     * compatibility, fall back to using post slug for blocks without universal ID.
     *
     * The block type must be the same on frontend.
     * @see /assets/src/gutenberg-template-editor/blocks/dynamic/create.js
     */
    $block_type = $config['package'] . '/' . (
      !empty($block['universal_id'])
        ? 'block-' . $block['universal_id']
        : $block['name']
    );

    register_block_type(
      $block_type,
      [
        'attributes'      => format_attributes( $block ),
        'render_callback' => __NAMESPACE__ . '\render',
        'apiVersion'      => 2,
        'editor_script'   => $config['handle'],
      ]
    );
  }
};

/**
 * @see /vendor/tangible/template-system/system/integrations/gutenberg/blocks.php
 */
add_action('init', $plugin->register_dynamic_blocks, 11);
