<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

/**
 * We might want to deprecate this one
 * The feature has been replaced by dynamic values in tangible-fields (not in blocks yet however)
 */
class TextSuggestion extends Base {

  public string $type = 'text_suggestion';

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}
