<?php

namespace Tangible\Blocks\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

use FLBuilder;

/**
 * @see https://beaverplugins.com/docs/toolbox/the-alias-settings-form/
 */
function create_module($block) {

  $generated_name = uniqid( 'TangibleBlock_' );
  eval( 'class ' . $generated_name . ' extends \Tangible\Blocks\Integrations\Beaver\Dynamic\Base { static $tangible_block; }' );
  $generated_name::$tangible_block = $block;

  $plugin = tangible_blocks();

  $class_name = 'TangibleBlock_' . $plugin->get_block_id( $block );
  $class_name = 'Tangible\Blocks\Integrations\Beaver\Dynamic\\' . $class_name;

  if( class_exists($class_name) ) return;

  class_alias( $generated_name, $class_name );

  FLBuilder::register_module( $class_name, format_settings( $block ) );
}

