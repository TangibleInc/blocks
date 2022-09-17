<?php

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base;

/**
 * Elementor: @see https://developers.elementor.com/elementor-controls/dimensions-control/
 * BeaverBuilder: @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-10-setting-fields-reference/#dimension-field
 * Gutenberg : @see https://developer.wordpress.org/block-editor/reference-guides/components/box-control/
 */

$plugin->register_legacy_control('dimension', [
  'elementor'       => $plugin->get_elementor_control_type('DIMENSIONS'),
  'beaver-builder'  => 'dimension',
  'gutenberg'       => 'object',
])

  ->elementor(function($field, $type) {

    $units = isset( $field['units'] )
      ? explode(',', str_replace(' ', '', $field['units']))
      : ['px']
    ;

    $default = isset($field['default'])
      ? explode(',', str_replace(' ', '', $field['default']))
      : ['', '', '', '']
    ;

    $is_linked = isset($field['multiple_values']) && $field['multiple_values'] === 'false';

    $args = [
      'label'     => $field['label'],
      'type'      => $type,
      'size_units'=> $units,
      'default'   => [
        'top'       => $default[0],
        'right'     => isset($default[1]) ? $default[1] : $default[0],
        'bottom'    => isset($default[2]) ? $default[2] : $default[0],
        'left'      => isset($default[3]) ? $default[3] : $default[0],
        'unit'      => isset($field['default_unit']) ? $field['default_unit'] : '',
        'isLinked'  => $is_linked,
      ],
    ];

    if( !$is_linked ) return $args;

    $args['allowed_dimensions'] = ['top']; // We only want one value when linked
    $args['description'] = 'Left, right and bottom values will be the same than the top value';
    
    return $args;
  })

  ->beaver_builder(function($field, $type) {

    $units = isset( $field['units'] )
      ? explode(',', str_replace(' ', '', $field['units']))
      : ['px']
    ;

    $default = isset( $field['default'] )
      ? explode(',', str_replace(' ', '', $field['default']))
      : ['', '', '', '']
    ;

    $is_linked = isset($field['multiple_values']) && $field['multiple_values'] === 'false';

    if($is_linked) {
      return [
        'type'        => 'unit',
        'label'       => $field['label'],
        'units'       => $units,
        'default_unit'=> isset($field['default_unit']) ? $field['default_unit'] : '',
        'default'     => $default[0],
      ];
    }

    return [
      'label'   => $field['label'],
      'type'    => $type,
      'units'   => $units,
      'placeholder' => [
        'top'     => $default[0],
        'right'   => isset($default[1]) ? $default[1] : $default[0],
        'bottom'  => isset($default[2]) ? $default[2] : $default[0],
        'left'    => isset($default[3]) ? $default[3] : $default[0],
      ],
      'default_unit' => isset($field['default_unit']) ? $field['default_unit'] : '',
    ];
  })

  ->gutenberg(function($field, $type) {

    $unit = isset($field['default_unit']) ? $field['default_unit'] : 'px';
    $default = isset($field['default'])
      ? explode(',', str_replace(' ', '', $field['default']))
      : [ '0', '0', '0', '0']
    ;
    
    $default_processed = [
      'top'   => isset($default[0]) ? $default[0] : '0',
      'right' => isset($default[1]) ? $default[1] : $default[0],
      'bottom'=> isset($default[2]) ? $default[2] : $default[0],
      'left'  => isset($default[3]) ? $default[3] : $default[0],
      'unit'  => $unit,
    ];

    return [
      'type'    => $type,
      'default' => $default_processed,
    ];
  })
  ->filter_value(function($value, $builder, $field, $settings) {

    $is_linked = isset($field['multiple_values']) && $field['multiple_values'] === 'false';

    if( $builder !== 'beaver-builder' ) {

      if( !$is_linked ) return $value;
      
      $value['right'] = $value['top'];  
      $value['bottom'] = $value['top'];
      $value['left'] = $value['top'];

      return $value;
    }

    // Convert beaver-builder return to data similar than gutenberg and elementor 

    $response = [];

    // Other builders use px by default, we want the same behavior everywhere 
    $response['unit'] = isset($settings->{ $field['name'] . '_unit' }) 
      ? $settings->{ $field['name'] . '_unit' } 
      : (isset($field['default_unit']) ? $field['default_unit'] : 'px')
    ;
    
    $default = isset($field['default'])
      ? explode(',', str_replace(' ', '', $field['default']))
      : ['', '', '', '']
    ;
    
    foreach(['top', 'right', 'bottom', 'left'] as $key => $name) {

      $setting_name = $field['name'] . '_' . $name;

      if( $is_linked ) {
        $response[ $name ] = is_numeric($value) || !isset($default[ $key ]) 
          ? (int) $value
          : $default[ $key ]
        ;
        continue;  
      }

      // Need to check if $default[ $key ] exist because value comes from user
      $response[ $name ] = is_numeric($settings->{ $setting_name }) 
        ? $settings->{ $setting_name }
        : (isset($default[ $key ]) ? $default[ $key ] : $default[ 0 ])
      ;           
    }

    return $response;
  })
  ->sub_values([
    'top',
    'right',
    'bottom',
    'left',
    'unit'
  ])
  ->render_sub_values(function($name, $builder, $field, $settings) {

    if ( empty( $name ) ) return '';

    $units = isset($field['units']) 
      ? explode( ',', $field['units'] ) 
      : ['px']
    ;
    
    if( isset($field['default_unit']) && !in_array($field['default_unit'], $units) ) {
      $units []= $field['default_unit'];
    }
    
    switch($builder) {
      
      case 'elementor':
      case 'gutenberg':  
        
        $values = $builder === 'elementor' 
          ? $settings[ Base::$control_prefix . $field['name'] ]
          : $settings[ $field['name'] ]
        ;

        if ( $name === 'unit' ) {
          return in_array($values['unit'], $units) 
            ? $values['unit']
            : 'px'
          ;
        }
        
        $is_linked = isset($field['multiple_values']) && $field['multiple_values'] === 'false';

        return $is_linked 
          ? (int) $values[ 'top' ]
          : (int) $values[ $name ]
        ;
        break;
      
      case 'beaver-builder':

        $setting_name = $field['name'] . '_' . $name;

        if ( $name === 'unit' ) {
          $unit = isset($settings->{ $setting_name }) && in_array($settings->{ $setting_name }, $units) 
            ? $settings->{ $setting_name }
            : (isset($field['default_unit']) ? $field['default_unit'] : 'px')
          ;
          
          return in_array($unit, $units) 
            ? $unit
            : 'px'
          ;  
        }

        $is_linked = isset($field['multiple_values']) && $field['multiple_values'] === 'false';
        if( $is_linked ) return $settings->{ $field['name'] };

        // For top, right, bottom and left field
        
        $value = $settings->{ $setting_name } !== ''  
          ? $settings->{ $field['name'] . '_' . $name }
          : false
        ;

        if( $value !== false ) return (int) $value;

        $default = isset($field['default'])
          ? explode(',', str_replace(' ', '', $field['default']))
          : ['', '', '', '']
        ;

        switch($name) {

          case 'top':     return isset($default[0]) ? (int) $default[0] : '';
          case 'right':   return isset($default[1]) ? (int) $default[1] : (int) $default[0];
          case 'bottom':  return isset($default[2]) ? (int) $default[2] : (int) $default[0];
          case 'left':    return isset($default[3]) ? (int) $default[3] : (int) $default[0];

          default: '';
        }
        break;

    }
  })
  ->render(function($value, $field) {
    ;
    $units = isset($field['units']) 
      ? explode( ',', $field['units'] )
      : ['px']
    ;

    if( isset($field['default_unit']) && !in_array($field['default_unit'], $units) ) {
      $units []= $field['default_unit'];
    }

    $unit = in_array($value['unit'], $units) 
      ? $value['unit']
      : 'px'
    ;

    $answer = [
      intval($value['top']) . $unit,
      intval($value['right']) . $unit,
      intval($value['bottom']) . $unit,
      intval($value['left']) . $unit
    ];

    return implode( ' ', $answer );
  });
