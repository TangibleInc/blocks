# Tangible Blocks

#### Source code

https://bitbucket.org/tangibleinc/blocks


## Table of Contents

- [Architecture](architecture)
- [Block controls](block-controls)

## Getting started

```sh
git clone git@bitbucket.org:tangibleinc/tangible-blocks.git
cd tangible-blocks
npm install && composer install
```

## Develop

Build for development - watch files for changes and rebuild

```sh
npm run dev
```

Build for production

```sh
npm run build
```

Format to code standard

```sh
npm run format
```

## Tests

This plugin comes with a suite of unit and integration tests.

`composer install --dev` will install PHPUnit.

To run the tests, we rely on the [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) tool to quickly spin up a local dev and test environment, optionally switching between multiple PHP versions.

Please note that `wp-env` requires Docker to be installed. There are instructions available for installing Docker on [Windows](https://docs.docker.com/desktop/install/windows-install/), [macOS](https://docs.docker.com/desktop/install/mac-install/), and [Linux](https://docs.docker.com/desktop/install/linux-install/).

This repository includes NPM scripts to run the tests with PHP versions 8.2 and 7.4. 

**Note**: We need to maintain compatibility with PHP 7.4, as WordPress itself only has “beta support” for PHP 8.x. See https://make.wordpress.org/core/handbook/references/php-compatibility-and-wordpress-versions/ for more information.

If you’re on Windows, you might have to use [Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/install) to run the tests (see [this comment](https://bitbucket.org/tangibleinc/tangible-fields-module/pull-requests/30#comment-389568162)).

To run the tests with Docker installed:
```
npm install

npm run env:test:7.4
npm run env:test:8.2
```

The version-specific commands take a while to start, but afterwards you can run npm run env:test to re-run tests in the same environment.

To stop the Docker process:
```
npm run env:stop
```

To “destroy” and remove cache:
```
npm run env:destroy
```

### End-to-end tests

The folder `/tests/e2e` contains end-to-end-tests using [Playwright](https://playwright.dev/docs/intro) and [WordPress E2E Testing Utils](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-e2e-test-utils-playwright/).

#### Run

Run the tests. This will start the local WordPress environment with `wp-env` as needed. Then Playwright starts a browser engine to interact with the test site.

```sh
npm run test:e2e
```

The first time you run it, it will prompt you to install the browser engine (Chromium).

```sh
npx playwright install
```

#### Watch mode

There is a "Watch mode", where it will watch the test files for changes and re-run them. 
This provides a helpful feedback loop when writing tests, as a kind of test-driven development. Press CTRL + C to stop the process.

```sh
npm run test:e2e:watch  # Shortcut: npm run tdd
```

A common usage is to have terminal sessions open with `npm run dev` (build assets and watch to rebuild) and `npm run tdd` (run tests and watch to re-run).

#### UI mode

There's also "UI mode" that opens a browser interface to see the tests run.

```sh
npm run test:e2e:ui
```

#### Utilities

Here are the common utilities used to write the tests.

- `test` - https://playwright.dev/docs/api/class-test
- `expect` - https://playwright.dev/docs/api/class-genericassertions
- `admin` - https://github.com/WordPress/gutenberg/tree/trunk/packages/e2e-test-utils-playwright/src/admin
- `page` - https://playwright.dev/docs/api/class-page
- `request` - https://playwright.dev/docs/api/class-apirequestcontext

#### References

Examples of how to write end-to-end tests:

- WordPress E2E tests - https://github.com/WordPress/wordpress-develop/blob/trunk/tests/e2e
- Gutenberg E2E tests - https://github.com/WordPress/gutenberg/tree/trunk/test/e2e
