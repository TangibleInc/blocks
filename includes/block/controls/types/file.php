<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class File extends Base {

  public string $type = 'file';

  /**
   * @see ./includes/block/sass.php
   */
  function get_sass_variable_definition($images, array $args) : array {
    return [
      'type'  => 'list',
      'value' => array_map(
        function($image) {
          return [
            'type'  => 'map',
            'value' => [
              'id' => [
                'value' => $image['id'] ?? '',
                'type'  => 'string'
              ],
              'url' => [
                'value' => $image['url'] ?? '',
                'type'  => 'string'
              ],
              'value' => [
                'value' => $image['value'] ?? '',
                'type'  => 'string'
              ],
              'alt' => [
                'value' => $image['alt'] ?? '',
                'type'  => 'string'
              ],
              'title' => [
                'value' => $image['title'] ?? '',
                'type'  => 'string'
              ],
              'caption' => [
                'value' => $image['caption'] ?? '',
                'type'  => 'string'
              ],
              'description' => [
                'value' => $image['description'] ?? '',
                'type'  => 'string'
              ]
            ]
          ];
        },
        $images
      )
    ];
  }

  function get_attachment_data(int $id) : array {

    $attachement = get_post($id);

    return $attachement 
      ? [
        'id'          => (int) $attachement->ID,
        'url'         => esc_url( wp_get_attachment_url( $attachement->ID ) ),
        'value'       => esc_url( wp_get_attachment_url( $attachement->ID ) ), // Default when no field specified
        'alt'         => esc_attr( get_post_meta( $attachement->ID, '_wp_attachment_image_alt', true ) ),
        'title'       => esc_html( $attachement->post_title ),
        'caption'     => esc_html( $attachement->post_excerpt ),
        'description' => esc_html( apply_filters( 'the_description' , $attachement->post_content ) )
      ]
      : [];
  }

  function get_value($values, array $args, string $context) {

    if( is_string($values) ) $values = json_decode($values);
    if( ! is_array($values) ) return [];

    return array_map(
      function( $attachment_id ) {
        return $this->get_attachment_data( (int) $attachment_id );
      },
      $values
    );
  }

}
