<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Number extends Base {

  public string $type = 'number';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
