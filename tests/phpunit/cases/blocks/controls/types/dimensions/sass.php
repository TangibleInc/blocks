<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Dimensions_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_dimensions_control_sass_variable_conversion_data 
   */
  function test_dimensions_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('dimensions', $expected, $value, $args);
  }

  function _test_dimensions_control_sass_variable_conversion_data() {
    return [

      'Simple dimensions' => [ 
        '("value":0px 0px 0px 0px,"top":0,"right":0,"bottom":0,"left":0,"unit":px)',
        '{"top":0,"left":0,"right":0,"bottom":0,"unit":"px","isLinked":false}',
        [ 'type' => 'dimensions' ] 
      ],

      'Dimensions, custom unit' => [ 
        '("value":0vw 0vw 0vw 0vw,"top":0,"right":0,"bottom":0,"left":0,"unit":vw)',
        '{"top":0,"left":0,"right":0,"bottom":0,"unit":"vw","isLinked":false}',
        [ 'type' => 'dimensions', 'units' => ['px', 'vw', '%' ] ]
      ],

      'Dimensions, unallowed unit' => [ 
        '("value":0px 0px 0px 0px,"top":0,"right":0,"bottom":0,"left":0,"unit":px)',
        '{"top":0,"left":0,"right":0,"bottom":0,"unit":"vh","isLinked":false}',
        [ 'type' => 'dimensions', 'units' => ['px', 'vw', '%' ] ]
      ],
      
    ];
  }

}
