<?php

defined('ABSPATH') or die();

/**
 * Add Elementor-specific actions to replace wp_head and wp_footer
 *
 * This ensures that template styles and scripts are loaded initially in the page builder.
 *
 * @see vendor/tangible/template/actions/index.php
 * @see https://github.com/elementor/elementor/issues/7174#issuecomment-466746848
 */

add_action('elementor/editor/before_enqueue_scripts', $ajax->register_library, 1);
add_action('elementor/editor/before_enqueue_scripts', $ajax->conditional_enqueue_library, 9999);

add_action('elementor/editor/before_enqueue_scripts', $html->head_action, 99);
add_action('elementor/editor/footer', $html->footer_action, 99);

add_action('elementor/editor/before_enqueue_scripts', $interface->admin_enqueue_modules, 9999);
add_action('elementor/editor/before_enqueue_scripts', $interface->enqueue_modules, 9999);
add_action('elementor/editor/before_enqueue_scripts', $interface->register_modules, 0);

/**
 * Called at the end of $template_system->enqueue_elementor_template_editor()
 * @see /vendor/tangible/template-system/system/integrations/elementor/enqueue.php
 */
add_action('tangible_enqueue_elementor_template_editor', function() use($plugin, $fields) {

  $fields->enqueue();

  wp_enqueue_script(
    $plugin->elementor_dynamic_config['handle'], // See ./index.php
    $plugin->url . 'assets/build/elementor-integration.min.js',
    ['tangible-elementor-template-editor'],
    $plugin->version
  );

  wp_enqueue_style(
    $plugin->elementor_dynamic_config['handle'],
    $plugin->url . 'assets/build/elementor-integration.min.css',
    [],
    $plugin->version
  );

});

add_action('elementor/editor/after_enqueue_scripts', function() use($plugin) {

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->elementor_dynamic_config;
  $handle = $plugin->elementor_dynamic_config['handle'];
  
  $config['controls'] = $plugin->enqueue_controls_data( $handle, 'elementor' );

  /**
   * Visibility conditions
   *
   * Similar logic in /includes/integrations/beaver/dynamic/utils.php
   *
   * Passed to frontend in /assets/src/elementor-template-editor/widgets/dynamic/visibility.js
   */

  $config['visibility'] = [

    'conditions' => $plugin->block_visibility_conditions,

    'tabs'       => [], // { [blockId]: { [tabName]: conditions } }
    'sections'   => [], // { [blockId]: { [sectionName]: conditions } }

    // Controls pass their own conditions in ./base.php, register_controls()
  ];

  $block_custom_tabs = $plugin->get_blocks_with_custom_tabs();

  foreach ($blocks as $block) {

    if( empty($block['tabs']) ) continue;

    $block_id = $plugin->get_block_id( $block );

    $config['visibility']['tabs'][ $block_id ] = [];

    // @see maybe_use_tabs_workaround() in ./base.php
    if( in_array($block_id, $block_custom_tabs) ) {
      $config['visibility']['tabs'][ $block_id ][ 'content' ] = [ 'conditions' => 'default-tab-workaround' ];
    }

    foreach( $block['tabs'] as $tab ) {

      if ( ! empty($tab['conditions']) ) {
        $config['visibility']['tabs'][ $block_id ][ $tab['name'] ] = [
          'conditions' => $tab['conditions'],
        ];
      }

      if( empty($tab['sections']) ) continue;

      foreach ($tab['sections'] as $section) {

        if ( empty($section['conditions']) ) continue;

        $config['visibility']['sections'][ $block_id ][ $section['name'] ] = [
          'conditions' => $section['conditions'],
        ];
      }
    }
  }

  wp_add_inline_script(
    $handle,
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );

});
