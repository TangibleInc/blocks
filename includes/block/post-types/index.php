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
    && !$template_system->has_plugin['loops']
    // && !$template_system->is_plugin
  ) {

    $filtered_items = [];

    $has_editor = $template_system->has_plugin['blocks_editor'];

    foreach ($submenu['tangible'] as $menu_item) {

      if (!isset($menu_item[2])) continue;

      $is_blocks = $menu_item[2]==='edit.php?post_type=tangible_block';
      $is_import = $menu_item[2]==='tangible_template_import_export';
      $is_category = $menu_item[2]==='edit-tags.php?taxonomy=tangible_template_category';

      if ($is_blocks || $is_import || $is_category) {

        /**
         * Without L&L or Blocks Editor, there is no export or category available
         */
        if (!$has_editor) {
          if ($is_import) {
            $menu_item[0] = $menu_item[3] = 'Import';
          } elseif ($is_category) {
            continue;
          }
        }

        $filtered_items []= $menu_item;
      }
    }

    // tangible()->see($filtered_items);

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
