<?php
/**
 * Plugin Name: Test plugin
 */
namespace test;

/**
 * Get all plugins
 * https://developer.wordpress.org/reference/functions/get_plugins/
 */
function get_all_plugins() {

  // Required on frontend
  if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  return get_plugins();
}

/**
 * Get active plugins
 */
function get_active_plugins() {
  return get_option('active_plugins');
}

/**
 * Activate dependency plugins
 */
function activate_dependency_plugins() {

  if (!function_exists('tangible_template')) {
    if (!function_exists('activate_plugin')) {
      require ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $result = activate_plugin(ABSPATH . 'wp-content/plugins/tangible-blocks/tangible-blocks.php');
    if (is_wp_error($result)) return $result;
  }
  
  if ( !get_option('site_init_done') ) {
  
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
  
    update_option('site_init_done', 1);
  }
  
  return true;
}
