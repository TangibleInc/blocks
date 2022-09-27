<?php

defined('ABSPATH') or die();

/**
 * @see https://stackoverflow.com/a/4345578/10491705
 */
$plugin->object_to_array = function($data) {

  if( ! is_array($data) && ! is_object($data) ) return $data;

  $result = [];

  foreach( $data as $key => $value ) {
    $result[$key] = (is_array($value) || is_object($value)) 
      ? object_to_array($value) 
      : $value
    ;
  }

  return $result;
};
