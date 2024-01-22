<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class Field_Group_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_field_group_control_sass_variable_conversion_data 
   */
  function test_field_group_control_sass_variable_conversion($expected, $value, $args) {
    $this->evaluate_sass_control_variable('field_group', $expected, $value, $args);
  }

  function _test_field_group_control_sass_variable_conversion_data() {
    return [

      'Simple field group' => [ 
        '("field1":"value1","field2":"value2")',
        '{"field1":"value1","field2":"value2"}',
        [ 
          'type'   => 'field_group',
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
      
      'Field group with integer value' => [ 
        '("field1":"value1","field2":0)',
        '{"field1":"value1","field2":"0"}',
        [ 
          'type'   => 'field_group',
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

      'Field group with subcontrol with no style contexts' => [ 
        '("field1":"value1")',
        '{"field1":"value1","field2":"0"}',
        [ 
          'type'   => 'field_group',
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

      'Field group with map value' => [ 
        '("field1":"value1","field2":("value":0px 0px 0px 0px,"top":0,"right":0,"bottom":0,"left":0,"unit":unquote("px")))',
        '{"field1":"value1","field2":{"top":0,"left":0,"right":0,"bottom":0,"unit":"px","isLinked":false}}',
        [ 
          'type'   => 'field_group',
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

      'Nested field groups' => [ 
        '("field1":"value1","field2":("field3":3))',
        '{"field1":"value1","field2":{"field3":"3"}}',
        [ 
          'type'   => 'field_group',
          'fields' => [
            [
              'name' => 'field1',
              'type' => 'text'
            ],
            [
              'name'   => 'field2',
              'type'   => 'field_group',
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

      'Field group with a repeater with a nested field group' => [ 
        '("field1":"value1","field2":(("field3":31,"field4":("field5":"value5-1","field6":61)),("field3":32,"field4":("field5":"value5-2","field6":62))))',
        '{
          "field1":"value1",
          "field2": [ 
            {"field3":"31", "field4":{ "field5":"value5-1", "field6": "61" } },
            {"field3":"32", "field4":{ "field5":"value5-2", "field6": "62" } }
          ] 
        }',
        [ 
          'type'   => 'field_group',
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
                  'name'   => 'field3',
                  'type'   => 'number'
                ],
                [
                  'name'   => 'field4',
                  'type'   => 'field_group',
                  'fields' => [
                    [
                      'name'     => 'field5',
                      'type'     => 'text'
                    ],
                    [
                      'name'     => 'field6',
                      'type'     => 'number'
                    ],
                  ]
                ]
              ]
            ]
          ]
        ] 
      ],
      
    ];
  }

}
