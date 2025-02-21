export default {
  build: [
    // Admin
    {
      src: 'assets/src/admin.scss',
      dest: 'assets/build/admin.min.css'
    },

    // Page buider integrations

    // Gutenberg
    {
      src: 'assets/src/gutenberg-integration/index.js',
      dest: 'assets/build/gutenberg-integration.min.js',
      react: 'wp.element',
    },
    {
      src: 'assets/src/gutenberg-integration/index.scss',
      dest: 'assets/build/gutenberg-integration.min.css',
    },

    // Beaver Builder
    {
      src: 'assets/src/beaver-integration/index.js',
      dest: 'assets/build/beaver-integration.min.js',
      react: 'wp.element',
    },
    {
      src: 'assets/src/beaver-integration/index.scss',
      dest: 'assets/build/beaver-integration.min.css',
    },

    // Elementor
    {
      src: 'assets/src/elementor-integration/index.js',
      dest: 'assets/build/elementor-integration.min.js',
      react: 'wp.element',
    },
    {
      src: 'assets/src/elementor-integration/index.scss',
      dest: 'assets/build/elementor-integration.min.css',
    },
  ],
  format: [
    'includes',
    'assets/src'
  ],
  archive: {
    root: 'tangible-blocks',
    src: [
      '*.php',
      'assets/**',
      'includes/**',
      'vendor/tangible/**',
      'readme.txt'
    ],
    dest: 'publish/tangible-blocks.zip',
    exclude: [
      'assets/src',
      'docs',
      'vendor/tangible/*/vendor',
      'vendor/tangible-dev/',
      '.git',
      '**/artifacts',
      '**/publish',
      '**/node_modules',
      '**/tests',
      '**/*.scss',
      '**/*.jsx',
      '**/*.ts',
      '**/*.tsx',
    ],
    configs: [
      './vendor/tangible/template-system/tangible.config.js'
    ]
  },
  /**
   * Dependencies for production are installed in `vendor/tangible`,
   * included in the zip package to publish. Those for development are
   * in `tangible-dev`, excluded from the archive.
   * 
   * In `.wp-env.json`, these folders are mounted to the virtual file
   * system for local development and testing.
   */
  install: [
    {
      git: 'git@github.com:tangibleinc/fields',
      dest: 'vendor/tangible/fields',
      branch: 'main',
    },
    {
      git: 'git@github.com:tangibleinc/framework',
      dest: 'vendor/tangible/framework',
      branch: 'main',
    },
    {
      git: 'git@github.com:tangibleinc/template-system',
      dest: 'vendor/tangible/template-system',
      branch: 'main',
    },
    {
      git: 'git@github.com:tangibleinc/updater',
      dest: 'vendor/tangible/updater',
      branch: 'main',
    },

    // For dev purpose but install by default because it's required for editing blocks
    {
      git: 'git@github.com:tangibleinc/blocks-editor',
      dest: 'vendor/tangible-dev/blocks-editor',
      branch: 'main',
    },
  ],
  installDev: [
    // Third-party plugins
    {
      zip: 'https://downloads.wordpress.org/plugin/advanced-custom-fields.latest-stable.zip',
      dest: 'vendor/tangible-dev/advanced-custom-fields',
    },
    {
      zip: 'https://downloads.wordpress.org/plugin/beaver-builder-lite-version.latest-stable.zip',
      dest: 'vendor/tangible-dev/beaver-builder-lite-version',
    },
    {
      zip: 'https://downloads.wordpress.org/plugin/elementor.latest-stable.zip',
      dest: 'vendor/tangible-dev/elementor',
    },
  ]
}
