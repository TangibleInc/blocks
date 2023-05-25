<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Checkbox extends Base {

  public string $type = 'checkbox';

  function get_value($value, array $args, string $context) {
    return in_array($value, ['1', 'true']) ? '1' : '0';
  }

}
