<?php

$plugin->register_legacy_custom_control([
  'type'    => 'taxonomy',
])
  ->context(['template'])
  ->enqueue(function($script_name) use($plugin, $interface) {

    $interface->enqueue('select');

    // Post type data
    $post_type_objects = get_post_types([], 'objects');

    $post_types = [];
    foreach ( $post_type_objects as $post_type ) {
      $post_types[ $post_type->name ] = $post_type->labels->singular_name;
    }

    // Taxonomy data
    $taxonomies = array_keys(get_taxonomies());
    $taxonomy_terms = [];
    foreach ( $taxonomies as $taxonomy ){
      $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false
      ]);
      $taxonomy_terms[ $taxonomy ] = $terms;
    }

    $data = [
      'allPostTypes' => $post_types,
      'allTaxonomies' => $taxonomy_terms,
    ];

    wp_add_inline_script(
      $script_name,
      'window.Tangible = window.Tangible || {}; window.Tangible.postQueryControlData = '
      . json_encode( $data )
      ,
      'before'
    );
  })
  ->filter_value(function($value, $builder, $field, $settings) {

    if ( $builder !== 'beaver-builder' ) return json_decode($value, true);
    
    return json_decode( json_encode( $value ), true );
  })
  ->legacy_render(function($values, $field) {

    if( empty($values) ) return '';

    $params = '';
    foreach( $values as $key => $value ) {
      if( !empty($taxonomy) ) $params .= "$key=\"$value\"\n";
    }

    return esc_html($params);
  });
