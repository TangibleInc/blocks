<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Text extends Base {

  public string $type = 'text';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
