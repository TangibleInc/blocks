<?php
/**
 * @see /vendor/tangible/template-system/system/post-types
 */

add_filter('tangible_template_post_types', function($post_types) {
  $post_types []= 'tangible_blocks';
  return $post_types;
}, 10, 1);

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
 * Extend post types
 * @see /vendor/tangible/template-system/system/post-types/extend.php
 */

add_action('admin_head', function() use ($template_system) {

  global $submenu;

  if (isset($submenu['tangible'])
    && !function_exists('tangible_loops_and_logic')
    && !$template_system->is_plugin
  ) {

    // Remove admin menu items if L&L or Template System plugin is not active

    $filtered_items = [];

    // tangible()->see($submenu['tangible']);

    foreach ($submenu['tangible'] as $menu_item) {
      if ($menu_item[3]==='Tangible Blocks') {
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

    if (!function_exists('tangible_blocks_editor')) {
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
