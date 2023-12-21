<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Checkbox_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_checkbox_control_sass_variable_conversion_data 
   */
  function test_checkbox_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('checkbox', $expected, $value, $args);
  }

  function _test_checkbox_control_sass_variable_conversion_data() {
    return [

      'Test with text' => [ 
        '0',
        'test text',
        [ 'type' => 'checkbox' ]
      ],

      'Test with 1' => [ 
        '1',
        '1',
        [ 'type' => 'checkbox' ] 
      ],

      'Test with true string' => [ 
        '1',
        'true',
        [ 'type' => 'checkbox' ] 
      ],

      'Test with true' => [ 
        '1',
        true,
        [ 'type' => 'checkbox' ] 
      ],
      
      'Test with 0' => [ 
        '0',
        '0',
        [ 'type' => 'checkbox' ] 
      ],

      'Test with false' => [ 
        '0',
        false,
        [ 'type' => 'checkbox' ] 
      ],

      'Test with false string' => [ 
        '0',
        'false',
        [ 'type' => 'checkbox' ] 
      ],
    ];
  }

}
