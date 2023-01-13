<?php

defined('ABSPATH') or die();

/**
 * Template controls
 *
 * Register and render control types in supported page builders
 */

$plugin->controls = [];
$plugin->custom_controls = [];

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/format.php';
require_once __DIR__ . '/register.php';

require_once __DIR__ . '/types/index.php';
require_once __DIR__ . '/template/index.php';
require_once __DIR__ . '/utils/index.php';

$plugin->get_control = function(string $type) use($plugin) {
  return $plugin->controls[ $type ] ?? false;
};

// Is going to be replaced by get_controls
$plugin->get_custom_controls = function() use($plugin) {
  return $plugin->custom_controls;
};

$plugin->get_controls = function() use($plugin) {
  return $plugin->controls;
};

do_action( 'tangible_template_controls_registered' );
