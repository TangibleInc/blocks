<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Select extends Base {

  public string $type = 'select';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
