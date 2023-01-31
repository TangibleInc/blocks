<?php 

defined('ABSPATH') or die();

/**
 * Get the data for each control, and enqueue dependencies if any
 */
$plugin->enqueue_controls_data = function(string $handle, string $builder) use($plugin): array {

  /**
   * @see includes/legacy/controls/enqueue.php
   */
  $plugin->enqueue_legacy_controls_data( $handle, $builder );

  return array_reduce(
    $plugin->get_controls(),  
    function($data, $control) use($handle, $plugin, $builder){

      /**
       * Enqueue data from custom controls
       *
       * @see includes/blocks/controls/types/base/
       */
      $control->enqueue( $handle, $builder );
      $type = $control->type;
      
      $data[ $type ] = [
        'type'          => $type,
        'prefixed_type' => $control->get_prefixed_type()
      ];

      return $data;
    }, 
    []
  );
};
