<?php

namespace Tangible\Blocks\Tests\Controls;

use Tangible\Blocks\Sass as sass;

trait Sass_Helper_Test {

  function evaluate_sass_control_variable(
    string $type, 
    string $expected, 
    $value, 
    array $args
  ) : void {
   
    $blocks = tangible_blocks();
    $control = $blocks->get_control( $type );

    $sass_definition = $control->render( $value, $args, 'style' );
    $sass_variable = sass\to_variable( $sass_definition );

    $this->assertEquals( $expected, $sass_variable );
  }

  function cases_for_control_with_choices(string $type) {
    return [
      'Test with value from choices' => [ 
        'value2',
        'value2',
        [
          'type'    => $type, 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],

      'Test with value not from choices' => [ 
        '',
        'value3',
        [
          'type'    => $type, 
          'choices' => [
            'value1' => 'Value 1',
            'value2' => 'Value 2'
          ] 
        ] 
      ],

      'Test with integer value' => [ 
        '1',
        1,
        [
          'type'    => $type,
          'choices' => [
            1 => 'Value 1',
            2 => 'Value 2'
          ] 
        ] 
      ],
    ];
  }

}
