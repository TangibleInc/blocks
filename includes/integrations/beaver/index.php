<?php
/**
 * @see /vendor/tangible/template-system/system/integrations/beaver
 */
namespace Tangible\Blocks\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

// Common config for all generated modules
$plugin->beaver_dynamic_config = [
  'group'     => 'Tangible Blocks',
  'category'  => 'Tangible Blocks',
  'slug'      => 'tangible-module-',
  'visibility'=> [],

  /**
   * Script handle used in enqueue.php
   */
  'handle'  => 'tangible-blocks-beaver-integration',
];

/**
 * Note: Do not change the folder and file name of this base module,
 * Beaver Builder uses them in its registry.
 */
require_once __DIR__ . '/tangible-base/tangible-base.php';

require_once __DIR__ . '/field-types/index.php';
require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/utils.php';

add_action('init', function() use($plugin) {

  $blocks = $plugin->get_all_blocks();
  foreach( $blocks as $block ) create_module($block);

});
