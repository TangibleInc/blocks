<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

/**
 * Base for controls that support the choices attribute
 */

class BaseList extends Base {

  function get_allowed_choices(array $args) : array {

    $choices = $args['choices'] ?? [];

    return $this->has_categories($choices)
      ? $this->get_categories_choices($choices)
      : array_keys($choices);
  }

  function has_categories(array $choices) : bool {
    return isset($choices[0]) && is_array($choices);
  }

  function has_multiple_values(array $args) : bool {
    return isset($args['multiple']) && $args['multiple'] === 'true';
  }

  function get_categories_choices(array $categories) : array {
    return array_reduce(
      $categories,
      function($choices, $args) {
        return array_merge(
          $this->get_allowed_choices($args),
          $choices
        );
      }, 
      []
    );
  }

  function get_valid_values(array $values, array $args) : array {
    return array_filter(
      $values,
      function($value) use($args) {
        return in_array( $value, $this->get_allowed_choices($args) );
      }
    );
  }
}
