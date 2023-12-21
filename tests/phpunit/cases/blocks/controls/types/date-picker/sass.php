<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Date_Picker_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_date_picker_control_sass_variable_conversion_data 
   */
  function test_date_picker_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('date_picker', $expected, $value, $args);
  }

  function _test_date_picker_control_sass_variable_conversion_data() {
    return [

      'Simple date' => [ 
        '2023-12-20',
        '2023-12-20',
        ['type' => 'date_picker' ] 
      ],

      'Date with format' => [ 
        '2023',
        '2023-12-20',
        ['type' => 'date_picker', 'format' => 'Y' ] 
      ],
      
    ];
  }

}
