<?php

namespace Tangible\Blocks\Tests\Controls;

use WP_UnitTestCase;

class File_Sass_Test extends WP_UnitTestCase {

  use Sass_Helper_Test;

  /**
   * @dataProvider _test_file_control_sass_variable_conversion_data 
   */
  function test_file_control_sass_variable_conversion($get_args) {

    // It seems that $this->factory would be empty if used in dataProvider
    $ids = $this->factory->attachment->create_many(3);
    [ $expected, $value, $args ] = $get_args($ids);
    
    $this->evaluate_sass_control_variable('file', $expected, $value, $args);
  }

  function _test_file_control_sass_variable_conversion_data() {
    return [
 
      'Simple file' => [
        function($ids) { 
          return [ 
            '(' . $this->_format_attachment_to_sass_map( $ids[0] ) .')',
            '[' . $ids[0] . ']',
            [ 'type' => 'file' ] 
          ];
        }
      ],

      'Multiple files' => [
        function($ids) { 
          return [ 
            '(' . $this->_format_attachment_to_sass_map( $ids[0] ) .',' . $this->_format_attachment_to_sass_map( $ids[1] ) . ')',
            '[' . $ids[0] . ',' . $ids[1] . ']',
            [ 'type' => 'file' ] 
          ];
        }
      ],

      'Wrong attachment' => [
        function($ids) { 
          return [ 
            '(("id":"","url":"","value":"","alt":"","title":"","caption":"","description":""))',
            '[999]',
            [ 'type' => 'file' ] 
          ];
        }
      ],

      'Empty' => [
        function($ids) { 
          return [ 
            '()',
            '',
            [ 'type' => 'file' ] 
          ];
        }
      ],
    ];
  }

  function _format_attachment_to_sass_map(int $id) {
    
    $attachement = get_post( $id );
    $values = [
      '"id":"' . $id . '"',
      '"url":"' . wp_get_attachment_url($id) . '"',
      '"value":"' . wp_get_attachment_url($id) . '"',
      '"alt":"' . get_post_meta( $attachement->ID, '_wp_attachment_image_alt', true ) . '"',
      '"title":"' . $attachement->post_title . '"',
      '"caption":"' . $attachement->post_excerpt . '"',
      '"description":"' . apply_filters( 'the_description' , $attachement->post_content ) . '"',
    ];

    return '(' . implode(',', $values) . ')';
  }

}
