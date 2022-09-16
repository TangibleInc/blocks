<?php

defined('ABSPATH') or die();

$plugin->register_legacy_control('align', [
  'elementor'       => $plugin->get_elementor_control_type('CHOOSE'),
  'beaver-builder'  => 'align',
  'gutenberg'       => 'string',
])
  ->elementor(function($field, $type) {
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'options' => [
        'left' => [
          'title' => __( 'Left', 'tangible-blocks' ),
          'icon' => 'fa fa-align-left',
        ],
        'center' => [
          'title' => __( 'Center', 'tangible-blocks' ),
          'icon' => 'fa fa-align-center',
        ],
        'right' => [
          'title' => __( 'Right', 'tangible-blocks' ),
          'icon' => 'fa fa-align-right',
        ],
      ],
      'default' => isset($field['default']) ? $field['default'] : 'center'
    ];
  })
  ->beaver_builder(function($field, $type){
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : 'center'
    ];
  })
  ->gutenberg(function($field, $type){
    return [
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : 'center'
    ];
  })
  ->render(function($value, $field) {

    $accepted_values = ['left', 'center', 'right'];

    if( isset($field['default']) ) $accepted_values []= $field['default'];
    
    return in_array($value, $accepted_values) ? $value : '';
  });
