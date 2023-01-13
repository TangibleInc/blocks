<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Repeater extends Base {

  public string $type = 'repeater';

  function get_control_args(string $builder, array $args) : array {

    $args = parent::get_control_args($builder, $args);

    /**
     * TODO: Look how to get directly the right structure from the L&L template
     */
    $args['data']['fields'] = $args['data']['controls'] ?? [];
    unset($args['data']['controls']);

    return $args;
  }

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
