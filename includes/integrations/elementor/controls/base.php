<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic\Field;

defined('ABSPATH') or die();

/**
 * Register a new control for Elementor
 *
 * @see https://developers.elementor.com/creating-a-new-control/
 * @see /includes/integrations/elementor/dynamic/controls/base.php
 */

$plugin->register_elementor_control = function($config) {

  return new class($config) extends \Elementor\Base_Data_Control {

    public function __construct($config) {

      $this->tangible_config = $config;

      parent::__construct();
    }

    public function get_type() {
      return $this->tangible_config['prefixed_type'];
    }

    public function enqueue() {

      $plugin = \tangible_blocks();
      $template_system = \tangible_template_system();

      $template_system->enqueue_elementor_template_editor();
      $handle = $plugin->elementor_dynamic_config['handle'];

      $control = $plugin->get_control( $this->tangible_config['type'] );
      $control->enqueue_callback( $handle, 'elementor' );
    }

    public function content_template() {
      ?>
      <div>
        <div class="tangible-<?php echo $this->get_type(); ?>" data-field="{{ JSON.stringify(data.field) }}"></div>
        <input type="hidden" class="<?php echo $this->get_type(); ?>-save-value" data-setting="{{ data.name }}"/>
      </div>
      <?php
    }

  };
};
