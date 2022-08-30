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
function render( $attributes, $content, $data ) {

  $plugin = tangible_blocks();

  $block_id = isset($attributes['block_id']) ? $attributes['block_id'] : '';
  $universal_id = isset($attributes['universal_id']) ? $attributes['universal_id'] : '';

  $render_data = [
    'content_id'   => $attributes['content_id'],
    'universal_id' => $universal_id,
    'fields'      => [],
    'wrapper'     => 'tangible-block-' . $block_id
  ];

  /**
   * Get template post from universal ID or post ID for backward compatibility
   * @see /includes/template/block/index.php
   */
  $template_post = $plugin->get_block_post_from_settings( $render_data );

  $fields = $plugin->get_block_controls( $render_data );

  if ( !empty($fields) ) {

    foreach( $fields as $field ) {

      if (!is_array( $field ) || empty(  $field['name'] )) continue;

      $value = isset($attributes[ $field['name'] ])
        ? $attributes[ $field['name'] ]
        : ''
      ;

      $control = $plugin->get_control( $field['type'] );
      if( $control === false ) continue;

      $render_data['fields'][ $field['name'] ] = $control->get_builder_value( $value, 'gutenberg', $field, $attributes );

      // A control can have more than one value
      $sub_values = $control->get_builder_sub_values( 'gutenberg', $field, $attributes );

      $render_data['fields'] = is_array($sub_values)
        ? array_merge( $render_data['fields'], $sub_values )
        : $render_data['fields']
      ;
    }
  }

  $template_system = tangible_template_system();
  $loop = $template_system->loop;

  /**
   * Disable links inside Gutenberg editor preview
   * @see /template-system/system/integrations/gutenberg/disable-links.php
   */
  $template_system->start_disable_links_inside_gutenberg_editor();

  /**
   * Ensure default loop context is set to current post
   * @see /template-system/loop/context/index.php
   */
  if (isset($attributes['current_post_id'])
    && (!empty($post = get_post($attributes['current_post_id'])))
  ) {
    $loop->push_current_post_context($post);
  } else {
    $loop->push_current_post_context();
  }

  ob_start(); ?>
    <div class="<?php echo $render_data['wrapper']; ?>">
      <?php echo $template_system->render_template_post( $template_post, $render_data ); ?>
    </div>
  <?php

  $loop->pop_current_post_context();
  $template_system->stop_disable_links_inside_gutenberg_editor();

  return \ob_get_clean();
}
