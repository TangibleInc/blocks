<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class File extends Base {

  public string $type = 'file';

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
        'description' => esc_html( $attachement->post_content )
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
