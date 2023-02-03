<?php

/**
 * @see /vendor/tangible/template-system/system/integrations/beaver/enqueue.php
 */
add_action('tangible_enqueue_beaver_template_editor', function() use ($plugin, $fields) {

  $handle = $plugin->beaver_dynamic_config['handle'];

  wp_enqueue_script(
    $handle,
    $plugin->url . 'assets/build/beaver-integration.min.js',
    ['jquery', 'wp-element', 'tangible-ajax', 'tangible-select'],
    $plugin->version
  );

  wp_enqueue_style(
    $handle,
    $plugin->url . 'assets/build/beaver-integration.min.css',
    [],
    $plugin->version
  );

  $fields->enqueue();

  $config = $plugin->beaver_dynamic_config;
  $config['visibility']['conditions'] = $plugin->block_visibility_conditions;
  $config['controls'] = $plugin->enqueue_controls_data( $handle, 'beaver-builder' );
  $config['legacy_controls'] = $plugin->get_legacy_custom_control();

  wp_add_inline_script(
    $handle,
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );

});
