<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Gallery extends Base {

  public array $context = [
    'template'
  ];

  function register_control(string $builder, array $args): array {
    
    $label   = $args['label'] ?? '';
    $size    = $field['size'] ?? 'full';
    $default = $this->format_default_value( $args['default'] ?? '', $builder );
    
    switch($builder) {
      case 'elementor':
        return [
          'type'    => $this->get_elementor_control_type('GALLERY'),
          'label'   => $label,
          'default' => $default,
          'size'    => $size
        ];
      case 'beaver-builder':
        return [
          'type'    => 'multiple-photos',
          'label'   => $label,
          'default' => $default,
          'size'    => $size
        ];
      case 'gutenberg':
        return [
          'type'    => 'array',
          'default' => $default
        ];
    }
  }

  function format_default_value($default, $builder) {

    $default = str_replace(' ', '', $field['default'] ?? '');
    $values  = explode(',', $default);

    if( $builder !== 'elementor' || empty($values) ) {
      return $values;
    } 

    $default = [];
    foreach( $values as $value ) {

      $attachment = get_post($value);
      if( ! $attachment ) continue;

      $default []= [
        'id'  => $value,
        'url' => wp_get_attachment_url($attachment->ID)
      ];
    }

    return $default;
  }

  function format_value($value, string $builder, array $args, $settings) {
    
    if( ! is_array($value) ) return [];    
    if( $builder === 'beaver-builder' ) return $value;

    $value = array_map(function($image) {
      if( ! empty($image['id']) ) return $image['id'];
    }, $value);

    return array_filter($value);
  }

  function get_value($images, array $args, string $context) {
    
    $images = array_map(function($id) use($args) {

      $image = get_post( $id );
      if( ! $image ) return;
      
      return [
        'value'       => $this->get_image_html( $id, $args['size'] ?? false ),
        'id'          => $id,
        'url'         => wp_get_attachment_url( $id ),
        'title'       => $image->post_title,
        'alt'         => get_post_meta( $id, '_wp_attachment_image_alt', true ),
        'caption'     => wp_get_attachment_caption( $id ),
        'description' => $image->post_content,
      ];
    }, $images);

    return array_filter($images);
  }

  function get_image_html( $id, $size ) {

    // Filter to remove style attribute on img tag - See below for its definition
    add_filter( 'wp_get_attachment_image_attributes', [$this, 'remove_image_attributes'], 99, 1 );
    
    return wp_get_attachment_image( $id, $size ?: 'thumbnail' );

    remove_filter( 'wp_get_attachment_image_attributes', [$this, 'remove_image_attributes'], 99 );
  }

  /**
   * Filter to remove style attribute on img tag added by Gutenberg and Beaver
   * 
   * @see https://developer.wordpress.org/reference/hooks/wp_get_attachment_image_attributes/
   */
  function remove_image_attributes( $attributes ) {
    unset($attributes['style']);
    return $attributes;
  }
  
}

$plugin->register_control('gallery', new Gallery);
