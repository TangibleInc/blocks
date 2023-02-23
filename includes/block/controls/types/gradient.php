<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Gradient extends Base {

  public string $type = 'gradient';

  function get_value($value, array $args, string $context) {

    if( is_string($value) ) $value = \json_decode($value);

    return [
      'value' => esc_html($value->stringValue ?? ''),
      'type'  => esc_html($value->type ?? ''),
      'angle' => (int) ($value->angle ?? 0),
      'shape' => esc_html($value->shape ?? ''),
      'colors'=> esc_html( implode(',', (array) ($value->colors ?? [])) ),
    ];
  }

}
