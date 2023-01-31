<?php

defined('ABSPATH') or die();

use Tangible\Blocks\Integrations\Elementor\Dynamic\Base;

$plugin->register_legacy_control('image', [
  'elementor'       => $plugin->get_elementor_control_type('MEDIA'),
  'beaver-builder'  => 'photo',
  'gutenberg'       => 'object',
])
  ->elementor(function($field, $type) {
    $default = isset( $field['default'] )
      ? $field['default']
      : ''
    ;
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => wp_http_validate_url($default)
      ? [ 'url'=> $default ]
      : [ 
        'id' => $default,
        'url' => wp_get_attachment_url($default)
      ]
    ];
  })
  ->beaver_builder(function($field, $type){
    return [
      'label'   => $field['label'],
      'type'    => $type,
      'default' => isset($field['default']) ? $field['default'] : ''
    ];
  })
  ->gutenberg(function($field, $type){ 
    if(isset( $field['default'] )){
      if( is_numeric($field['default']) ) $default = wp_prepare_attachment_for_js($field['default']);
      if( wp_http_validate_url($field['default']) ) $default['url'] = wp_http_validate_url($field['default']);
    }

    return [
      'type'    => $type,
      'default' => isset($default) ? $default : ''
    ];
  })
  ->filter_value(function($value, $builder, $field, $settings) {

    if( $builder !== 'beaver-builder' ) return $value;

    // $value will be an url only in cases of a default value, will be an attachement id otherwise
    if( is_numeric($value) ) {
      return !empty($settings->{ $field['name'] . '_src' })
        ? $settings->{ $field['name'] . '_src' }
        : wp_get_attachment_url($value)
      ;
    }
    
    return $value;
  })
  ->legacy_render(function($value, $block) {
    if( empty($value) ) return '';

    // Elementor returns an array of info instead of a specific piece of data ID like the others do
    if ( gettype($value) == "array" ) {
      $value = !empty($value['url']) 
        ? $value['url']
        : (!empty($value['id']) ? wp_get_attachment_url($value['id']) : '')
      ;
    }

    return esc_url($value);
  })
  ->sub_values([
    'id',
    'alt',
    'title',
    'caption',
    'description'
  ])
  ->render_sub_values(function($name, $builder, $field, $settings) {   
    switch($builder) {
      case 'elementor':
        $elementor_prefix = Base::$control_prefix;
        $values = $settings[ $elementor_prefix . $field['name'] ];
        $attachment = $values['id'] === '' ? $values['id'] : get_post($values['id']);
        if( empty($attachment) ) return '';
        break;
        
      case 'gutenberg':
        $values = $settings[$field['name']];        
        $attachment = get_post( $values['id'] ?? '');
        if( empty($attachment) ) return '';
        break;

      case 'beaver-builder':
        $value = $settings->{ $field['name'] } !== ''   
          ? $settings->{ $field['name'] } 
          : false 
        ;

        $values['id'] = is_numeric($value) ? $value : '';
        if( empty($values['id']) ) return '';

        $attachment = get_post( $values['id'] );
        if( empty($attachment) ) return '';
        break;
    }
    $values['alt'] = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
    $values['title'] = $attachment->post_title;
    $values['caption'] = $attachment->post_excerpt;
    $values['description'] = $attachment->post_content;
    
    return $values[$name];
  });

