<?php

namespace Tangible\Blocks\Integrations\Elementor\Dynamic\Field;

defined('ABSPATH') or die();

use Tangible\Blocks\Controls\Base;
use \Elementor\Base_Data_Control;

/**
 * Register a new control for Elementor
 *
 * @see https://developers.elementor.com/creating-a-new-control/
 * @see /includes/integrations/elementor/dynamic/controls/base.php
 */

$plugin->register_elementor_control = function($control) {

  return new class($control) extends Base_Data_Control {

    public function __construct(Base $control) {
   
      $this->type = $control->type;
      $this->prefixed_type = $control->get_prefixed_type();

      parent::__construct();
    }

    public function get_type() {
      return $this->prefixed_type;
    }

    public function enqueue() {

      $plugin = \tangible_blocks();
      $template_system = \tangible_template_system();

      $template_system->enqueue_elementor_template_editor();
      $handle = $plugin->elementor_dynamic_config['handle'];

      $control = $plugin->get_control( $this->type );
      $control->enqueue( $handle, 'elementor' );
    }

    public function content_template() {
      ?>
      <div>
        <div class="<?php echo $this->get_type(); ?>" data-field="{{ JSON.stringify(data.data) }}"></div>
        <input type="hidden" class="<?php echo $this->get_type(); ?>-save-value" data-setting="{{ data.name }}"/>
      </div>
      <?php
    }

  };
};
