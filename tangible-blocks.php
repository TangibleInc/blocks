<?php
/**
 * Plugin Name: Tangible Blocks
 * Plugin URI: https://tangibleplugins.com/tangible-blocks
 * Description: Tangible Blocks provides universal blocks that work in Gutenberg, Elementor, and Beaver Builder
 * Version: 3.0.0
 * Author: Team Tangible
 * Author URI: https://teamtangible.com
 * License: GPLv2 or later
 */

define( 'TANGIBLE_BLOCKS', '3.0.0' );

require_once __DIR__ . '/vendor/tangible/plugin-framework/index.php';
require_once __DIR__ . '/vendor/tangible/plugin-updater/index.php';
require_once __DIR__ . '/vendor/tangible/template-system/index.php';

/**
 * Get plugin instance
 */
function tangible_blocks($instance = false) {
  static $plugin;
  return $plugin ? $plugin : ($plugin = $instance);
}

add_action('plugins_loaded', function() {

  $framework = tangible();
  $plugin    = $framework->register_plugin([
    'name'           => 'tangible-blocks',
    'title'          => 'Tangible Blocks',
    'setting_prefix' => 'tangible_blocks',

    'version'        => TANGIBLE_BLOCKS,
    'file_path'      => __FILE__,
    'base_path'      => plugin_basename( __FILE__ ),
    'dir_path'       => plugin_dir_path( __FILE__ ),
    'url'            => plugins_url( '/', __FILE__ ),
    'assets_url'     => plugins_url( '/assets', __FILE__ ),
  ]);

  tangible_blocks( $plugin );

  tangible_plugin_updater()->register_plugin([
    'name' => $plugin->name,
    'file' => __FILE__,
    // 'license' => ''
  ]);

  // Features loaded will have in their local scope: $framework, $plugin, ...

  $template_system = tangible_template_system();

  $loop      = $template_system->loop;
  $logic     = $template_system->logic;
  $html      = $template_system->html;
  $interface = $template_system->interface;
  $ajax      = $template_system->ajax;

  require_once __DIR__.'/includes/index.php';

}, 10);
