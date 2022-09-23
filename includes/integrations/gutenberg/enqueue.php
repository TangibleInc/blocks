<?php

defined('ABSPATH') or die();

/**
 * Called at the end of $template_system->enqueue_gutenberg_template_editor()
 * @see /vendor/tangible/template-system/system/integrations/gutenberg/enqueue.php
 */
add_action('tangible_enqueue_gutenberg_template_editor', function() use ($plugin) {

  wp_enqueue_script(
    $plugin->gutenberg_dynamic_config['handle'], // See ./index.php
    $plugin->url . 'assets/build/gutenberg-integration.min.js',
    ['tangible-gutenberg-template-editor'],
    $plugin->version
  );
  
  wp_enqueue_style(
    $plugin->gutenberg_dynamic_config['handle'],
    $plugin->url . 'assets/build/gutenberg-integration.min.css',
    ['wp-edit-blocks'],
    $plugin->version
  );


  // Add inline dynamic block data

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->gutenberg_dynamic_config;

  $config['conditions'] = $plugin->block_visibility_conditions;
  $config['controls'] = $plugin->custom_controls;

  $config['current_post_id'] = get_the_ID();

  wp_add_inline_script(
    $config['handle'],
    'window.Tangible = window.Tangible || {}; window.Tangible.blocks = ' . json_encode($blocks),
    'before'
  );

  wp_add_inline_script(
    $config['handle'],
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );

  /**
   * Enqueue data from custom controls
   *
   * @see includes/templates/fields/custom/*
   */
  foreach( $plugin->custom_controls as $type => $control ) {
    $control = $plugin->get_control( $type );
    $control->enqueue_callback( $config['handle'], 'gutenberg' );
  }

});
