<?php

defined('ABSPATH') or die();

$plugin->register_control_alias('user_select', 'ajax_select', [
  'ajax_action_name' => 'tangible_blocks_select_user'
]);

$ajax->add_action('tangible_blocks_select_user', function($data, $ajax) {

  global $wpdb;

  $user_name = $data['search'];
  $field = $data['field'];

  $limit = isset($field['result_length'])
    ? (int) $field['result_length']
    : 10
  ;

  $user_role = isset($field['role'])
    ? explode(',', $field['role'])
    : []
  ;

  $args = [
    'role__in' => $user_role,
    'order' => 'ASC',
    'orderby' => 'display_name',
    'search' => '*' . $user_name . '*',
    'search_columns' => [ 'display_name', 'user_nicename' ]
  ];
  $user_query = new WP_User_Query( $args );
  $results = $user_query->get_results();

  if ( empty($results) ) return [];

  $response = array_map(function($user) {
    return [ 'value' => $user->ID, 'label' => $user->display_name ];
  }, $results);

  return $response;
});
