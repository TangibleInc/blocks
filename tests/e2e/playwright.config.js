import path from 'node:path'
import { fileURLToPath } from 'url'
import { createConfig } from '../../vendor/tangible/framework/playwright/config.js'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export default createConfig({
  testDir: __dirname,
  testMatch: '**/*.js'
})
