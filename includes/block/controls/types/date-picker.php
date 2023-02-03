<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class DatePicker extends Base {

  public string $type = 'date_picker';

  function get_value($value, array $args, string $context) {
    return isset($args['format'])
      ? tangible_date($value)->format($args['format'])
      : esc_html($value);
  }

}
