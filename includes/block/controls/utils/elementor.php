<?php

use Elementor\Plugin as Elementor;
use Tangible\Blocks\Integrations\Elementor\Dynamic\Base;

defined('ABSPATH') or die();

/**
 * When using a global color in Elementor, the value of the global will not be passed in $value,
 * we have to get it from the __globals__ array
 */
$plugin->get_elementor_control_value = function($value, $name, $settings) use( $plugin ) {
  
  $setting_name = Base::$control_prefix . $name;

  $global_key = isset($settings['__globals__'][ $setting_name ]) 
    ? $settings['__globals__'][ $setting_name ]
    : false
  ;

  if( empty($global_key) ) return $value;

  // @see get_selector_global_value() in /elementor/core/files/css/base.php

  $data = Elementor::$instance->data_manager->run( $global_key );
  
  return ! empty($data['value']) ? $data['value'] : $value;
};


