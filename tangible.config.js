import templateSystemConfig from "./vendor/tangible/template-system/tangible.config.js";

const relativeToTemplateSystem = (pattern) =>
  `./vendor/tangible/template-system/${pattern.replace("^/", "")}`;

export default {
  build: [
    // Admin
    {
      src: "assets/src/admin.scss",
      dest: "assets/build/admin.min.css",
    },

    // Page buider integrations

    // Gutenberg
    {
      src: "assets/src/gutenberg-integration/index.js",
      dest: "assets/build/gutenberg-integration.min.js",
      react: "wp.element",
    },
    {
      src: "assets/src/gutenberg-integration/index.scss",
      dest: "assets/build/gutenberg-integration.min.css",
    },

    // Beaver Builder
    {
      src: "assets/src/beaver-integration/index.js",
      dest: "assets/build/beaver-integration.min.js",
      react: "wp.element",
    },
    {
      src: "assets/src/beaver-integration/index.scss",
      dest: "assets/build/beaver-integration.min.css",
    },

    // Elementor
    {
      src: "assets/src/elementor-integration/index.js",
      dest: "assets/build/elementor-integration.min.js",
      react: "wp.element",
    },
    {
      src: "assets/src/elementor-integration/index.scss",
      dest: "assets/build/elementor-integration.min.css",
    },
  ],
  format: ["includes", "assets/src"],
  install: [
    {
      git: "git@github.com:tangibleinc/template-system",
      branch: "main",
    },
    {
      git: "git@github.com:/tangibleinc/plugin-updater",
      branch: "main",
    },
    {
      git: "git@github.com:/tangibleinc/fields",
      branch: "main",
    },
    {
      url: "https://downloads.wordpress.org/plugin/elementor.latest-stable.zip",
    },
    {
      url: "https://downloads.wordpress.org/plugin/beaver-builder-lite-version.latest-stable.zip",
    },
  ],
  archive: {
    src: [
      "*.php",
      "readme.txt",
      "assets/**",
      "includes/**",
      "vendor/tangible/plugin-updater/**",
      "vendor/tangible/fields/**",
      ...templateSystemConfig.archive.src.map(relativeToTemplateSystem),
    ],
    dest: "publish/tangible-blocks.zip",
    exclude: [
      ...templateSystemConfig.archive.exclude.map(relativeToTemplateSystem),
    ],
    rootFolder: "tangible-blocks",
  },
};
