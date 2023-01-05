<?php

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base;

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/background-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#gradient-field
 */

$plugin->register_legacy_control('gradient', [
  'elementor'       => $plugin->get_elementor_group_control_type('Background'),
  'beaver-builder'  => 'gradient',
  'gutenberg'       => 'string',
])
  ->context(['template', 'style'])
  ->elementor(function($field, $type) {
    return [
      'label'     => $field['label'],
      'type'      => $type,
      'types'     => ['gradient'],
      'selector'  => false,
      'separator' => 'before',
      'include'   => [
        'color', 'color_stop', 'color_b', 'color_b_stop',
        'gradient_type', 'gradient_angle', 'gradient_position', 'background'
      ],
    ];
  })
  ->beaver_builder(function($field, $type) {
    return [
      'label'   => $field['label'],
      'type'    => $type,
    ];
  })
  ->gutenberg(function($field, $type) {
    return [
      'type'    => $type,
    ];
  })
  ->filter_value(function($value, $builder, $field, $settings) use($plugin) {

    if ( $builder === 'gutenberg' ) return $value;

    if ( $builder === 'elementor' ) {

      $name = Base::$control_prefix . $field['name'];

      $color = $settings[ $name . '_color'];
      $color_b = $settings[ $name . '_color_b'];

      // Needed to use Elementor global colors
      $color = $plugin->get_elementor_control_value( $color, $field['name'] . '_color', $settings );
      $color_b = $plugin->get_elementor_control_value( $color_b, $field['name'] . '_color_b', $settings );
      
      // Elementor uses an hex value with 8 digit to handle opacity, but it's not supported by $html->sass()
      $color = $plugin->hex_to_rgba( $color );
      $color_b = $plugin->hex_to_rgba( $color_b );
      
      $value = [
        'type'    => $settings[ $name . '_gradient_type'],
        'angle'   => (string) $settings[ $name . '_gradient_angle']['size'],
        'position'=> $settings[ $name . '_gradient_position'],
        'colors'  => [ $color, $color_b ],
        'stops'   => [
          (string) $settings[ $name . '_color_stop']['size'],
          (string) $settings[ $name . '_color_b_stop']['size']
        ]
      ];
    }

    if( empty($value) && $builder === 'beaver-builder' ) {
      $value = [
        'type'    => 'linear',
        'angle'   => '90',
        'position'=> 'top',
        'colors'  => [ 'rgba(0,0,0,0)', 'rgba(0,0,0,0)' ],
        'stops'   => [ '0', '100' ]
      ];
    }

    return FLBuilderColor::gradient( $value );
  })
  ->legacy_render(function($value) use($plugin) {

    return $plugin->is_valid_gradient($value) 
      ? esc_html($value) 
      : ''
    ;
  });

