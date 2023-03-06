<?php

defined('ABSPATH') or die();

/**
 * Called at the end of $template_system->enqueue_gutenberg_template_editor()
 * @see /vendor/tangible/template-system/system/integrations/gutenberg/enqueue.php
 */
add_action('tangible_enqueue_gutenberg_template_editor', function() use ($plugin, $fields) {

  $fields->enqueue();
  
  wp_enqueue_script(
    $plugin->gutenberg_dynamic_config['handle'], // See ./index.php
    $plugin->url . 'assets/build/gutenberg-integration.min.js',
    ['tangible-gutenberg-template-editor'],
    $plugin->version
  );
  
  wp_enqueue_style(
    $plugin->gutenberg_dynamic_config['handle'],
    $plugin->url . 'assets/build/gutenberg-integration.min.css',
    ['wp-edit-blocks'],
    $plugin->version
  );

  // Add inline dynamic block data

  $blocks = $plugin->get_all_blocks();
  $config = $plugin->gutenberg_dynamic_config;

  /**
   * We need to apply formating on new controls before passing info to js
   * 
   * @see /vendor/tangible/fields/format.php
   */
  $blocks = array_map(
    function($block) use($plugin) {

      // @see attributes.php
      if( $block['legacy_controls'] ) return $block;
      
      foreach( $block['tabs'] as &$tab ) {
        foreach( $tab['sections'] as &$section ) {
          foreach( $section['fields'] as &$field ) {
        
            if( ! is_array($field) ) continue;
            
            /**
             * @see ./includes/blocks/controls/format.php
             */
            $field = $plugin->get_builder_args( 
              $field, 
              'gutenberg', 
              $block['content_id'] 
            );

          }
        }
      }
      return $block;
    }, 
    $plugin->get_all_blocks()
  );

  $config['conditions']      = $plugin->block_visibility_conditions;
  $config['controls']        = $plugin->enqueue_controls_data( $config['handle'], 'gutenberg' );
  $config['legacy_controls'] = $plugin->get_legacy_custom_control();

  /**
   * Ensure the field "current_post_id" is a number, as defined in the schema
   * for register_block_type() in ./index.php. get_the_ID() can return false,
   * which makes Gutenberg throw an error, "Invalid parameter(s): attributes".
   */
  $id = get_the_ID();
  if ($id===false) $id = 0;

  $config['current_post_id'] = $id;

  wp_add_inline_script(
    $config['handle'],
    'window.Tangible = window.Tangible || {}; window.Tangible.blocks = ' . json_encode($blocks),
    'before'
  );

  wp_add_inline_script(
    $config['handle'],
    'window.Tangible = window.Tangible || {}; window.Tangible.blockConfig = ' . json_encode($config),
    'before'
  );

});
