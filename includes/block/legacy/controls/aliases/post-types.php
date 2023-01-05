<?php

defined('ABSPATH') or die();

// Post type data
$post_type_objects = get_post_types([], 'objects');

$post_types = [];
foreach ( $post_type_objects as $post_type ) {
  $post_types[ $post_type->name ] = $post_type->labels->singular_name;
}

$plugin->register_legacy_control_alias('post_types', 'select2', [
  'options' => $post_types,
]);
