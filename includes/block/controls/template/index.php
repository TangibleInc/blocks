<?php

/**
 * Local tags for block control template
 */

$plugin->block_controls_template_tags = [];

$visibility = require_once __DIR__ . '/visibility.php';

foreach ([
  'Tab' => 'tabs',
  'Section' => 'sections',
  'Control' => 'controls',
] as $tag_name => $property_name) {

  $plugin->block_controls_template_tags[ $tag_name ] = [
    'callback' => function($atts, $nodes) use ($html, $property_name, $visibility) {

      // Create a map

      $html->map_tag([ 'keys' => ['_current'] ], $nodes);

      $item = isset($html->current_map['_current']) ? $html->current_map['_current'] : [];

      unset($html->current_map['_current']);

      // Assign tag attributes like "title" to map

      foreach ($atts as $key => $value) {
        if ($key==='keys') continue;

        if ($key==='type') {
          /**
           * Backward compatibility with old control type names
           * Control type names are now "snake_case", instead of "kebab-case".
           */
          if ($value==='wysiwyg') $value = 'editor';
          else $value = str_replace('-', '_', $value);
        }

        $item[ $key ] = $value;
      }

      if (empty($item)) return;

      // Append it to current map property

      if (!isset($html->current_map[ $property_name ])) {
        $html->current_map[ $property_name ] = [];
      }

      // Visibility conditions for this tab/section/control
      if ( $visibility->has_conditions() ) {
        $item['conditions'] = $visibility->active_conditions();
      }

      $html->current_map[ $property_name ] []= $item;
    },
  ];
}

$plugin->block_controls_template_tags['If'] = [
  'callback' => function($atts, $nodes) use($html, $visibility) {

    // Support regular If tag on server side
    if (!isset($atts['control'])) {
      return $html->if_tag($atts, $nodes);
    }

    unset($atts['keys']);

    $visibility->start_condition( $atts );

    $html->map_tag([ 'keys' => ['_current'] ], $nodes); // Evaluates child nodes

    $visibility->end_condition();

    $item = isset($html->current_map['_current']) ? $html->current_map['_current'] : [];

    unset($html->current_map['_current']);

    if (empty($item)) return;

    foreach (['controls', 'sections', 'tabs'] as $key) {

      $collected = isset($item[ $key ]) ? $item[ $key ] : [];
      if (empty($collected)) continue;

      $html->current_map[ $key ] = isset($html->current_map[ $key ])
        ? array_merge($html->current_map[ $key ], $item[ $key ])
        : $item[ $key ]
      ;
    }
  }
];

$plugin->block_controls_template_tags['Else'] = [
  'callback' => function($atts, $nodes) use($plugin, $html, $visibility) {

    unset($atts['keys']);

    $visibility->add_condition_level( $atts );
  }
];

/**
 * Get JSON from block controls template
 * @see /includes/template/controls/data.php, get_block_data()
 */

$plugin->get_block_controls_template_json = function($template, $block_id) use ($plugin, $html, $visibility) {

  $visibility->init( $block_id );

  $html->render('<Map _current>' . $template . '</Map>', [
    'local_tags' => &$plugin->block_controls_template_tags
  ]);

  $json = $html->get_map('_current');

  return $json;
};
