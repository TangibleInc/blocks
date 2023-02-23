<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ButtonGroup extends Base {

  public string $type = 'button_group';
  
  function get_value($value, array $args, string $context) {

    $choices = array_keys($args['choices'] ?? []);
    $value = esc_html($value);
    
    return in_array($value, $choices) ? $value : '';
  }

}
