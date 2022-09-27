<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic;

defined('ABSPATH') or die();

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * @see https://developers.elementor.com/creating-a-new-widget/
 * @see https://stackoverflow.com/a/3303658
 */
class Base extends Widget_Base {

  static $slug_prefix    = 'tangible_widget_';
  static $section_prefix = 'tangible_section_';
  static $control_prefix = 'tangible_control_';

  static $plugin;
  static $template_system;

  private function plugin() {
    return tangible_blocks();
  }

  public function get_block_id() {
    return static::$plugin->get_block_id( static::$tangible_block );
  }

  public function get_name() {  
    return static::$slug_prefix . $this->get_block_id();
  }

  public function get_title() {
    return static::$tangible_block['label'];
  }

  /**
   * Class name of an eicon or font-awesome icon
   *
   * @see https://elementor.github.io/elementor-icons/
   */
  public function get_icon() {
    return self::$plugin->elementor_dynamic_config['icon'];
  }

  /**
   * @see https://developers.elementor.com/widget-categories/
   */
  public function get_categories() {
    return [ self::$plugin->elementor_dynamic_config['category']['slug'] ];
  }

  /**
   * It's possible to create custom tabs in Elementor:
   *
   * However, if we don't have the tab default "content tab" set we can't access our custom tabs and get
   * an error, like in this issue:
   *
   * @see https://github.com/elementor/elementor/issues/10511
   *
   * I don't know if it's a bug or a limitation, the only possible fix for now is to load the content
   * section in all cases, even if there is no control inside it
   *
   * Current workaround is to contextually hide the content tab using js
   *
   * @see ./tabs.php
   */
  public function maybe_use_tabs_workaround() {

    $tabs = static::$tangible_block['tabs'];

    $tabs_name = array_map( function($tab) { return $tab['name']; }, $tabs );
    if( in_array('default', $tabs_name) || empty($tabs_name) ) return;

    $this->start_controls_section(
      static::$section_prefix . 'tangible_default',
      [
        'tab' => Controls_Manager::TAB_CONTENT
      ]
    );
    $this->end_controls_section();
  }

  protected function add_tangible_group_control( $control_name, $args ) {

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

  /**
   * @see https://developers.elementor.com/docs/controls/classes/control-repeater/
   */
  protected function add_repeater( $name, $args ) {

    $controls = $args['controls'] ?? false;

    if( ! is_array($controls) ) return;

    $repeater = new Repeater();

    foreach( $controls as $control ) {
      $this->register_control( $control, $repeater );
    }

    $this->add_control($name, [
      'label'  => $args['label'] ?? '',
      'type'   => Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
		]);

  }

  /**
   * $parent is either $this or a Repeater instance
   */
  protected function register_control( $field, $parent ) {
    
    if( ! is_array($field) ) return false;

    $args = self::$plugin->get_builder_args( $field, 'elementor', $this->get_block_id() ); 
    
    if( $args === false ) return false;

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

    $name = static::$control_prefix . $field['name'];
    $type = $args['type'] ?? '';

    if( self::$plugin->is_elementor_group_control( $type ) ) {
      $parent->add_tangible_group_control( $name, $args );
    }
    else if( $type === 'repeater' ) {
      $parent->add_repeater( $name, $args );
    }
    else {
      $parent->add_control( $name, $args ); 
    }
  }

  /**
   * @see https://developers.elementor.com/elementor-controls/
   */
  protected function register_controls() {

    $tabs = static::$tangible_block['tabs'];

    foreach( $tabs as $key => $tab ) {

      foreach( $tab['sections'] as $section ) {

        $this->start_controls_section(
          static::$section_prefix . $section['name'],
          [
            'label' => $section['label'],
            'tab'   => $tab['name'] !== 'default' ? $tab['name'] : Controls_Manager::TAB_CONTENT,
          ]
        );

        foreach( $section['fields'] as $field ) {

          $this->register_control( $field, $this );

        }

        $this->end_controls_section();
      }

    }

    $this->maybe_use_tabs_workaround();
  }

  protected function render() {

    $settings = $this->get_settings_for_display();
    $block_id = static::$tangible_block['content_id'];
    $universal_id = isset(static::$tangible_block['universal_id'])
      ? static::$tangible_block['universal_id']
      : ''
    ;

    $render_data = [
      'content_id'   => $block_id,
      'universal_id' => $universal_id,
      'fields'       => [],
      'wrapper'      => 'elementor-element-' . $this->get_id()
    ];

    $fields = self::$plugin->get_block_controls( $render_data );

    foreach( $fields as $field ) {
      
      if ( ! is_array($field) ) continue;

      $name  = $field['name'];
      $value = $settings[ static::$control_prefix . $name ] ?? '';
      
      $render_data['fields'][ $name ] = self::$plugin->format_control_value( 
        $value, 'elementor', $field, $settings 
      );
    }

    $post = self::$plugin->get_block_post_from_settings( $render_data );
    
    if ( ! empty($post) ) {

      $system = self::$template_system;

      // Ensure current post is set for builder preview
      $system->loop->push_current_post_context();
      echo $this->plugin()->render( $post, $render_data );
      $system->loop->pop_current_post_context();
    }
  }

}

Base::$plugin = $plugin;
Base::$template_system = $template_system;
