<?php

namespace Tangible\Blocks\Integrations\Gutenberg\Dynamic;

/**
 * Block output
 *
 * @param array $attributes
 * @param string $content
 * @param array $block
 *
 * @return void
 */
function render( $attributes, $content ) {

  $plugin = tangible_blocks();

  $block_id = isset($attributes['block_id']) ? $attributes['block_id'] : '';
  $universal_id = isset($attributes['universal_id']) ? $attributes['universal_id'] : '';

  $render_data = [
    'content_id'   => $attributes['content_id'],
    'universal_id' => $universal_id,
    'fields'       => [],
    'wrapper'      => 'tangible-block-' . $block_id
  ];

  /**
   * Get template post from universal ID or post ID for backward compatibility
   * @see /includes/template/block/index.php
   */
  $template_post = $plugin->get_block_post_from_settings( $render_data );

  $fields = $plugin->get_block_controls( $render_data );

  if ( ! empty($fields) ) {

    foreach( $fields as $field ) {
      
      if ( ! is_array( $field ) || empty( $field['name'] )) continue;

      $name  = $field['name'];
      $value = $attributes[ $name ] ?? '';

      $control = $plugin->get_control( $field['type'] );
      
      if( $control === false ) continue;

      $render_data['fields'][ $name ] = $control->get_builder_data( $value, 'gutenberg', $field, $attributes );
    }

  }

  /**
   * Use common utility functions for rendering dynamic blocks
   * @see /vendor/template-system/system/integrations/gutenberg/utils.php
   */
  $template_system = tangible_template_system();

  $template_system->before_gutenberg_block_render($attributes);

  ob_start(); ?>
    <div class="<?php echo $render_data['wrapper']; ?>">
      <?php echo $plugin->render( $template_post, $render_data ); ?>
    </div>
  <?php

  $template_system->after_gutenberg_block_render();

  return $template_system->wrap_gutenberg_block_html( ob_get_clean() );
}
