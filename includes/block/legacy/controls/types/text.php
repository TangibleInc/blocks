<?php

defined('ABSPATH') or die();

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/text-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#text-field
 */

$plugin->register_legacy_control('text', [
  'elementor'       => $plugin->get_elementor_control_type('TEXT'),
  'beaver-builder'  => 'text',
  'gutenberg'       => 'string',
])
  ->elementor(function($field, $type) {
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->beaver_builder(function($field, $type) {
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->gutenberg(function($field, $type) {
    return [
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->legacy_render(function($value, $block, $context) {

    // To avoid code injections, value will always be used with commas when in script context

    return $context === 'script'
      ? "'" . esc_html($value) . "'"
      : esc_html($value)
    ;
  });
