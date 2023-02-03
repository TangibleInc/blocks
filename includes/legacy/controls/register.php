<?php

namespace Tangible\Blocks\Legacy;

defined('ABSPATH') or die();

/**
 * Legacy control registration
 * 
 * There is 3 different kind of controls: Basic, Alias and Custom
 * 
 * You can refer to the documentation to determine which one is appropriate for your use case
 * 
 * @see https://loop.tangible.one/develop/block-controls
 */

$plugin->register_legacy_control = function(string $type, array $builder_types) use(&$legacy) {
  
  if( isset($legacy['controls'][ $type ]) ) return false;
  
  // @see /control.php
  $control = new Control( $type, $builder_types );
  $legacy['controls'][ $type ] = $control;

  return $control;
};

$plugin->register_legacy_control_alias = function(string $alias, string $type, array $values) use(&$legacy) {

  if( isset($legacy['controls'][ $alias ]) || ! isset($legacy['controls'][ $type ]) ) {
    return false;
  }

  $alias_control = clone $legacy['controls'][ $type ];
  $alias_control->init_alias($alias, $type, $values);

  $legacy['controls'][ $alias ] = $alias_control;
  $legacy['control_aliases'][ $alias ] = $type;

  return $alias_control;
};

$plugin->custom_legacy_control_prefix = 'tangible_block_legacy_control_';

$plugin->register_legacy_custom_control = function(array $config) use(&$legacy, $plugin) {

  $type = isset($config['type']) ? $config['type'] : false;

  if( empty($type) ) return false;
  if( isset($legacy['custom_controls'][ $type ]) ) return false;

  /**
   * Prefix our generated controls to avoid collision with existing ones in builders
   */
  $prefixed_type = $plugin->custom_legacy_control_prefix . $type;
  $config['prefixed_type'] = $prefixed_type;
   
  $legacy['custom_controls'][ $type ] = $config;

  $control = $plugin->register_legacy_control($type, [
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
