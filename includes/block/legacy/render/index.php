<?php

defined('ABSPATH') or die();

/**
 * Render control variable using deprecated synatax: {{ control-name }}
 */

require_once __DIR__ . '/render.php';
require_once __DIR__ . '/style.php';
require_once __DIR__ . '/script.php';

$plugin->init_legacy_render = function($post, $data) use($plugin) {

  $plugin->legacy_render_post_id = $post->ID;
  $plugin->legacy_render_data    = $data;

  add_filter( 'get_post_metadata', $plugin->legacy_style_render, 10, 4 ); 
  add_filter( 'get_post_metadata', $plugin->legacy_script_render, 10, 4 ); 

  return $plugin->legacy_render( $post->post_content, 'template' );
};

$plugin->reset_legacy_render = function() use($plugin) {

  $plugin->legacy_render_post_id = false;
  $plugin->legacy_render_data    = false;
  
  remove_filter( 'get_post_metadata', $plugin->legacy_style_render, 10 ); 
  remove_filter( 'get_post_metadata', $plugin->legacy_script_render, 10 ); 

};

/**
 * We don't need this function anymore, keep to avoid error if called
 */
$plugin->replace_control_values = function($content, $data, $context) {
  return $content;
};
