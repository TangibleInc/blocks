<?php

defined('ABSPATH') or die();

$plugin->register_legacy_control_alias('post_select', 'ajax_select', [
  'ajax_action_name' => 'tangible_blocks_select_post'
]);

$ajax->add_action('tangible_blocks_select_post', function($data, $ajax) use($plugin) {

  /**
   * Here, we should check that the current user has the right to edit in the current builder 
   * before returning anything
   * 
   * We would need an helper to evaluate this, according to the current builder
   */

  global $wpdb;

  $post_title = $data['search'];
  $field = $data['field'];

  $limit = (int) ( $field['result_length'] ?? 10 );
  $post_type = explode(',', $field['post_type'] ?? 'post');
  
  $order = $field['result_order'] ?? 'ASC';
  if( ! in_array($order, ['ASC', 'DESC']) ) $order = 'ASC';

  /**
   * @see https://wordpress.stackexchange.com/a/8847/190549
   */
  $results = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT * FROM {$wpdb->posts}
      WHERE (`post_title` LIKE %s)
      AND `post_type` IN ({$plugin->prepare_sql_in_statement($post_type)})
      ORDER BY `post_title` {$order}
      LIMIT %d",
      '%' . $wpdb->esc_like($post_title) . '%',
      $limit
    )
  );
  
  $response = array_map(function($post) {
    return [ 'value' => $post->ID, 'label' => $post->post_title ];
  }, $results);

  return $response;
});

$plugin->prepare_sql_in_statement = function($array) {

  global $wpdb;

  $escaped = [];
  foreach($array as $value) {
    $escaped[] = $wpdb->prepare('%s', $value);
  }

  return implode(',', $escaped);
};
