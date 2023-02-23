<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class ColorPicker extends Base {

  public string $type = 'color_picker';

  /**
   * @see https://gist.github.com/olmokramer/82ccce673f86db7cda5e
   */
  function is_valid_color(string $color) {
    return preg_match(
      '/^(#[0-9a-f]{3}|#(?:[0-9a-f]{2}){2,4}|(rgb|hsl)a?\((-?\d+%?[,\s]+){2,3}\s*[\d\.]+%?\))$/', 
      strtolower($color)
    );
  }

  function get_value($value, array $args, string $context) {
    return $this->is_valid_color($value) ? $value : '';
  }

}
