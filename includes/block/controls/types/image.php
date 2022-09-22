<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

class Image extends Base {

  function register_control(string $builder, array $args): array {
    
    $label   = $args['label'] ?? '';
    $default = $this->format_default_value( $args['default'] ?? '', $builder );
    
    switch($builder) {
      case 'elementor':
        return [
          'type'    => $this->get_elementor_control_type('MEDIA'),
          'label'   => $label,
          'default' => $default
        ];
      case 'beaver-builder':
        return [
          'type'    => 'photo',
          'label'   => $label,
          'default' => $default
        ];
      case 'gutenberg':
        return [
          'type'    => 'object',
          'default' => $default
        ];
    }

  }

  /**
   * Default value can either be an attachment ID or an url
   */
  function format_default_value($value, $builder) {

    if( $builder === 'beaver-builder' ) return $value;
    if( empty($value) ) return [];

    $is_url = wp_http_validate_url($value);
    $is_id  = is_numeric($value);

    if( ! $is_id && ! $is_url ) return $value;
    if( $is_url ) return [ 'url' => $value ];
    
    if( $builder === 'gutenberg' ) {
      return wp_prepare_attachment_for_js($value);
    }

    return [
      'id'  => $value,
      'url' => wp_get_attachment_url($value)
    ]; 
  }

  function format_value($value, string $builder, array $args, $settings) {
    
    if( $builder === 'beaver-builder' ) return $value;
    
    if( is_numeric($value['id'] ?? '') ) {
      return $value['id'];
    }

    return $value['url'] ?? '';
  }

  /**
   * $value is either an url or an id at this point
   */
  function get_value($value, $args, $context) {

    $is_url = wp_http_validate_url($value);
    $is_id  = is_numeric($value);

    $default_fields = [
      'value'       => '',
      'id'          => '',
      'alt'         => '',
      'title'       => '',
      'caption'     => '',
      'description' => ''
    ];

    if( $is_url ) {
      return wp_parse_args([
        'value' => esc_url($value)
      ], $default_fields); 
    }

    if( $is_id && $attachment = get_post($value) ) {
      return [
        'value'       => wp_get_attachment_url($value),
        'id'          => $value,
        'title'       => $attachment->post_title,
        'alt'         => get_post_meta( $value, '_wp_attachment_image_alt', true ),
        'caption'     => wp_get_attachment_caption($value),
        'description' => $attachment->post_content,
      ];
    }

    return wp_parse_args(
      is_array($value) ? $value : [], 
      $default_fields
    );
  }

}

$plugin->register_control('image', new Image);
