<?php

namespace Tangible\Blocks\Tests\Controls;

/**
 * Gallery class extends File so we use the same tests 
 */

class Gallery_Sass_Test extends File_Sass_Test {

  /**
   * @dataProvider _test_file_control_sass_variable_conversion_data 
   */
  function test_file_control_sass_variable_conversion($get_args) {

    // It seems that $this->factory would be empty if used in dataProvider
    $ids = $this->factory->attachment->create_many(3);
    [ $expected, $value, $args ] = $get_args($ids);
    
    $this->evaluate_sass_control_variable('gallery', $expected, $value, $args);
  }

}
