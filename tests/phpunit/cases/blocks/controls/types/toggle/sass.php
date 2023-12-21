<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Toggle_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_switch_control_sass_variable_conversion_data 
   */
  function test_switch_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('switch', $expected, $value, $args);
  }

  function _test_switch_control_sass_variable_conversion_data() {
    return [

      'Simple test with string' => [ 
        'yes',
        'yes',
        ['type' => 'switch' ] 
      ],

      'Simple test with string' => [ 
        'no',
        'no',
        ['type' => 'switch' ] 
      ],

      'Simple test with integer' => [ 
        '0',
        0,
        ['type' => 'switch' ] 
      ],

      'Simple test with false' => [ 
        '',
        false,
        ['type' => 'switch' ] 
      ],
      
      // TODO: Add new test when validation will be implemented in control render
      
    ];
  }

}
