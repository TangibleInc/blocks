<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ButtonGroup extends Base {

  public string $type = 'button_group';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
