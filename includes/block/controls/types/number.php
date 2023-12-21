<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Number extends Base {

  public string $type = 'number';


  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($value, array $args) : array {
    return [
      'type'  => 'number',
      'value' => $value
    ];
  }

  function get_value($value, array $args, string $context) {
    return (int) $value;
  }

}
