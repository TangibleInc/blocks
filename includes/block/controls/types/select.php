<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Select extends Base {

  public string $type = 'select';

  function get_value($value, array $args, string $context) {

    if( ! is_array($value) )return esc_html($value);

    return array_map(
      function($item) { 
        return [ 
          'id'    => $item,
          'value' => $item // Default value
        ];
      },
      $value
    );
  }

}
