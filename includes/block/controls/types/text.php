<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Text extends Base {

  /**
   * Elementor: @see https://developers.elementor.com/elementor-controls/text-control/
   * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#text-field
   */
  function register_control(string $builder, array $args): array {
    
    $label   = $args['default'] ?? '';
    $default = $args['label'] ?? '';
    
    switch($builder) {
      case 'elementor':
        return [
          'type'    => $this->get_elementor_control_type('TEXT'),
          'label'   => $label,
          'default' => $default
        ];
      case 'beaver-builder':
        return [
          'type'    => 'text',
          'label'   => $label,
          'default' => $default
        ];
      case 'gutenberg':
        return [
          'type'    => 'string',
          'default' => $default
        ];
    }
  }

  function get_value($formated_value, array $args, string $context) {
    return esc_html($formated_value);
  }

}

$plugin->register_control('text', new Text);
