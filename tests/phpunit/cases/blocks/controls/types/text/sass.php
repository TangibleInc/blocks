<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Text_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_text_control_sass_variable_conversion_data 
   */
  function test_text_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('text', $expected, $value, $args);
  }

  function _test_text_control_sass_variable_conversion_data() {
    return [

      'Simple test with string' => [ 
        'test with string value',
        'test with string value',
        ['type' => 'text' ] 
      ],

      'Simple test with integer' => [ 
        '0',
        0,
        ['type' => 'text' ] 
      ],

      'Simple test with false' => [ 
        '',
        false,
        ['type' => 'text' ] 
      ],
      
    ];
  }

}
