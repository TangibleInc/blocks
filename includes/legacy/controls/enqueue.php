<?php 

defined('ABSPATH') or die();

/**
 * Get the data for each legacy control, and enqueue dependencies if any
 */
$plugin->enqueue_legacy_controls_data = function(string $handle, string $builder) use($plugin) {

  foreach( $plugin->get_legacy_controls() as $control ) {
    $control->enqueue_callback( $handle, $builder ); 
  }

};
  