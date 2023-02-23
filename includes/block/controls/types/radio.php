<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Radio extends BaseList {

  public string $type = 'radio';

  function get_value($value, array $args, string $context) {
    
    $choices = $this->get_allowed_choices($args);
    
    return in_array($value, $choices) ? esc_html($value) : '';
  }

}
