<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

$plugin->register_control = function(string $type, Base $control) use($plugin) {
  
  if( isset($plugin->controls[ $type ]) ) return false;
  
  $plugin->controls[ $type ] = $control;

  return $control;
};
