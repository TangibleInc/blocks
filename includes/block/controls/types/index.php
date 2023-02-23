<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

require_once __DIR__ . '/base/index.php';

require_once __DIR__ . '/button-group.php';
require_once __DIR__ . '/checkbox.php';
require_once __DIR__ . '/color-picker.php';
require_once __DIR__ . '/combo-box.php';
require_once __DIR__ . '/date-picker.php';
require_once __DIR__ . '/dimensions.php';
require_once __DIR__ . '/editor.php';
require_once __DIR__ . '/field-group.php';
require_once __DIR__ . '/file.php';
require_once __DIR__ . '/gallery.php';
require_once __DIR__ . '/gradient.php';
require_once __DIR__ . '/number.php';
require_once __DIR__ . '/radio.php';
require_once __DIR__ . '/repeater.php';
require_once __DIR__ . '/select.php';
require_once __DIR__ . '/text.php';
require_once __DIR__ . '/text-suggestion.php';
require_once __DIR__ . '/toggle.php';

$plugin->register_control( new ButtonGroup );
$plugin->register_control( new Checkbox );
$plugin->register_control( new ColorPicker );
$plugin->register_control( new ComboBox );
$plugin->register_control( new Editor );
$plugin->register_control( new DatePicker );
$plugin->register_control( new Dimensions );
$plugin->register_control( new FieldGroup );
$plugin->register_control( new File );
$plugin->register_control( new Gradient );
$plugin->register_control( new Gallery );
$plugin->register_control( new Number );
$plugin->register_control( new Radio );
$plugin->register_control( new Repeater );
$plugin->register_control( new Select );
$plugin->register_control( new Text );
$plugin->register_control( new TextSuggestion );
$plugin->register_control( new Toggle );
