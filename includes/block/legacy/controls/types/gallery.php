<?php

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base;

$plugin->register_legacy_control('gallery', [
  'elementor'       => $plugin->get_elementor_control_type('GALLERY'),
  'beaver-builder'  => 'multiple-photos',
  'gutenberg'       => 'array',
])
  ->context(['template'])
  ->elementor(function($field, $type) {
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => explode(',', str_replace(' ', '', $field['default'] ?? '')),
      'size'    => $field['size'] ?? 'full'
    ];
  })
  ->beaver_builder(function($field, $type){
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => explode(',', str_replace(' ', '', $field['default'] ?? '')),
      'size'    => $field['size'] ?? 'full'
    ];
  })
  ->gutenberg(function($field, $type){
    return [
      'type'    => $type,
      'default' => explode(',', str_replace(' ', '', $field['default'] ?? '')),
      'size'    => $field['size'] ?? 'full'
    ];
  })
  ->default(function($values, $builder) {

    if( $builder !== 'elementor' ) return $values;

    $default = [];
    if( empty($values) ) return $default;

    foreach ($values as $value) {

      $attachment = get_post($value);
      if( !$attachment ) continue;

      $default []= [
        "id"  => $value,
        "url" => wp_get_attachment_url($attachment->ID)
      ];
    }

    return $default;
  })
  ->filter_value(function($values, $builder, $field, $settings) {
    if( empty($values) ) return '';
    
    $output = [];
    foreach ($values as $key => $value) {
      $output[$key] = [
        'info' => $value,
        'size' => isset($field['size']) ? $field['size'] : 'thumbnail'
      ];
    }
    return $output;
  })
  ->legacy_render(function($values, $block) use ($plugin) {
    if( empty($values) ) return '';

    // Filter to remove style attribute on img tag - See below for its definition
    add_filter('wp_get_attachment_image_attributes',
      $plugin->gallery_control_attachment_image_filter,
      99, // After all other plugins that may change attributes
      1
    );

    $output = '';
    foreach ($values as $value) {
      $attachement_id = isset($value['info']['id']) ? $value['info']['id'] : (int) $value['info'];
      if( !empty($attachement_id) ) $output .= wp_get_attachment_image( $attachement_id, $value['size'] );
    }

    remove_filter('wp_get_attachment_image_attributes',
      $plugin->gallery_control_attachment_image_filter,
      99 // Must be the same as priority used for add_filter
    );

    return $output;
  })
  ->sub_values([
    'ids',
  ])
  ->render_sub_values(function($name, $builder, $field, $settings) {
    if ( empty( $name ) ) return '';

    $ids = [];

    switch($builder){

      case 'elementor':
        $elementor_prefix = Base::$control_prefix;
        $values = $settings[ $elementor_prefix . $field['name'] ];
        if( !isset($values[0]['id']) ) return '';
        foreach ($values as $image) {
          $ids []= $image['id'];
        }
        break;

      case 'gutenberg':
        $values = $settings[ $field['name'] ];
        foreach ($values as $image) {
          if( empty($image) ) continue;
          $ids []= is_array($image) ? $image['id'] : $image;
        }
        break;

      case 'beaver-builder':
        $value = $settings->{ $field['name'] } !== ''
          ? $settings->{ $field['name'] }
          : false
        ;

        if( empty($value) ) break;
        
        foreach ($value as $image) {
          $ids []= $image;
        }
        break;
    }

    return implode(',', $ids);
  });


/**
 * Filter to remove style attribute on img tag added by Gutenberg and Beaver
 * @see https://developer.wordpress.org/reference/hooks/wp_get_attachment_image_attributes/
 */
$plugin->gallery_control_attachment_image_filter = function($attr) {
  unset($attr['style']);
  return $attr;
};
