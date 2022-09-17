<?php

defined('ABSPATH') or die();

/**
 * Safely get Elementor control type for field registration
 * @see /fields/*.php
 */

$plugin->has_elementor_controls_manager = class_exists('Elementor\\Controls_Manager');
$plugin->elementor_group_control_prefix = 'group_control_';

$plugin->get_elementor_group_control_type = function($type) use ($plugin) {
  return $plugin->has_elementor_controls_manager
    ? $plugin->elementor_group_control_prefix . call_user_func( '\Elementor\Group_Control_' . $type . '::get_type' )
    : $type // No need for valid value, since Elementor not active
    ;
};

$plugin->is_elementor_group_control = function($name) use($plugin) {
  return strpos( $name, $plugin->elementor_group_control_prefix ) === 0;
};

$plugin->get_elementor_control_type = function($type) use ($plugin) {
  return $plugin->has_elementor_controls_manager
    ? constant( 'Elementor\\Controls_Manager::' . $type )
    : $type // No need for valid value, since Elementor not active
    ;
};
