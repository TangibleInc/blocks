import { test, expect } from '@wordpress/e2e-test-utils-playwright'

/**
 * Tests to exercise the frontend and admin features of Tangible Blocks.
 *
 * Note: To interact with pages, locate elements by user-visible locators like
 * accessible role, instead of CSS selectors which can change.
 *
 * @see https://playwright.dev/docs/locators#locating-elements
 * @see https://playwright.dev/docs/locators#locate-by-role
 * @see https://www.w3.org/TR/html-aria/#docconformance
 */

test.describe('Admin', () => {
  test('Dashboard', async ({ admin, page }) => {
    await admin.visitAdminPage('/')
    const heading = page.getByRole('heading', {
      name: 'Welcome to WordPress',
      level: 2,
    })
    await expect(heading).toBeVisible()
  })

  const plugins = [
    ['Tangible Blocks', 'tangible-blocks/tangible-blocks'],
    ['Elementor', 'elementor/elementor'],
    ['Beaver Builder', 'beaver-builder-lite-version/fl-builder']
  ]

  for (const [pluginTitle, pluginBasename] of plugins) {
    test(`${pluginTitle} installed`, async ({ admin, page, requestUtils }) => {
      await admin.visitAdminPage('/')

      // const plugins = await requestUtils.rest({
      //   path: 'wp/v2/plugins',
      // })
      // expect(plugins).toContain(pluginBasename)
      // console.log('plugins', plugins)

      const result = await requestUtils.rest({
        path: `wp/v2/plugins/${pluginBasename}`,
      })
      // console.log('plugin', result)

      expect(result.plugin).toBe(pluginBasename)
    })

    test(`Activate ${pluginTitle}`, async ({ admin, page, request, requestUtils }) => {
      await admin.visitAdminPage('plugins.php')

      // See if plugin is active or not
      const pluginClasses = await page.evaluate(({ pluginBasename }) => {
        const $row = document.querySelector(
          `[data-plugin="${pluginBasename}.php"]`
        )
        return [...$row.classList]
      }, { pluginBasename })

      if (!pluginClasses.includes('active')) {
        await expect(pluginClasses).toContain('inactive')

        // Find the Activate link

        const activateLink = await page.evaluate(({ pluginBasename }) => {
          const $row = document.querySelector(
            `[data-plugin="${pluginBasename}.php"]`
          )
          const $activate = $row.querySelector('a.edit')
          return $activate?.href
        }, { pluginBasename })

        await expect(activateLink).toBeTruthy()

        // Make a POST request
        await request.post(activateLink)
      }

      const plugin = await requestUtils.rest({
        path: `wp/v2/plugins/${pluginBasename}`,
      })

      expect(plugin.status).toBe('active')
    })
  }
})

test.describe('Admin menu', () => {
  test('Exists', async ({ admin, page }) => {
    await admin.visitAdminPage('/')
    expect(page.getByRole('navigation', { name: 'Main menu' })).toHaveCount(1)
  })

  test('Tangbile', async ({ admin, page }) => {
    await admin.visitAdminPage('/')
    expect(
      page
        .getByRole('navigation', { name: 'Main menu' })
        .getByRole('link', { name: 'Tangible' })
        .first()
    ).toHaveCount(1)
  })

  test('Tangbile -> Blocks', async ({ admin, page }) => {
    await admin.visitAdminPage('/')
    expect(
      page
        .getByRole('link', { name: 'Tangible' })
        .locator('xpath=..')
        .getByRole('link')
        .filter({ hasText: 'Blocks' })
        .first()
    ).toHaveCount(1)
  })
})

test.describe('Block post type', () => {
  test('Archive', async ({ admin, page }) => {
    await admin.visitAdminPage('/edit.php?post_type=tangible_block')
    const heading = await page.getByRole('heading', {
      name: 'Tangible Blocks',
    })
    await expect(heading).toBeVisible()
  })
  test('Add new', async ({ admin, page }) => {
    await admin.visitAdminPage('/post-new.php?post_type=tangible_block')
    const heading = await page.getByRole('heading', {
      name: ' Add New Block ',
    })
    await expect(heading).toBeVisible()
  })
})
