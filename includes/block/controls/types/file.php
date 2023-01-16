<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class File extends Base {

  public string $type = 'file';

  function get_attachment_data(int $id) : array {

  $attachement = get_post($id);

  return $attachement 
    ? [
        'id'          => $attachement->ID,
        'url'         => wp_get_attachment_url( $attachement->ID ),
        'value'       => wp_get_attachment_url( $attachement->ID ), // Default when no field specified
        'alt'         => get_post_meta( $attachement->ID, '_wp_attachment_image_alt', true ),
        'title'       => $attachement->post_title,
        'caption'     => $attachement->post_excerpt,
        'description' => $attachement->post_content
      ] 
    : [];
  }

  function get_value($values, array $args, string $context) {

    if( ! is_array($values) ) return [];

    return array_map(
      function( $attachment_id ) {
        return $this->get_attachment_data( (int) $attachment_id );
      },
      $values
    );
  }

}
