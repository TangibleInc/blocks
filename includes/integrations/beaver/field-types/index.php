<?php

defined('ABSPATH') or die();

/**
 * Register our fields into beaver-builder
 * 
 * @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-14-create-custom-fields/
 */
add_filter('fl_builder_custom_fields', function( $beaver_builder_fields ) use ( $plugin ) {

  foreach( $plugin->get_controls() as $type => $control ) {
    
    $prefixed_type = $control->get_prefixed_type();

    $html = __DIR__ . '/tangible-blocks-control.php';

    $beaver_builder_fields[ $prefixed_type ] = $html;
  }

  return $beaver_builder_fields;
});
