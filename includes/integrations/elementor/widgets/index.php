<?php

namespace Tangible\Template\Integrations\Elementor\Dynamic;

defined('ABSPATH') or die();

// Common config for all generated widget
$plugin->elementor_dynamic_config = [
  'category' => [
    'slug'  => 'Tangible',
    'title' => 'Tangible',
    'icon'  => 'fa fa-plug',
  ],
  'icon'  => 'eicon-code-highlight'
];

$plugin->elementor_tabs = [];

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/tabs.php';

/**
 * @see https://developers.elementor.com/widget-categories/
 */
$plugin->register_dynamic_category = function($elements_manager) use($plugin) {

  $config = $plugin->elementor_dynamic_config['category'];

  $elements_manager->add_category($config['slug'], $config);
};

/**
 * @see https://code.elementor.com/hooks/elementor-widgets-widgets_registered/
 */
$plugin->register_dynamic_widgets = function() use($plugin) {

  require_once __DIR__ . '/base.php';

  $plugin->elementor_dynamic_config['prefix'] = [
    'slug'     => Base::$slug_prefix,
    'section'  => Base::$section_prefix,
    'control'  => Base::$control_prefix
   ];

  $widgets_manager = &\Elementor\Plugin::instance()->widgets_manager;
  $blocks = $plugin->get_all_blocks();

  foreach( $blocks as $block ) {

    $class_name = create_widget($block);
    $widgets_manager->register_widget_type( new $class_name() );

    // We need to register the settings tab now
    register_tabs( $block );
  }

};

/**
 * Register icon for custom tabs
 */
add_action('elementor/editor/footer', function() use($plugin){

  $selector = '';
  foreach($plugin->elementor_tabs as $tab) {
    if( !empty($selector) ) $selector .= ',';
    $selector .= " .elementor-panel .elementor-panel-navigation .elementor-tab-control-$tab a::before";
  }

  if( empty($selector) ) return;

  ?><style><?php echo $selector; ?> { font-family: 'dashicons'; content: "\f13f"; }</style><?php
});
