<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic;

defined('ABSPATH') or die();

/**
 * @see https://developers.elementor.com/creating-a-new-widget/
 * @see https://stackoverflow.com/a/3303658
 */
class Base extends \Elementor\Widget_Base {

  static $slug_prefix    = 'tangible_widget_';
  static $section_prefix = 'tangible_section_';
  static $control_prefix = 'tangible_control_';

  static $plugin;
  static $template_system;

  private function plugin() {
    return tangible_blocks();
  }

  public function get_name() {
    /**
     * Universal ID - Unique and immutable across sites
     * @see /includes/template/universal-id/index.php
     */
    return static::$slug_prefix
      . (!empty(static::$tangible_block['universal_id'])
        ? static::$tangible_block['universal_id']
        : static::$tangible_block['content_id']
      )
    ;
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
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT
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
            'tab'   => $tab['name'] !== 'default' ? $tab['name'] : \Elementor\Controls_Manager::TAB_CONTENT,
          ]
        );

        foreach( $section['fields'] as $field ) {

          $args = self::$plugin->get_builder_args($field, 'elementor');
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
            ? $this->add_tangible_group_control( $control_name, $args )
            : $this->add_control( $control_name, $args );
          ;
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
      'fields'      => [],
      'wrapper'     => 'elementor-element-' . $this->get_id()
    ];

    $fields = self::$plugin->get_block_controls( $render_data );

    foreach( $fields as $field ) {

      if (!is_array($field)) continue;

      $control_name = static::$control_prefix . $field['name'];

      $value = isset($settings[ $control_name ]) ? $settings[ $control_name ] : '';

      $control = self::$plugin->get_control( $field['type'] );
      if( $control === false ) continue;

      $render_data['fields'][ $field['name'] ] = $control->get_builder_value( $value, 'elementor', $field, $settings );

      // A control can have more than one value
      $sub_values = $control->get_builder_sub_values( 'elementor', $field, $settings );

      $render_data['fields'] = is_array($sub_values)
        ? array_merge( $render_data['fields'], $sub_values )
        : $render_data['fields']
      ;
    }

    $post = self::$plugin->get_block_post_from_settings( $render_data );
    
    if (!empty($post)) {

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
