<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Color_Picker_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_color_picker_control_sass_variable_conversion_data 
   */
  function test_color_picker_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('color_picker', $expected, $value, $args);
  }

  function _test_color_picker_control_sass_variable_conversion_data() {
    return [

      'Test with text' => [ 
        '',
        'test text',
        [ 'type' => 'color_picker' ]
      ],

      'Test with hexadecimal color' => [ 
        '#FFFFFF',
        '#FFFFFF',
        [ 'type' => 'color_picker' ]
      ],

      'Test with hexadecimal color with opacity' => [ 
        '#FFFFFFFF',
        '#FFFFFFFF',
        [ 'type' => 'color_picker' ]
      ],

      'Test with rgb color' => [ 
        'rgb(0,0,0)',
        'rgb(0,0,0)',
        [ 'type' => 'color_picker' ]
      ],

      'Test with wrong rgb color' => [ 
        '',
        'rgb(0,0,0,0)',
        [ 'type' => 'color_picker' ]
      ],

      'Test with rgba color' => [ 
        'rgba(0,0,0,0)',
        'rgba(0,0,0,0)',
        [ 'type' => 'color_picker' ]
      ],

      'Test with wrong rgba color' => [ 
        '',
        'rgba(0,0,0)',
        [ 'type' => 'color_picker' ]
      ],

      'Test with hsl color' => [ 
        'hsl(0,100%,50%)',
        'hsl(0,100%,50%)',
        [ 'type' => 'color_picker' ]
      ],

      'Test with wrong hsl color' => [ 
        '',
        'hsl(0,100%,50%,0)',
        [ 'type' => 'color_picker' ]
      ],

      // We should add support for hsla

    ];
  }

}
