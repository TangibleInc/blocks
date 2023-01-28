<?php

defined('ABSPATH') or die();

/**
 * Edit screen - Controls tab and editor
 *
 * HTML editor with dynamic tags to define tabs, sections, and controls.
 *
 * @see /vendor/tangible/template-system/system/editor/fields.php
 */
add_filter( 'tangible_template_editor_tabs', function( $tabs, $post ) {
  if ( $post->post_type === 'tangible_block' ) {
    $tabs [] = 'Controls';
  }
  return $tabs;
}, 10, 2 );

add_action( 'tangible_template_editor_after_tabs', function( $post, $fields ) {

  if ( $post->post_type !== 'tangible_block' ) return;

  $is_editable = apply_filters('tangible_template_editor_tab_editable', false, 'control', $post, $fields );

  if ( $is_editable ) {
    ?>
    <div class="tangible-template-tab tangible-template-editor-container">
      <textarea
        name="controls_template"
        style="display: none"
        data-tangible-template-editor-type="html"
      ><?= htmlspecialchars( $fields['controls_template'] ) ?></textarea>
    </div>
    <?php
  } else {
    ?>
    <div class="tangible-template-tab">
      <pre><code class="tangible-template-editor-locked"><?php
        echo esc_html( $fields['controls_template'] );
      ?></code></pre>
    </div>
    <?php
  }

}, 10, 2 );
