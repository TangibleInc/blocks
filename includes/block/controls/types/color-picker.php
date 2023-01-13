<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ColorPicker extends Base {

  public string $type = 'color_picker';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
