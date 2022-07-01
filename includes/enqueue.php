<?php
// local $plugin

// Enqueue frontend styles and scripts

add_action('wp_enqueue_scripts', function() use ($plugin) {

  $url = $plugin->url;
  $version = $plugin->version;

/*
  wp_enqueue_style(
    'tangible-blocks',
    $url . 'assets/build/tangible-blocks.min.css',
    [],
    $version
  );

  wp_enqueue_script(
    'tangible-blocks',
    $url . 'assets/build/tangible-blocks.min.js',
    ['jquery'],
    $version
  );
*/

});
