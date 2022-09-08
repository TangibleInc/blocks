<?php

$plugin->register_custom_control([
    'type'  => 'post_query',
    'popup' => true
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

    if ( empty( $value ) ) return '';

    if ( $builder !== 'beaver-builder' ){
      $values = json_decode($value, true);

    } else {
      if ( gettype( $value ) === 'string' ) {
        $values = json_decode( $value );
      } else {
        $values = json_decode( json_encode( $value ), true );
      }
    }

    return $values;
  })
  ->render(function($values, $field) {

    if ( empty( $values ) ) return '';

    $params = '';
    $excluded_keys = ['loopType'];
    foreach( $values as $key => $value ) {

      if ( empty($value) || in_array($key, $excluded_keys) ) continue;

      if ( $key !== 'taxonomies' && is_string($value) ) {
        $params .= "$key=\"$value\"\n";
        continue;
      }

      foreach( $value as $key2 => $taxonomy ) {
        if ( !empty($taxonomy) ) $params .= "$key2=\"$taxonomy\"\n";
      }
    }

    return esc_html($params);
  });
