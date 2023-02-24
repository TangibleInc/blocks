<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic;

defined('ABSPATH') or die();

use Tangible\Blocks\Legacy\Integrations\Elementor\LegacyTrait;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * @see https://developers.elementor.com/creating-a-new-widget/
 * @see https://stackoverflow.com/a/3303658
 */
class Base extends Widget_Base {

  use LegacyTrait;

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

  public function get_post_id() {
    return static::$tangible_block['content_id'];
  }

  public function get_name() {  
    return static::$slug_prefix . $this->get_block_id();
  }

  public function get_title() {
    return static::$tangible_block['label'];
  }

  public function use_legacy_controls() {
    return static::$plugin->block_use_new_controls( $this->get_post_id() ) === false;
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

  protected function register_control( $field ) {
    
    if( ! is_array($field) ) return false;
    
    $args = self::$plugin->get_builder_args( $field, 'elementor', $this->get_post_id() ); 
    
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
      unset($args['conditions']);
    }

    $name = static::$control_prefix . $field['name'];
    $type = $args['type'] ?? '';

    $this->add_control( $name, $args );
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

        /**
         * Old system (using elementor native controls)
         * 
         * @see ./includes/legacy/integrations/elementor/legacy-trait.php
         */
        if( $this->use_legacy_controls() ) {
          $this->register_legacy_controls( $section['fields'] );
        }
        else {
          foreach( $section['fields'] as $field ) {
            $this->register_control( $field, $this );
          }
        }

        $this->end_controls_section();
      }

    }

    $this->maybe_use_tabs_workaround();
  }

  protected function render() {

    $settings = $this->get_settings_for_display();
    $block_id = static::$tangible_block['content_id'];
    $universal_id = static::$tangible_block['universal_id'] ?? '';

    $render_data = [
      'content_id'   => $block_id,
      'universal_id' => $universal_id,
      'fields'       => [],
      'wrapper'      => 'elementor-element-' . $this->get_id()
    ];

    $fields = self::$plugin->get_block_controls( $render_data );
    $post   = self::$plugin->get_block_post_from_settings( $render_data );

    foreach( $fields as $field ) {
      
      if ( ! is_array($field) ) continue;

      $name  = $field['name'];
      $value = $settings[ static::$control_prefix . $name ] ?? '';
      
      $render_data['fields'][ $name ] = self::$plugin->format_control_value( 
        $value, 'elementor', $field, $settings, $post->ID ?? false
      );
    }

    if( empty($post) ) return;

    $system = self::$template_system;

    // Ensure current post is set for builder preview
    $system->loop->push_current_post_context();
    echo $this->plugin()->render( $post, $render_data );
    $system->loop->pop_current_post_context();
  }

}

Base::$plugin = $plugin;
Base::$template_system = $template_system;
