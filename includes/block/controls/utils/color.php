<?php

defined('ABSPATH') or die();

/**
 * @see https://stackoverflow.com/a/17115500/10491705
 */
$plugin->hex_to_rgba = function($value) {

  $hex_value = ltrim($value, '#');

  if( strlen($hex_value) !== 8 && strlen($hex_value) !== 6 ) return $value;

  // hexa values with 6 digit don't have opacity, we need to add it
  if( strlen($hex_value) === 6 ) {
    $hex_value = $hex_value . 'FF';
  }

  list($r, $g, $b, $a) = array_map('hexdec', str_split($hex_value, 2));

  // Convert opacity to a value between 0 and 1
  $a = round($a / 255, 1);

  return "rgba( $r, $g, $b, $a )";
};

$plugin->rgba_to_hex = function($rgba_value) {

  $rgba_value = substr($rgba_value, 0, 4) === 'rgba' 
    ? str_replace('rgba(', '', $rgba_value) 
    : str_replace('rgb(', '', $rgba_value)
  ;

  $rgba_value = str_replace(')', '', $rgba_value);
  $value = explode(",",$rgba_value,3);

  if(strpos($value[2], ',') !== false) $value[2] = strtok($value[2], ',');

  $hex = '#';
  foreach ($value as $val) {
    $val = dechex($val);
    if (strlen($val)<2) $val = '0'.$val;
    $hex = $hex . $val;
  }

  return $hex;
};

$plugin->is_valid_color = function($value) use($plugin) {

  if( ! is_string($value) ) return false;
  
  // Spaces can make regex check fail (Elementor adds some)
  $value = str_replace(' ', '', $value);

  if( empty($value) ) return false;

  if( $plugin->is_rgba($value) || $plugin->is_rgb($value) || $plugin->is_hsl($value) ) {
    return true;
  } 

  // If not rgba, only other solution is #HEX value (we don't support full letter colors)

  if( substr($value, 0, 1) !== '#' ) return false;
  
  return ctype_xdigit( substr($value, 1) );
};

/**
 * @see https://regex101.com/r/O581sO/1/
 */
$plugin->is_valid_gradient = function(string $value) {

  // Spaces can make regex check fail
  $value = str_replace(' ', '', $value);

  if( empty($value) ) return false;

  if( preg_match('/^linear-gradient\([^(]*(\([^)]*\)[^(]*)*[^)]*\)$/', $value) ) return true;
  if( preg_match('/^radial-gradient\([^(]*(\([^)]*\)[^(]*)*[^)]*\)$/', $value) ) return true;
  if( preg_match('/^conic-gradient\([^(]*(\([^)]*\)[^(]*)*[^)]*\)$/', $value) ) return true;
  
  return false;
};

$plugin->get_color_format = function($value) use($plugin) {

  if( ! $plugin->is_valid_color($value) ) return false;

  if( $plugin->is_rgb($value) ) return 'rgb';
  if( $plugin->is_rgba($value) ) return 'rgb';
  if( $plugin->is_hsl($value) ) return 'hsl';

  return 'hex';
};

/**
 * Check if valid rgb or rgba
 * 
 * @see https://stackoverflow.com/a/43706402
 */

$plugin->is_rgba = function($value) {
  return preg_match( '/^rgba\((\s*\d+\s*,){3}[\d\.]+\)$/', str_replace(' ', '', $value) );
};

$plugin->is_rgb = function($value) {
  return preg_match( '/^rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)$/', str_replace(' ', '', $value) );
};

$plugin->is_hsl = function($value) {
  return preg_match( '/^hsl\((\d+(?:[\.\,]\d+)?),(\d+(?:[\.\,]\d+)?)%,(\d+(?:[\.\,]\d+)?)%\)$/', str_replace(' ', '', $value) );
};
