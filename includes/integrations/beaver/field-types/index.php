<?php

add_filter('fl_builder_custom_fields', function( $fields ) use ( $plugin ) {

  foreach($plugin->custom_controls as $type => $config) {
    $fields[ $config['prefixed_type'] ] = __DIR__.'/tangible-blocks-control.php';
  }

  return $fields;
});
