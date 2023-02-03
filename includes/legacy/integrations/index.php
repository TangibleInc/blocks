<?php

defined('ABSPATH') or die();

if ( class_exists( 'Elementor\\Plugin' ) ) {
  require_once __DIR__ . '/elementor/index.php'; 
}

if ( class_exists( 'FLBuilder' ) ) {
  require_once __DIR__ . '/beaver/index.php'; 
}
