module.exports = {
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
    // {
    //   src: 'assets/src/gutenberg-integration/index.scss',
    //   dest: 'assets/build/gutenberg-integration.min.css',
    // },

    // Beaver Builder
    {
      src: 'assets/src/beaver-integration/index.js',
      dest: 'assets/build/beaver-integration.min.js',
      react: 'wp.element',
    },
    // {
    //   src: 'assets/src/beaver-integration/index.scss',
    //   dest: 'assets/build/beaver-integration.min.css',
    // },

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
  ]
}