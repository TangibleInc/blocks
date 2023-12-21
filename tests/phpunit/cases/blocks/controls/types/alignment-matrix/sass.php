<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Alignment_Matrix_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_alignment_matrix_control_sass_variable_conversion_data 
   */
  function test_alignment_matrix_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('alignment_matrix', $expected, $value, $args);
  }

  function _test_alignment_matrix_control_sass_variable_conversion_data() {
    return [

      'Simple test with string' => [ 
        'test with string value',
        'test with string value',
        ['type' => 'alignment_matrix' ] 
      ],

      'Simple test with integer' => [ 
        '0',
        0,
        ['type' => 'alignment_matrix' ] 
      ],

      'Simple test with false' => [ 
        '',
        false,
        ['type' => 'alignment_matrix' ] 
      ],
      
    ];
  }

}
