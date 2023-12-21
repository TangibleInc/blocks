<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ColorPicker extends Base {

  public string $type = 'color_picker';

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($value, array $args) : array {
    return [
      'type'  => 'color',
      'value' => $value
    ];
  }

  /**
   * @see https://gist.github.com/olmokramer/82ccce673f86db7cda5e
   */
  function is_valid_color(string $color) {
    return static::$plugin->is_valid_color( strtolower($color) );
  }

  function get_value($value, array $args, string $context) {
    return $this->is_valid_color($value) ? $value : '';
  }

}
