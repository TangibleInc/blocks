<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Dimensions extends Base {

  public string $type = 'dimensions';
  
  function get_units(array $args) : array {
    return $args['units'] ?? ['px'];
  }

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($value, array $args) : array {
    return [
      'type'  => 'map',
      'value' => [
        'value' => [ 
          'value' => $value['value'], 
          'type'  => 'dimension'
        ],
        'top' => [ 
          'value' => $value['top'], 
          'type'  => 'number'
        ],
        'right' => [ 
          'value' => $value['right'], 
          'type'  => 'number'
        ],
        'bottom' => [ 
          'value' => $value['bottom'], 
          'type'  => 'number'
        ],
        'left' => [ 
          'value' => $value['left'], 
          'type'  => 'number'
        ],
        'unit' => [ 
          'value' => $value['unit'], 
          'type'  => 'unit'
        ],
      ],
    ];
  }

  function get_value($formated_value, array $args, string $context) {

    if( is_string($formated_value) ) {
      $formated_value = json_decode($formated_value);
    }

    $fields = [
      'top'   => (int) ($formated_value->top ?? 0),
      'right' => (int) ($formated_value->right ?? 0),
      'bottom'=> (int) ($formated_value->bottom ?? 0),
      'left'  => (int) ($formated_value->left ?? 0),
      'unit'  => in_array($formated_value->unit ?? '', $this->get_units($args)) 
        ? $formated_value->unit
        : 'px'
    ];

    // Default value
    $fields['value'] = implode( ' ', [
      $fields['top']    . $fields['unit'],
      $fields['right']  . $fields['unit'],
      $fields['bottom'] . $fields['unit'],
      $fields['left']   . $fields['unit'],
    ]);

    return $fields;
  }

}
