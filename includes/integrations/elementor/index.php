<?php 

defined('ABSPATH') or die();

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

  /**
   * Script handle used in enqueue.php, controls/base.php
   */
  'handle' => 'tangible-blocks-elementor-integration',
  'icon'   => 'eicon-code-highlight',
];

require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/widgets/index.php';
require_once __DIR__ . '/controls/index.php';

/**
 * Widgets
 *
 * https://developers.elementor.com/creating-a-new-widget/
 * https://developers.elementor.com/creating-a-new-widget/adding-javascript-to-elementor-widgets/
 * https://developers.elementor.com/add-custom-functionality/#Registering_New_Widgets
 */
add_action( 'elementor/widgets/register', function( $widgets_manager ) use ( $plugin ) {

  $plugin->register_dynamic_widgets($widgets_manager);
  
});

/**
 * Categories
 *
 * https://developers.elementor.com/widget-categories/
 */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) use ( $plugin ) {

  $plugin->register_dynamic_category($elements_manager);

});
