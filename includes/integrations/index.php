<?php

defined('ABSPATH') or die();

if ( function_exists( 'has_blocks' ) ) {
  require_once __DIR__.'/gutenberg/index.php';
}

if ( class_exists( 'Elementor\\Plugin' ) ) {
  require_once __DIR__.'/elementor/index.php';
}

if ( class_exists( 'FLBuilder' ) ) {
  require_once __DIR__.'/beaver/index.php';
}
