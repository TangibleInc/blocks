<?php defined('ABSPATH') or die();
/**
 * Elementor integration
 * @see /vendor/tangible/template-system/system/integrations/elementor
 */

// Common config for all generated widget
$plugin->elementor_dynamic_config = [
  'category' => [
    'slug'  => 'Tangible',
    'title' => 'Tangible',
    'icon'  => 'fa fa-plug',
  ],
  'icon'  => 'eicon-code-highlight',

  /**
   * Script handle used in enqueue.php, controls/base.php
   */
  'handle'  => 'tangible-blocks-elementor-integration',
];

require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/widgets/index.php';
require_once __DIR__ . '/controls/index.php';

/**
 * Enqueue AJAX and Interface modules
 *
 * In local scope: $ajax, $interface
 */

add_action('elementor/editor/before_enqueue_scripts', $ajax->register_library, 1);
add_action('elementor/editor/before_enqueue_scripts', $ajax->conditional_enqueue_library, 9999);

add_action('elementor/editor/before_enqueue_scripts', $interface->admin_enqueue_modules, 9999);
add_action('elementor/editor/before_enqueue_scripts', $interface->enqueue_modules, 9999);

/**
 * Widgets
 *
 * https://developers.elementor.com/creating-a-new-widget/
 * https://developers.elementor.com/creating-a-new-widget/adding-javascript-to-elementor-widgets/
 * https://developers.elementor.com/add-custom-functionality/#Registering_New_Widgets
 */
add_action( 'elementor/widgets/widgets_registered', function() use ( $plugin ) {

  $plugin->register_dynamic_widgets();
});

/**
 * Categories
 *
 * https://developers.elementor.com/widget-categories/
 */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) use ( $plugin ) {

  $plugin->register_dynamic_category($elements_manager);
});
