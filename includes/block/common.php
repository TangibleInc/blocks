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
 * Universal ID - Unique and immutable across sites
 * @see /includes/template/universal-id/index.php
 */
$plugin->get_block_id = function($args) {

  if( is_array($args) ) {
    return ! empty($args['universal_id'])
      ? $args['universal_id']
      : $args['content_id']
    ;
  }
  
  else if( is_numeric($args) ) {
    
    $post_id = $args;
    $universal_id = get_post_field('universal_id', $post_id);

    return ! empty($universal_id) ? $universal_id : $post_id; 
  }
  
  return false;
};

/**
 * Companion plugins
 */
$plugin->has_editor = function_exists('tangible_blocks_editor');
$plugin->has_pro = function_exists('tangible_blocks_pro')
  || function_exists('tangible_loops_and_logic_pro')
;
