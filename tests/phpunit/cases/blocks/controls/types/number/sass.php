<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Number_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_number_control_sass_variable_conversion_data 
   */
  function test_number_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('number', $expected, $value, $args);
  }

  function _test_number_control_sass_variable_conversion_data() {
    return [

      'Simple test with integer' => [ 
        '8',
        8,
        ['type' => 'number' ] 
      ],

      'Simple test with numeral string' => [ 
        '8',
        '8',
        ['type' => 'number' ] 
      ],

      'Simple test with string' => [ 
        '0',
        'test',
        ['type' => 'number' ] 
      ],

      'Simple test with float' => [ 
        '8',
        8.5,
        ['type' => 'number' ] 
      ],

      
    ];
  }

}
