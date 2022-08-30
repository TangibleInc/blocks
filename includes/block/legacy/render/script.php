<?php

defined('ABSPATH') or die();

$plugin->legacy_script_render = function($value, $post_id, $meta_key, $single) use($plugin) {

  if(  $meta_key !== 'script' 
    || $post_id !== $plugin->legacy_render_post_id 
    || $single !== true 
  ) return $value;

  remove_filter( 'get_post_metadata', $plugin->legacy_script_render, 10 );

  $script = get_post_meta( $post_id, 'script', true );

  return $plugin->legacy_render( $script, 'script' );
};
