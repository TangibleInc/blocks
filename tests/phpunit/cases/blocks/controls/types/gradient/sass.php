<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Gradient_Picker_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_gradient_control_sass_variable_conversion_data 
   */
  function test_gradient_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('gradient', $expected, $value, $args);
  }

  function _test_gradient_control_sass_variable_conversion_data() {
    return [
      'Simple gradient' => [ 
        '("value":radial-gradient(ellipse, rgba(0,255,255,1) 0%, rgba(0,255,255,1) 100%),"type":"radial","angle":45,"shape":"ellipse","colors":"rgba(0,255,255,1),rgba(0,255,255,1)")',
        '{
          "type"        : "radial",
          "angle"       : 45,
          "shape"       : "ellipse",
          "colors"      : [ "rgba(0,255,255,1)","rgba(0,255,255,1)" ],
          "stringValue" : "radial-gradient(ellipse, rgba(0,255,255,1) 0%, rgba(0,255,255,1) 100%)"
        }',
        [ 'type'  => 'gradient' ] 
      ],
    ];
  }

}
