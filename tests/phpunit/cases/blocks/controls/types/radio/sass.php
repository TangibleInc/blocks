<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Radio_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_radio_control_sass_variable_conversion_data 
   */
  function test_radio_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('radio', $expected, $value, $args);
  }

  function _test_radio_control_sass_variable_conversion_data() {
    return $this->cases_for_control_with_choices('radio');
  }

}
