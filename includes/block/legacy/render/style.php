<?php

defined('ABSPATH') or die();

$plugin->legacy_style_render = function($value, $post_id, $meta_key, $single) use($plugin) {

  if(  $meta_key !== 'style' 
    || $post_id !== $plugin->legacy_render_post_id 
    || $single !== true 
  ) return $value;

  remove_filter( 'get_post_metadata', $plugin->legacy_style_render, 10 );

  $style = get_post_meta( $post_id, 'style', true );

  return $plugin->legacy_render( $style, 'style' );
};
