<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic;

defined('ABSPATH') or die();

use Elementor\Controls_Manager as Elementor;

function create_widget($block) {

  // We can't use anonymous classes for this
  $generated_name = uniqid( 'TangibleBlock_' );
  eval('class ' . $generated_name . ' extends \Tangible\Blocks\Integrations\Elementor\Dynamic\Base { static $tangible_block; }');

  $plugin = tangible_blocks();

  $class_name = 'TangibleBlock_' . $plugin->get_block_id( $block );
  $class_name = 'Tangible\Blocks\Integrations\Elementor\Dynamic\\' . $class_name;

  if( class_exists($class_name) ) return;

  class_alias( $generated_name, $class_name);
  $class_name::$tangible_block = $block;

  return $class_name;
}

function register_tabs( $block ) {

  if( empty($block['tabs']) ) return;

  $plugin = tangible_blocks();

  foreach( $block['tabs'] as $tab ) {

    if( $tab['name'] === 'default' ) continue;
    if( isset($plugin->elementor_tabs[ $tab['name'] ]) ) continue;

    $plugin->elementor_tabs []= $tab['name'];
    Elementor::add_tab( $tab['name'], $tab['label'] );
  }
}
