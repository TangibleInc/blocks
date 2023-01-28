<?php

defined('ABSPATH') or die();

// local $framework, $plugin

require_once __DIR__ . '/block/index.php';
require_once __DIR__ . '/integrations/index.php';
require_once __DIR__ . '/legacy/index.php';

if( is_admin() ) {
  require_once __DIR__ . '/admin/index.php';
}
