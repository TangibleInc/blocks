<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class DatePicker extends Base {

  public string $type = 'date_picker';

  function get_value($formated_value, array $args, string $context) {
    return isset($args['format'])
      ? tangible_date($formated_value)->format($args['format'])
      : esc_html($formated_value);
  }

}
