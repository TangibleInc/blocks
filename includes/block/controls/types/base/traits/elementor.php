<?php

namespace Tangible\Blocks\Controls;

defined('ABSPATH') or die();

/**
 * Helpers for defining control in elementor
 */
trait Elementor {

  public $elementor_group_control_prefix = 'group_control_';

  /**
   * Safely get control name from Elementor constants
   */

  function has_elementor_controls_manager(): bool {
    return class_exists( 'Elementor\\Controls_Manager' );
  }

  function get_elementor_control_type(string $type): string {
    return $this->has_elementor_controls_manager()
      ? constant( 'Elementor\\Controls_Manager::' . $type )
      : $type
    ;
  }

  function get_elementor_group_control_type(string $type): string {
    
    if( ! $this->has_elementor_controls_manager() ) return $type;

    $prefix = $this->elementor_group_control_prefix;
    $method = $prefix . '\Elementor\Group_Control_' . $type . '::get_type';

    return $prefix . $method; 
  }

  function is_elementor_group_control(string $type): bool {
    return strpos($type, $this->elementor_group_control_prefix) === 0;
  }

} 
