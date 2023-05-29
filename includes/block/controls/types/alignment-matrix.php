<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class AlignmentMatrix extends BaseList {

  public string $type = 'alignment_matrix';

  function get_value($value, array $args, string $context) {
    return esc_html( $value );
  }

}
