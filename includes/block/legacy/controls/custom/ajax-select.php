<?php

/**
 * This control will not work out of the box, it needs to be used as an alias
 *
 * @see example alias/post-select
 */

$plugin->register_legacy_custom_control([
    'type' => 'ajax_select',
  ])
  ->context(['template'])
  ->enqueue(function($script_name) use($plugin) {
    wp_enqueue_script( 'tangible-ajax' );
  })
  ->filter_value(function($value, $builder, $field, $settings) {

    if( $builder === 'beaver-builder' ) return json_encode($value);

    return $value;
  })
  ->render(function($value, $field) {

    if( empty($value) || !is_string($value) ) return '';

    $data = json_decode($value, true);
    if( empty($data) || !isset($data[0]['value']) ) return '';

    if( !isset($field['multiple']) || $field['multiple'] !== 'true' ) return esc_html($data[0]['value']);

    $ids = '';
    foreach ($data as $data_child) {
      if($ids === '') {
        $ids = $data_child['value'];
      } else {
        $ids .= ','.$data_child['value'];
      }
    }

    return esc_html($ids);
  });
