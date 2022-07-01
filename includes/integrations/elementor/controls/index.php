<?php

/**
 * Register our custom fields in Elementor
 * 
 * @see /includes/templates/controls/fields/custom/
 */

add_action('elementor/controls/controls_registered', function($controls_manager) use($plugin) {

  require_once __DIR__ . '/base.php';

  foreach($plugin->custom_controls as $type => $config) {

    $control = $plugin->register_elementor_control($config);
    
    $controls_manager->register_control( $config['prefixed_type'], $control );
  }

}, 10, 1);
