<?php

defined('ABSPATH') or die();

/**
 * Template controls
 *
 * Register and render control types in supported page builders
 */

$plugin->controls = [];
$plugin->custom_controls = [];

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/format.php';
require_once __DIR__ . '/register.php';

require_once __DIR__ . '/types/index.php';
require_once __DIR__ . '/template/index.php';
require_once __DIR__ . '/utils/index.php';

$plugin->get_control = function($type) use($plugin) {
  
  if( ! is_string($type) ) return false;

  if( isset($plugin->controls[ $type ]) ) {
    return $plugin->controls[ $type ];
  }  

  /**
   * If control type does not exists, we will attempt to use a legacy control instead 
   * This allows us to slowly migrate old controls into the new system
   * 
   * @see see ../legacy/controls
   */
  return isset($plugin->get_legacy_control)
    ? $plugin->get_legacy_control( $type )
    : false
  ;
};

$plugin->get_custom_controls = function() use($plugin) {
  
  if( ! isset($plugin->get_legacy_custom_control) ) {
    return $plugin->custom_controls;
  }

  return array_merge(
    $plugin->custom_controls,
    $plugin->get_legacy_custom_control()
  );
};

do_action( 'tangible_template_controls_registered' );
