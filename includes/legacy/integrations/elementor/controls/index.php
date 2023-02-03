<?php

defined('ABSPATH') or die();

/**
 * Register legacy custom controls in Elementor
 * 
 * @see /includes/templates/controls/fields/custom/
 */

add_action('elementor/controls/register', function($controls_manager) use($plugin) {

  require_once __DIR__ . '/base.php';

  foreach( $plugin->get_legacy_custom_control() as $type => $config ) {
    
    $control = $plugin->get_legacy_control( $type );

    if( empty($control) ) continue;

    $elementor_control = $plugin->register_legacy_elementor_control( $control, $config );
    $controls_manager->register( $elementor_control );
  }

}, 10, 1);
