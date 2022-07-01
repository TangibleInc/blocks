<?php

namespace Tangible\Template;

defined('ABSPATH') or die();

/**
 * Register a new control for blocks
 * 
 * There is different kind of controls: Basic, Alias and Custom
 * 
 * You can refer to the documentation to determine which one is appropriate for your use case
 * 
 * @see https://loop.tangible.one/develop/block-controls
 * 
 * If you need to call those functions outside of tangible-blocks, you must use the tangible_template_controls_registered action
 * 
 * @see /fields/*.php
 */

$plugin->controls = [];
$plugin->control_aliases = [];
$plugin->custom_controls = [];
$plugin->custom_control_prefix = 'tangible_block_control_';

$plugin->register_control = function(string $type, array $builder_types) use($plugin) {

  if( isset($plugin->controls[ $type ]) ) return false;

  // @see /base-control.php
  $control = new Control( $type, $builder_types );
  $plugin->controls[ $type ] = $control;

  return $control;
};

$plugin->register_control_alias = function(string $alias, string $type, array $values) use($plugin) {

  if( isset($plugin->controls[ $alias ]) || !isset($plugin->controls[ $type ]) ) {
    return false;
  }

  $alias_control = clone $plugin->controls[ $type ];
  $alias_control->init_alias($alias, $type, $values);

  $plugin->controls[ $alias ] = $alias_control;
  $plugin->control_aliases[ $alias ] = $type;

  return $alias_control;
};

$plugin->register_custom_control = function(array $config) use($plugin) {

  $type = isset($config['type']) ? $config['type'] : false;

  if( empty($type) ) return false;
  if( isset($plugin->custom_controls[ $type ]) ) return false;

  /**
   * Prefix our generated controls to avoid collision with existing ones in builders
   */
  $prefixed_type = $plugin->custom_control_prefix . $type;
  $config['prefixed_type'] = $prefixed_type;
   
  $plugin->custom_controls[ $type ] = $config;

  $control = $plugin->register_control($type, [
    'elementor'       => $prefixed_type,
    'beaver-builder'  => $prefixed_type,
    'gutenberg'       => 'string'
  ])
    ->elementor(function($field, $type) {
      return [
        'label'   => $field['label'],
        'type'    => $type,
        'field'   => $field,
        'default' => isset($field['default'])
          ? (is_array( $field['default'] ) ? json_encode( $field['default'] ) : $field['default'])
          : ""
      ];
    })
    ->beaver_builder(function($field, $type) {
      return [
        'label'   => $field['label'],
        'type'    => $type,
        'field'   => $field,
        'default' => isset($field['default'])
          ? (is_array( $field['default'] ) ? json_encode( $field['default'] ) : $field['default'])
          : ""
      ];
    })
    ->gutenberg(function($field, $type) {
      return [
        'type'    => $type,
        'default' => isset($field['default'])
          ? (is_array( $field['default'] ) ? json_encode( $field['default'] ) : $field['default'])
          : ""
      ];
    });

    $control->init_custom();

    return $control;
};
