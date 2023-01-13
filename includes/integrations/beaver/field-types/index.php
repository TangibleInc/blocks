<?php

defined('ABSPATH') or die();

add_filter('fl_builder_custom_fields', function( $beaver_builder_fields ) use ( $plugin ) {

  foreach( $plugin->get_controls() as $type => $control ) {
    
    $prefixed_type = $control->get_prefixed_type();

    $html = __DIR__ . '/tangible-blocks-control.php';

    $beaver_builder_fields[ $prefixed_type ] = $html;
  }

  return $beaver_builder_fields;
});
