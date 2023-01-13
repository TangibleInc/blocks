<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Checkbox extends Base {

  public string $type = 'checkbox';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
