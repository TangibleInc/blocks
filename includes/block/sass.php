<?php

namespace Tangible\Blocks\Sass;

defined('ABSPATH') or die();

use Tangible\Blocks\Sass as sass;

/**
 * @see https://sass-lang.com/documentation/values/lists
 */
function to_list(array $values, string $type = 'string', array $map_types = []) : string {

  $list = [];

  foreach( $values as $value ) {

    $is_map = $type === 'map' && is_array($value);

    $list []= $is_map  
      ? sass\to_map($value, $map_types)
      : ($type === 'string' 
        ? '"' . $value .  '"' 
        : $value
      );
  }
  
  return '(' . implode(',', $list) . ')';
}

/**
 * @see https://sass-lang.com/documentation/values/maps
 */
function to_map(array $values, array $types = []) : string {
  
  $map = [];

  foreach( $values as $key => $value ) {

    $type = $types[ $key ] ?? 'string';
    $is_map = ($type === 'map' || is_array($type)) && is_array($value);
    $is_list = $type === 'list' && is_array($value);

    // We can have a map/list inside of map with the repeater control
    if( $is_map ) {
      $value = sass\to_map($value, is_array($type) ? $type : []);
    }
    else if( $is_list ) {
      $value = sass\to_list($value, $types);
    } else {
      $value = ! is_array($value) 
        ? ($type === 'string' 
          ? '"' . $value .  '"' 
          : $value)
        : '""';
    }
    
    $map []= '"' . $key . '":' . $value;
  }

  return '(' . implode(',', $map) . ')';
}
