<?php

namespace Tangible\Blocks\Legacy\Integrations\Elementor;

defined('ABSPATH') or die();

/**
 * Contain deprecetd method used by the elementor widget class to generate legacy fields
 */
trait LegacyTrait {

  public function register_legacy_controls( $fields ) {

    foreach( $fields as $field ) {

      $args = self::$plugin->get_builder_args( 
        $field, 
        'elementor', 
        $this->get_post_id() 
      );
      
      if( $args === false ) continue;

      /**
       * We can add un-elementor infos in this array, and it will
       * still be passed by Elementor to the JS
       *
       * We will use this to handle our custom visbility system
       *
       * @see /assets/src/elementor-template-editor/widgets/dynamic/visibility.js
       */
      if ( isset($field['conditions']) ) {
        $args['tangible_conditions'] = $field['conditions'];
      }

      $control_name = static::$control_prefix . $field['name'];

      self::$plugin->is_elementor_group_control( $args['type'] )
        ? $this->add_legacy_tangible_group_control( $control_name, $args )
        : $this->add_control( $control_name, $args );
      ;
    }
  }

  protected function add_legacy_tangible_group_control( $control_name, $args ) {

    $group_control_prefix = self::$plugin->elementor_group_control_prefix;

    $type = str_replace($group_control_prefix, '', $args['type']);

    $args['name'] = $control_name;
    unset($args['type']);

    $this->add_group_control( $type, $args );

    foreach( $args['include'] as $name ) {
      $this->update_control( $control_name . '_' . $name, [
        'condition'   => [],
        'render_type' => 'template',
      ]);
    }
  }

}
