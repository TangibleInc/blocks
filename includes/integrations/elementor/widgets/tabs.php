<?php

defined('ABSPATH') or die();

/**
 * Get only the blocks where we needs to do the workaround
 */
$plugin->get_blocks_with_custom_tabs = function() use($plugin) {

  $blocks = array_filter($plugin->get_all_blocks(), function($block) {

    foreach( $block['tabs'] as $tab ) {
      if( $tab['name'] === 'default' ) return false;
    }

    return true;
  });

  return array_map(function($block) use($plugin) {
    return $plugin->get_block_id( $block );
  }, $blocks);
};
