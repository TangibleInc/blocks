<?php
/**
 * Common functions for blocks
 * @see /includes/integrations/gutenberg, beaver, elementor
 */

/**
 * Get block post from universal ID or post/content ID for backward compatibility
 */
$plugin->get_block_post_from_settings = function( $settings, $post_type = 'tangible_block' ) {

  if (!empty($settings['universal_id'])) {

    $posts = get_posts([
      'post_type' => $post_type,
      'post_status' => 'publish',
      'posts_per_page' => 1,
      'meta_query' => [
        [
          'key'   => 'universal_id',
          'value' => $settings['universal_id'],
        ]
      ]
    ]);

    if (!empty($posts)) return $posts[0];
  }

  if (!empty($settings['content_id'])) {
    return get_post( (int) $settings['content_id'] );
  }
};

/**
 * Companion plugins
 */
$plugin->has_editor = function_exists('tangible_blocks_editor');
$plugin->has_pro = function_exists('tangible_blocks_pro')
  || function_exists('tangible_loops_and_logic_pro')
;
