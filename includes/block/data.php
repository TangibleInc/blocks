<?php

/**
 * Block data
 */

$plugin->all_blocks_cache = [];

// Should the block data directly added and cached in the get_all_templates method?
$plugin->get_all_blocks = function() use ($plugin) {

  if ( !empty($plugin->all_blocks_cache) ) return $plugin->all_blocks_cache;

  $templates = $plugin->get_all_templates( 'tangible_block' );
  if( empty($templates) ) return [];

  $blocks = [];
  foreach($templates as $template) {

    $data = $plugin->get_block_data($template);
    if( empty($data) ) continue;

    $blocks []= $data;
  }

  $plugin->all_blocks_cache = $blocks;

  return $blocks;
};

/**
 * Get block data - Cached to avoid re-generating fields
 */
$plugin->get_block_data = function($template) use ($plugin) {

  static $blocks = [];

  if( isset($blocks[ $template['id'] ])) return $blocks[ $template['id'] ];

  $controls_template = !empty($template['controls_template'])
    ? $template['controls_template']
    : '{}'
  ;

  /**
   * Get JSON from controls template - Accepts Human JSON for backward compatibility
   */
  $data = ($controls_template[0]==='{' || $controls_template[0]==='[')
    ? tangible_hjson()->parse($controls_template)
    : $plugin->get_block_controls_template_json($controls_template, $template['id'])
  ;

  // Backward compatibility: Sections can be given as array directly
  if (!isset($data['tabs']) && !isset($data['sections'])) {
    $data['sections'] = $data;
  }

  // Controls outside of any section will be added to default section
  if (isset($data['controls'])) {
    $default_section = [
      'title' => 'Settings',
      'controls' => $data['controls']
    ];

    if (!isset($data['sections'])) $data['sections'] = [];
    array_unshift($data['sections'], $default_section);
  }

  // Sections outside of any tab will be added to default tab
  if( isset($data['sections']) ) {

    $sections = $data['sections'];

    $default_tab = [
      'title'   => 'default', // "default" can refer to a different name according to the builder
      'sections'=> $sections
    ];

    $tabs = [ $default_tab ];

    if (isset($data['tabs'])) {
      foreach ($data['tabs'] as $tab) {
        if ($tab['title']!=='default') {
          $tabs []= $tab;
        }
        // Merge with default section
        if (isset($tab['sections'])) {
          $default_tab['sections'] = array_merge(
            $default_tab['sections'],
            $tab['sections']
          );
        }
      }
    }

    $data = [
      'tabs' => $tabs
    ];
  }

  $tabs = [];

  foreach( $data['tabs'] as $key => $tab ) {

    // Controls outside of any section will be added to default section
    if (isset($tab['controls'])) {

      $tab['sections'] = [
        [
          'title' => 'Settings',
          'controls' => $tab['controls']
        ]
      ];

      unset($tab['controls']);
    }

    if (!isset($tab['sections']) || !is_array($tab['sections'])) continue;

    $sections = [];

    foreach( $tab['sections'] as $section_key => $section ) {

      if (!isset($section['controls']) || !is_array($section['controls'])) continue;
      if (!isset($section['title'])) $section['title'] = 'Settings';

      $sections []= [
        'name'  => 'section-' . $key . '-' . $section_key,
        'label' => $section['title'],
        'fields'=> array_map(function($control) use($plugin) {

          $data = (array) $control;
          if( empty($data['type']) ) return false;

          $control = $plugin->get_control( $data['type'] );
          if( empty($control) ) return false;

          return $control->filter_field_data( $data );

        }, $section['controls']),
        'conditions' => isset($section['conditions'])
          ? $section['conditions']
          : []
      ];
    }

    $tabs []= [
      'name'    => $tab['title'] !== 'default'
        ? 'tab-' . $template['id'] . '-' . $key
        : $tab['title']
      ,
      'label'   => $tab['title'],
      'sections'=> $sections,
      'conditions' => isset($tab['conditions'])
        ? $tab['conditions']
        : []
    ];
  }

  $post_id = $template['id'];
  $name = get_post_field('post_name', $post_id);

  $content_id = $post_id;
  /**
   * Universal ID - Unique and immutable across sites
   * @see /includes/template/universal-id/index.php
   */
  $universal_id = get_post_field('universal_id', $post_id);

  return $blocks[ $post_id ] = [
    'name'        => $name,
    'label'       => $template['title'],

    'content_id'   => $post_id,
    'universal_id' => $universal_id,

    'tabs'        => $tabs
  ];
};
