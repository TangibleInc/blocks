<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Combobox_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_combo_box_control_sass_variable_conversion_data 
   */
  function test_combo_box_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('combo_box', $expected, $value, $args);
  }

  function _test_combo_box_control_sass_variable_conversion_data() {
    return $this->cases_for_control_with_choices('combo_box') + [

      'Test with multiple values from choices' => [ 
        'value1,value2',
        'value1,value2',
        [
          'type'    => 'combo_box',
          'multiple'=> 'true', 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],

      'Test with multiple values from choices, some not from choices' => [ 
        'value1,value2',
        'value1,value2,value3',
        [
          'type'    => 'combo_box',
          'multiple'=> 'true', 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],

      'Test with async value' => [ 
        'value1',
        '{"value":"value1","name":"Value 1"}',
        [
          'type'     => 'combo_box',
          'is_async' => 'true', // Should probably change to make it work with boolean true as well
        ] 
      ],

      'Test with async multiple value' => [ 
        'value1,value2',
        '[{"value":"value1","name":"Value 1"},{"value":"value2","name":"Value 2"}]',
        [
          'type'     => 'combo_box',
          'is_async' => 'true',
          'multiple' => 'true', 
        ] 
      ],

    ];
  }

}
