<?php

defined('ABSPATH') or die();

/**
 * Register our custom fields in Elementor
 * 
 * @see /includes/templates/controls/fields/custom/
 */

add_action('elementor/controls/register', function($controls_manager) use($plugin) {

  require_once __DIR__ . '/base.php';

  foreach($plugin->get_custom_controls() as $type => $config) {

    $control = $plugin->register_elementor_control($config);
    
    $controls_manager->register( $control );
  }

}, 10, 1);
