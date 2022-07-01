<?php

defined('ABSPATH') or die();

add_action('elementor/editor/after_enqueue_scripts', function() use($plugin) {

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->elementor_dynamic_config;

  $config['controls'] = $plugin->custom_controls;

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

    /**
     * We can use "content ID" instead of universal ID here, since this part
     * only needs to be unique for current site.
     */
    $block_id = $block['content_id'];

    $config['visibility']['tabs'][ $block_id ] = [];

    // @see maybe_use_tabs_workaround() in ./base.php
    if( in_array($block_id, $block_custom_tabs) ) {
      $config['visibility']['tabs'][ $block_id ][ 'content' ] = [ 'conditions' => 'default-tab-workaround' ];
    }

    foreach( $block['tabs'] as $tab ) {

      if ( !empty($tab['conditions']) ) {
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
    'tangible-elementor-template-editor',
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );
});
