import path from 'node:path'
import { test, is, ok, run } from 'testra'
import { getServer } from './server.ts'

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
    path: path.join(process.cwd(), 'tests', 'now'),
    phpVersion: process.env.PHP_VERSION || '8.2',
    mappings: process.env.TEST_ARCHIVE
      ? {
          'wp-content/plugins/tangible-blocks': '../../publish/tangible-blocks',
        }
      : {},
    reset: true,
  })

  let result: any

  test('Plugin', async () => {

    result = await wpx`return test\\blocks\\get_active_plugins();`
    // console.log('Active plugins', result)
    is(true, result.includes('now/plugin.php'), 'test plugin is active')

    result = await wpx`return test\\blocks\\get_all_plugins();`
    // console.log('All plugins', result)

    result = await wpx`return test\\blocks\\activate_dependency_plugins();`
    is(true, result, 'activate dependency plugins')
  })

  await import(`../../vendor/tangible/framework/tests/now/index.ts`)

  for (const key of [
    'loop',
    'logic',
    'language',
    'admin',
  ]) {
    await import(`../../vendor/tangible/template-system/tests/${key}/index.ts`)
  }
})

type PluginInfos = {
  [entryFilePath: string]: {
    [key: string]: string
    // [Name] => Hello Dolly
    // [PluginURI] => https://wordpress.org/extend/plugins/hello-dolly/
    // [Version] => 1.6
    // [Description] => This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
    // [Author] => Matt Mullenweg
    // [AuthorURI] => http://ma.tt/
    // [TextDomain] =>
    // [DomainPath] =>
    // [Network] =>
    // [Title] => Hello Dolly
    // [AuthorName] => Matt Mullenweg
  }
}
