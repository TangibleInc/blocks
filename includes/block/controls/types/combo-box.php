<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ComboBox extends Base {

  public string $type = 'combo_box';

  function get_value($formated_value, array $args, string $context) {

    if( is_string($formated_value) ) return esc_html($formated_value);

    return is_object($formated_value) && ! empty($formated_value->value)
      ? esc_html($formated_value->value)
      : ''
    ;
  }

}
