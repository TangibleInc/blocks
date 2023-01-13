<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Gradient extends Base {

  public string $type = 'gradient';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
