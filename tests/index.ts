import { test, is, ok, run } from 'testra'
import { getServer } from '../vendor/tangible/template-system/framework/env'

async function ensurePlugin({ wpx }) {
  return wpx/* php */ `

if (!function_exists('tangible_template')) {
  if (!function_exists('activate_plugin')) {
    require ABSPATH . 'wp-admin/includes/plugin.php';
  }
  $result = activate_plugin(ABSPATH . 'wp-content/plugins/tangible-blocks/tangible-blocks.php');
  if (is_wp_error($result)) return $result;
}

if ( !get_option('site_init_done') ) {

  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure('/%postname%/');
  $wp_rewrite->flush_rules();

  update_option('site_init_done', 1);
}

return true;
`
}

/**
 * For syntax highlight of PHP in template strings, install:
 * https://marketplace.visualstudio.com/items?itemName=bierner.comment-tagged-templates
 */
export default run(async () => {
  // Set up server before running tests in Framework
  const {
    php,
    request,
    wpx,
    documentRoot,
    setSiteTemplate,
    resetSiteTemplate,
  } = await getServer({
    phpVersion: process.env.PHP_VERSION || '7.4',
    mappings: process.env.TEST_ARCHIVE
      ? {
          'wp-content/plugins/tangible-blocks': '../publish/tangible-blocks',
        }
      : {},
    reset: true,
  })

  let result: any, template: any

  // Framework
  // await import('../vendor/tangible/template-system/framework/tests/index.ts')

  test('Tangible Blocks -Setup', async () => {
    
    result = await ensurePlugin({ wpx })
    is(true, result, 'activate plugin')

    result = await wpx`return switch_theme('empty-block-theme');`
    is(null, result, 'Activate empty block theme')

    result = await request({
      method: 'GET',
      route: '/',
      format: 'text',
    })

    ok(Boolean(result), 'responds')
    is('<!doc', result.slice(0, 5).toLowerCase(), 'responds with HTML document')
  })

  test('Tangible Blocks - Basic', async () => {

    result = await wpx`return function_exists('tangible_blocks');`
    is(true, result, 'tangible_blocks() exists')

    result = await wpx`return tangible_blocks();`
    // console.log(result)

    is('object', typeof result, 'tangible_blocks() runs')
    is('object', typeof result.controls, 'has controls')

    for (const key of [
      'alignment_matrix', 'button_group',
      'checkbox',         'color_picker',
      'combo_box',        'wysiwyg',
      'date_picker',      'dimensions',
      'field_group',      'file',
      'gradient',         'gallery',
      'number',           'radio',
      'repeater',         'select',
      'text',             'text_suggestion',
      'switch'
    ]) {
      is('object', typeof result.controls[key], `control ${key} exists`)
    }

    /**
     * Create a post and get it
     * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
     */

    const postTitle = 'Test 123'
    result = await wpx/* php */ `
return wp_insert_post([
  'post_type' => 'post',
  'post_status' => 'publish',
  'post_title' => '${postTitle}',
  'post_content' => '',
  'post_excerpt' => '',  
]);`

    is('number', typeof result, 'create post returns ID')

    template = `<Loop type=post id=${result}><Field title /></Loop>`
    result = await wpx`return tangible_template('${template}');`

    is(postTitle, result, 'get test post')
  })

  // await import('./loop/index.ts')
})
