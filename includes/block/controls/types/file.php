<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class File extends Base {

  public string $type = 'file';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
