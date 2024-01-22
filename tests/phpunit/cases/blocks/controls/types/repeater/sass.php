<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Repeater_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_repeater_control_sass_variable_conversion_data 
   */
  function test_repeater_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('repeater', $expected, $value, $args);
  }

  function _test_repeater_control_sass_variable_conversion_data() {
    return [

      'Simple repeater' => [ 
        '(("field1":"value1","field2":"value2"),("field1":"value3","field2":"value4"))',
        '[
          {"field1":"value1","field2":"value2"},
          {"field1":"value3","field2":"value4"}
        ]',
        [ 
          'type'   => 'repeater',
          'fields' => [
            [
              'name' => 'field1',
              'type' => 'text'
            ],
            [
              'name' => 'field2',
              'type' => 'text'
            ]
          ]
        ] 
      ],

      'Repeater with an integer value' => [ 
        '(("field1":"value1","field2":2),("field1":"value3","field2":4))',
        '[
          {"field1":"value1","field2":"2"},
          {"field1":"value3","field2":"4"}
        ]',
        [ 
          'type'   => 'repeater',
          'fields' => [
            [
              'name' => 'field1',
              'type' => 'text'
            ],
            [
              'name' => 'field2',
              'type' => 'number'
            ]
          ]
        ] 
      ],

      'Repeater with subcontrol with no style contexts' => [ 
        '(("field1":"value1"),("field1":"value3"))',
        '[
          {"field1":"value1","field2":"2"},
          {"field1":"value3","field2":"4"}
        ]',
        [ 
          'type'   => 'repeater',
          'fields' => [
            [
              'name' => 'field1',
              'type' => 'text'
            ],
            [
              'name' => 'field2',
              'type' => 'wysiwyg'
            ]
          ]
        ] 
      ],

      'Repeater with an map value' => [ 
        '(("field1":"value1","field2":("value":0px 0px 0px 0px,"top":0,"right":0,"bottom":0,"left":0,"unit":unquote("px"))))',
        '[{"field1":"value1","field2":{"top":0,"left":0,"right":0,"bottom":0,"unit":"px","isLinked":false}}]',
        [ 
          'type'   => 'repeater',
          'fields' => [
            [
              'name' => 'field1',
              'type' => 'text'
            ],
            [
              'name' => 'field2',
              'type' => 'dimensions'
            ]
          ]
        ] 
      ],

      'Nested repeater' => [ 
        '(("field1":"value1","field2":(("field3":2),("field3":3))),("field1":"value4","field2":(("field3":5),("field3":6))))',
        '[
          {
            "field1":"value1",
            "field2": [
              {"field3": "2"},
              {"field3": "3"}
            ]
          },
          {
            "field1":"value4",
            "field2": [
              {"field3": "5"},
              {"field3": "6"}
            ]
          }
        ]',
        [ 
          'type'   => 'repeater',
          'fields' => [
            [
              'name'   => 'field1',
              'type'   => 'text'
            ],
            [
              'name'   => 'field2',
              'type'   => 'repeater',
              'fields' => [
                [
                  'name' => 'field3',
                  'type' => 'number'
                ]
              ]
            ]
          ]
        ] 
      ],
      
    ];
  }

}
