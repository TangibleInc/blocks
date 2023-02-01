<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Editor extends Base {

  public string $type = 'wysiwyg';

  function get_value($formated_value, array $args, string $context) {
    return wp_kses_post($formated_value);
  }

}
