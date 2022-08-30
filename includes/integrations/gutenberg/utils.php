<?php

namespace Tangible\Template\Integrations\Gutenberg\Dynamic;

defined('ABSPATH') or die();

/**
 * Attributes will contain only the information gutenberg needs to know for state management.
 *
 * Information needed for generating the settings fields will be passed in window.Tangible.blocks
 */
function to_attributes( array $data ) {

  $plugin = tangible_blocks();

  $attributes = [
    'config' => array_values([
      'type'    => 'array',
      'default' => $data
    ]),
    'name' => [
      'type'    => 'string',
      'default' => $data['name'],
    ],
    'block_id' => [
      'type' => 'string'
    ],
    // Post ID as "content ID" for backward compatibility
    'content_id' => [
      'type'    => 'integer',
      'default' => (int) $data['content_id'],
    ],
    /**
     * Universal ID - Unique and immutable across sites
     * @see /includes/template/universal-id/index.php
     */
    'universal_id' => [
      'type'    => 'string',
      'default' => isset($data['universal_id'])
        ? $data['universal_id']
        : ''
      ,
    ],
  ];

  $fields = $plugin->get_block_controls( $data );

  if( empty($fields) ) return $attributes;

  foreach( $fields as $field ) {

    $field_args = $plugin->get_builder_args($field, 'gutenberg');
    if( $field_args === false ) continue;

    $attributes[ $field['name'] ] = $field_args;
  }

  return $attributes;
}

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

  /**
   * Disable links inside Gutenberg editor preview
   * @see ../disable-links.php
   */
  $template_system->start_disable_links_inside_gutenberg_editor();

  ob_start(); ?>
    <div class="<?php echo $render_data['wrapper']; ?>">
      <?php echo $plugin->render( $template_post, $render_data ); ?>
    </div>
  <?php

  $template_system->stop_disable_links_inside_gutenberg_editor();

  return \ob_get_clean();
}
