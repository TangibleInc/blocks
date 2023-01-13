<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

$plugin->register_control = function(Base $control) use($plugin) {
  
  if( isset($plugin->controls[ $control->type ]) ) return false;
  
  $plugin->controls[ $control->type ] = $control;

  return $control;
};
