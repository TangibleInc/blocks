<?php

namespace Tangible\Blocks\Sass;

defined('ABSPATH') or die();

use Tangible\Blocks\Sass as sass;

/**
 * Example of expected arguments to convert to a sass variable:
 * 
 * - Simple variable (no type, default to string)
 * $args = [ 'value' => 'value'];
 * 
 * - Simple variable, integer
 * $args = [ 
 *   'type' => 'integer' // string, integer, map, list
 *   'value' => '0', 
 * ];
 * 
 * - Map variable
 * $args = [ 
 *   'type'  => 'map',
 *   'value' => [ 
 *     'name_1' => [ 'value' => 'value' ], 
 *     'name_2' => [ 'type' => 'integer', 'value' => '0' ]
 *   ],
 * ];
 * 
 * - List variable
 * $args = [ 
 *   'type'  => 'list',
 *   'value' => [ 
 *     [ 'value' => '0' ], 
 *     [ 'value' => '5' ]
 *   ],
 * ];
 */

function to_variable(array $definition) : string {
  $type = $definition['type'] ?? 'string';
  switch($type) {
    case 'integer':
      return (string) $definition['value'] ?? '0';
    case 'map':
      return sass\to_map($definition['value'] ?? []);
    case 'list':
      return sass\to_list($definition['value'] ?? []);
    case 'string':
    default:
      return (string) $definition['value'] ?? '';
  }
}

/**
 * @see https://sass-lang.com/documentation/values/lists
 */
function to_list(array $definition) : string {

  $list = [];

  foreach( $definition as $name => $args ) {
    $list []= $args['type'] === 'string' 
      ? '"' . sass\to_variable($args) .  '"'
      : sass\to_variable($args);  
  }

  return '(' . implode(',', $list) . ')';
}

/**
 * @see https://sass-lang.com/documentation/values/maps
 */
function to_map(array $definition) : string {
  
  $map = [];

  foreach( $definition as $name => $args ) {
    $value = $args['type'] === 'string' 
      ? '"' . sass\to_variable($args) .  '"'
      : sass\to_variable($args);  

    $map []= '"' . $name . '":' . $value;
  }

  return '(' . implode(',', $map) . ')';
}
