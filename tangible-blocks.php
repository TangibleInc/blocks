<?php
/**
 * Plugin Name: Tangible Blocks
 * Plugin URI: https://tangibleblocks.com/
 * Description: Tangible Blocks is a system for universal blocks that work in Gutenberg, Elementor, and Beaver Builder
 * Version: 4.1.5
 * GitHub URI: TangibleInc/blocks
 * Author: Team Tangible
 * Author URI: https://teamtangible.com
 * License: GPLv2 or later
 */
use tangible\framework;
use tangible\updater;

define( 'TANGIBLE_BLOCKS_VERSION', '4.1.5' );

$module_path = is_dir(
  ($path = __DIR__ . '/../../tangible') // Module
) ? $path : __DIR__ . '/vendor/tangible'; // Plugin

require_once $module_path . '/framework/index.php';
require_once $module_path . '/template-system/index.php';
require_once $module_path . '/fields/index.php';
require_once $module_path . '/updater/index.php';

/**
 * Get plugin instance
 */
function tangible_blocks($instance = false) {
  static $plugin;
  return $plugin ? $plugin : ($plugin = $instance);
}

add_action('plugins_loaded', function() {

  $plugin    = framework\register_plugin([
    'name'           => 'tangible-blocks',
    'title'          => 'Tangible Blocks',
    'setting_prefix' => 'tangible_blocks',

    'version'        => TANGIBLE_BLOCKS_VERSION,
    'file_path'      => __FILE__,
    'base_path'      => plugin_basename( __FILE__ ),
    'dir_path'       => plugin_dir_path( __FILE__ ),
    'url'            => plugins_url( '/', __FILE__ ),
    'assets_url'     => plugins_url( '/assets', __FILE__ ),
  ]);

  tangible_blocks( $plugin );

  updater\register_plugin([
    'name' => $plugin->name,
    'file' => __FILE__,
    // 'license' => ''
  ]);

  // Features loaded will have in their local scope: $plugin

  $template_system = tangible_template_system();

  $loop      = $template_system->loop;
  $logic     = $template_system->logic;
  $html      = $template_system->html;
  $interface = $template_system->interface;
  $ajax      = $template_system->ajax;

  $fields = tangible_fields();
  
  require_once __DIR__.'/includes/index.php';

}, 10);
