<?php

namespace Tangible\Blocks\Legacy;

defined('ABSPATH') or die();

/**
 * Legacy controls: 
 * 
 * We changed the way control are defined (it was needed to support the new syntax
 * better)
 * 
 * Legacy controls will stil be used in 2 cases:
 * - With legacy syntaxes ( {{ control-name }} )
 * - When a control is not defined in non-legacy mode yet 
 */

$legacy = [
  'controls'        => [],
  'control_aliases' => [],
  'custom_controls' => []
];

require_once __DIR__ . '/register.php';
require_once __DIR__ . '/control.php';

require_once __DIR__ . '/types/index.php';
require_once __DIR__ . '/custom/index.php';
require_once __DIR__ . '/aliases/index.php';

$plugin->get_legacy_control = function(string $type) use($legacy) {
  return $legacy['controls'][ $type ] ?? false;
};

$plugin->get_legacy_custom_control = function() use($legacy) {
  return $legacy['custom_controls'];
};

// $plugin->legacy = &$legacy;
