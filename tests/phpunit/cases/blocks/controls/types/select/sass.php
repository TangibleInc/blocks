<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Select_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_select_control_sass_variable_conversion_data 
   */
  function test_select_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('select', $expected, $value, $args);
  }

  function _test_select_control_sass_variable_conversion_data() {
    return $this->cases_for_control_with_choices('select') + [

      'Test with multiple values from choices' => [ 
        'value1,value2',
        '["value1","value2"]',
        [
          'type'    => 'select',
          'multiple'=> 'true', 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],

      'Test with multiple values from choices, some not from choices' => [ 
        'value1,value2',
        '["value1","value2","value3"]',
        [
          'type'    => 'select',
          'multiple'=> 'true', 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],
    ];
  }

}
