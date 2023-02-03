<?php

defined('ABSPATH') or die();

/**
 * Register legacy custom controls as beaver builder fields
 * 
 * @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-14-create-custom-fields/
 */
add_filter('fl_builder_custom_fields', function($beaver_builder_fields) use ($plugin) {

  foreach( $plugin->get_legacy_custom_control() as $type => $control ) {
    
    $html = __DIR__ . '/tangible-blocks-control.php';
    $type = $plugin->custom_legacy_control_prefix . $type;
    
    $beaver_builder_fields[ $type ] = $html;
  }
  
  return $beaver_builder_fields;
}, 5);
