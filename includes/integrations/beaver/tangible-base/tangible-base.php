<?php

namespace Tangible\Blocks\Integrations\Beaver\Dynamic;

defined('ABSPATH') or die();

/**
 * @see https://docs.wpbeaverbuilder.com/beaver-builder/developer/custom-modules/cmdg-02-add-a-module-to-your-plugin
 */
class Base extends \FLBuilderModule {

  static $tangible_block;

  public function __construct() {

    $plugin = tangible_blocks();
    $config = $plugin->beaver_dynamic_config;

    parent::__construct([
      'name'            => static::$tangible_block['label'],
      'description'     => static::$tangible_block['label'],
      'group'           => $config['group'],
      'category'        => $config['category'],
      'dir'             => __DIR__,
      'url'             => plugins_url( '', __FILE__ ),
      'enabled'         => true,
    ]);

    $this->slug = $config['slug'] . $plugin->get_block_id( static::$tangible_block );
  }
}
