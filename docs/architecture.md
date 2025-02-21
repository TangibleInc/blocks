# Architecture

## File structure

- `assets`
  
  Frontend and admin assets, such as JS, CSS, and image files.

  - `build` - Bundled and minified files
  - `src` - Source files, such as JSX and Sass

- `docs`

  Documentation in Markdown format.

- `includes`
  - `admin`
  - `block`
  - `integrations`
  - `legacy`

- `vendor/tangible`

  Project dependencies installed with `npm install`. These are included in the plugin zip archive and published.

  - `fields`
  - `framework`
  - `template-system`
  - `updater`

- `vendor/tangible-dev`

  These are dev dependencies installed with `npm run install:dev`. They're excluded from the archive.

  - `advanced-custom-fields`
  - `beaver-builder-lite-version`
  - `blocks-editor`
  - `elementor`
