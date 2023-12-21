<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Toggle extends Base {

  public string $type = 'switch';

  /**
   * TODO: Validate returned value according to $args (eyes/no by default, but can be changed)
   */
  function get_value($value, array $args, string $context) {
    return esc_html($value);
  }

}
