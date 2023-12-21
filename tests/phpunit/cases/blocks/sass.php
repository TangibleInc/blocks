<?php

use Tangible\Blocks\Sass as sass;

class Sass_Test extends WP_UnitTestCase {

  /**
   * @dataProvider _test_array_to_sass_variable_conversion_data
   */
  function test_array_to_sass_variable_conversion(
    string $result, 
    array $variable_definition
  ) {
    $this->assertEquals( $result, sass\to_variable($variable_definition) );
  }

  function _test_array_to_sass_variable_conversion_data() {
    return [

      'Simple variable' => [
        'test',
        [ 
          'value' => 'test',
          'type'  => 'string'
        ],
      ],

      'Map with string values only' => [
        '("key1":"value1","key2":"value2")',
        [ 
          'type'  => 'map',
          'value' => [
            'key1' => [ 
              'value' => 'value1', 
              'type'  => 'string' 
            ],
            'key2' => [ 
              'value' => 'value2', 
              'type'  => 'string' 
            ]
          ],
        ],
      ],

      'Map with string and integer values' => [
        '("key1":"value1","key2":2)',
        [ 
          'type'  => 'map',
          'value' => [
            'key1' => [ 
              'value' => 'value1', 
              'type'  => 'string' 
            ],
            'key2' => [ 
              'value' => '2', 
              'type'  => 'integer' 
            ]
          ],
        ],
      ],

      'Nested map with string and integer values' => [
        '("key1":("key1-0":"subvalue1","key1-1":2),"key2":2)',
        [ 
          'type'  => 'map',
          'value' => [
            'key1' => [ 
              'type'  => 'map',
              'value' => [
                'key1-0' => [
                  'type'  => 'string',
                  'value' => 'subvalue1'
                ],
                'key1-1' => [
                  'type'  => 'integer',
                  'value' => '2'
                ]
              ], 
            ],
            'key2' => [ 
              'value' => '2', 
              'type'  => 'integer' 
            ]
          ],
        ],
      ],

      'Multiple nested maps with string and integer values' => [
        '("key1":("key1-0":"subvalue1","key1-1":("key1-1-0":"subsubvalue1","key1-1-1":3)),"key2":2)',
        [ 
          'type'  => 'map',
          'value' => [
            'key1' => [ 
              'type'  => 'map',
              'value' => [
                'key1-0' => [
                  'type'  => 'string',
                  'value' => 'subvalue1'
                ],
                'key1-1' => [
                  'type'  => 'map',
                  'value' => [
                    'key1-1-0' => [
                      'type'  => 'string',
                      'value' => 'subsubvalue1'
                    ],
                    'key1-1-1' => [
                      'type'  => 'integer',
                      'value' => '3'
                    ],
                  ]
                ]
              ], 
            ],
            'key2' => [ 
              'value' => '2', 
              'type'  => 'integer' 
            ]
          ],
        ],
      ],

      'Map with a list' => [
        '("key1":(1,2,3),"key2":4)',
        [ 
          'type'  => 'map',
          'value' => [
            'key1' => [ 
              'type'  => 'list',
              'value' => [
                [ 
                  'type'  => 'integer',
                  'value' => '1'
                ],
                [ 
                  'type'  => 'integer',
                  'value' => '2'
                ],
                [ 
                  'type'  => 'integer',
                  'value' => '3'
                ]
              ]
            ],
            'key2' => [ 
              'type'  => 'integer',
              'value' => '4'
            ]
          ] 
        ],
      ],

      'Simple list' => [
        '("one","two","three")',
        [ 
          'type'  => 'list',
          'value' => [
            [ 
              'type'  => 'string',
              'value' => 'one'
            ],
            [ 
              'type'  => 'string',
              'value' => 'two'
            ],
            [ 
              'type'  => 'string',
              'value' => 'three'
            ],
          ] 
        ],
      ],

      'Simple list of integer' => [
        '(1,2,3)',
        [ 
          'type'  => 'list',
          'value' => [
            [ 
              'type'  => 'integer',
              'value' => '1'
            ],
            [ 
              'type'  => 'integer',
              'value' => '2'
            ],
            [ 
              'type'  => 'integer',
              'value' => '3'
            ],
          ] 
        ],
      ],

      'List with map' => [
        '(("key1":1),2)',
        [ 
          'type'  => 'list',
          'value' => [
            [ 
              'type'  => 'map',
              'value' => [
                'key1' => [ 
                  'type'  => 'integer',
                  'value' => '1'
                ]
              ]
            ],
            [ 
              'type'  => 'integer',
              'value' => '2'
            ]
          ] 
        ],
      ],

    ];
  }

  function _test_array_to_sass_list_conversion_data() {

  }

}
