<?php
/**
 * @see /vendor/tangible/template-system/system/integrations/beaver/enqueue.php
 */
add_action('tangible_enqueue_beaver_template_editor', function() use ($plugin) {

  $script_name = $plugin->beaver_dynamic_config['handle'];

  wp_enqueue_script(
    $script_name,
    $plugin->url . 'assets/build/beaver-integration.min.js',
    ['jquery', 'wp-element', 'tangible-ajax', 'tangible-select'],
    $plugin->version
  );

  /**
   * Enqueue data from custom controls
   *
   * @see includes/templates/fields/custom/*
   */
  foreach($plugin->custom_controls as $type => $control) {
    $control = $plugin->get_control( $type );
    $control->enqueue_callback( $script_name, 'beaver-builder' );
  }

  $config = $plugin->beaver_dynamic_config;
  $config['visibility']['conditions'] = $plugin->block_visibility_conditions;
  $config['controls'] = $plugin->custom_controls;

  wp_add_inline_script(
    $script_name,
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );

});
