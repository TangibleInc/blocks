<?php
/**
 * Register post types
 */
add_action('init', function() use ( $template_system ) {

  $template_system->register_template_post_type([
    'post_type'   => 'tangible_block',
    'single'      => 'Block',
    'plural'      => 'Blocks',
    'description' => 'Universal blocks for all page builders supported by Tangible Blocks'
  ]);

}, 9); // Before default 10 for Template System's other post types

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

  if ($is_editable) {
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

/**
 * Extend post types
 * @see /vendor/tangible/template-system/system/post-types/extend.php
 */

add_action('admin_head', function() use ($template_system, $plugin) {

  global $submenu;

  // Remove admin menu items if L&L or Template System plugin is not active

  if (isset($submenu['tangible'])
    && !function_exists('tangible_loops_and_logic')
    && !$template_system->is_plugin
  ) {

    $filtered_items = [];

    // tangible()->see($submenu['tangible']);

    foreach ($submenu['tangible'] as $menu_item) {
      if ($menu_item[3]==='Tangible Blocks'
        || $menu_item[5]==='tangible_template_import_export'
      ) {
        $filtered_items []= $menu_item;
      }
    }

    $submenu['tangible'] = $filtered_items;
  }

  // Archive

  global $pagenow;

  $is_archive = $pagenow==='edit.php'
    && isset($_GET['post_type'])
    && $_GET['post_type']==='tangible_block'
    // && in_array($_GET['post_type'], $template_system->template_post_types)
  ;

  if ( $is_archive ) {

    // For Tangible Block, hide "Add New" button if Block Editor plugin is not installed

    if (!$plugin->has_editor) {
?><style>
.page-title-action {
  display: none;
}
</style>
<?php
    }
    return;
  }

}, 9); // Before default 10 for Template System's other styles
