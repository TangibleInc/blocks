<?php

defined('ABSPATH') or die();

$plugin->add_inline_dynamic_block_data = function() use ($plugin) {

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->gutenberg_dynamic_config;

  $config['conditions'] = $plugin->block_visibility_conditions;
  $config['controls'] = $plugin->custom_controls;

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

};

/**
 * Called at the end of $template_system->enqueue_gutenberg_template_editor()
 * @see /vendor/tangible/template-system/system/integrations/gutenberg/enqueue.php
 */
add_action('tangible_enqueue_gutenberg_template_editor',
  $plugin->add_inline_dynamic_block_data
);
