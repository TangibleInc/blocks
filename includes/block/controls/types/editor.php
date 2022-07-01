<?php

defined('ABSPATH') or die();

$plugin->register_control('editor', [
  'elementor'       => $plugin->get_elementor_control_type('WYSIWYG'),
  'beaver-builder'  => 'editor',
  'gutenberg'       => 'string',
])
  ->context(['template']) // editor should not be used in style and script
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
      'label'   => $field['label'],
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->render(function($value, $block) {
    return wp_kses_post($value);
  });
