<?php

defined('ABSPATH') or die();

/**
 * Metabox to enable new block render/controls  
 */

$legacy_meta_name = 'tangible_blocks_use_new_controls';
$nonce_prefix = 'tangible_blocks_legacy_metabox_';

$plugin->block_use_new_controls = function($block_id) use($legacy_meta_name) {
  return get_post_meta( $block_id, $legacy_meta_name, true ) === 'on';
};

/**
 * @see ./vendor/tangible/fields/store.php
 */
$plugin->register_block_meta = function($block_id, $name, $args = []) use($fields) {
  $fields->register_field($name, 
    $args
    + $fields->_store_callbacks['meta']('post', $block_id, $prefix = '')
    + $fields->_permission_callbacks([
      'store' => ['user_can', 'manage_options'],
      'fetch' => ['always_allow']
    ])
  );
};

add_action('add_meta_boxes', function() use($plugin, $fields, $legacy_meta_name, $nonce_prefix) {

  add_meta_box(
    'tangible-block-legacy',
    __( 'New controls', 'tangible-blocks' ),
    function($block) use($plugin, $fields, $legacy_meta_name, $nonce_prefix) {

      $plugin->register_block_meta($block->ID, $legacy_meta_name);
      $fields->set_context('default');

      wp_nonce_field(
        $nonce_prefix . $block->ID, 
        $nonce_prefix . $block->ID,
        false
      );

      ?>

      <!-- Temporary - TODO: move into separate css file -->
      <style>.tangible-block-new-block-switch .tf-switch { display: flex; justify-content: space-between; align-items: center }</style>
      <?php echo $fields->render_field($legacy_meta_name, [
        'label'   => __( 'Enable new controls for this block', 'tangible-blocks' ),
        'wrapper' => [ 'class' => 'tangible-block-new-block-switch' ],
        'type'    => 'switch',
        'value'   => $fields->fetch_value($legacy_meta_name) ? $fields->fetch_value($legacy_meta_name) : 'on',
      ]);
    },
    'tangible_block',
    'side'
  );

});

add_action('save_post', function($block_id, $block) use($legacy_meta_name, $nonce_prefix, $plugin, $fields) {
  
  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
  if( $block->post_type !== 'tangible_block' ) return;

  $nonce_name  = $nonce_prefix . $block->ID;
  $nonce_value = $_POST[ $nonce_name ] ?? false;

  $is_nonce_valid = wp_verify_nonce( $nonce_value, $nonce_name );

  if( ! $is_nonce_valid ) return;

  $is_legacy = $_POST[ $legacy_meta_name ] ?? false;

  if( ! in_array($is_legacy, ['on', 'off']) ) return;

  $plugin->register_block_meta( $block->ID, $legacy_meta_name );
  $fields->store_value( $legacy_meta_name, $is_legacy );

}, 10, 2);
