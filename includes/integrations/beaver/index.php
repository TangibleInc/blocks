<?php

namespace Tangible\Template\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

// Common config for all generated modules
$plugin->beaver_dynamic_config = [
  'group'     => 'Tangible Blocks',
  'category'  => 'Tangible Blocks',
  'slug'      => 'tangible-module-',
  'visibility'=> []
];

require_once __DIR__ . '/tangible-base/tangible-base.php';
require_once __DIR__ . '/utils.php';

add_action('init', function() use($plugin) {

  $blocks = $plugin->get_all_blocks();
  foreach( $blocks as $block ) create_module($block);

});
