<?php

defined('ABSPATH') or die();

/**
 * Template controls
 *
 * - Render template control types in supported page builders
 * - Render templates with variables (control values) replaced
 */

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/register.php';
require_once __DIR__ . '/render.php';
require_once __DIR__ . '/control.php';

require_once __DIR__ . '/template/index.php';
require_once __DIR__ . '/utils/index.php';

require_once __DIR__ . '/types/index.php';
require_once __DIR__ . '/custom/index.php';
require_once __DIR__ . '/aliases/index.php';

do_action( 'tangible_template_controls_registered' );
