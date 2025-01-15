import type {Config} from '@docusaurus/types';
import type * as Preset from '@docusaurus/preset-classic';
import {themes as prismThemes} from 'prism-react-renderer';

const title = 'Tangible Blocks'

// https://docusaurus.io/docs/next/api/themes/configuration
const config: Config = {
  title,
  tagline: 'Documentation for Tangible Blocks',

  favicon: 'img/favicon.ico',

  // Set the production url of your site here
  url: 'https://docs.tangibleblocks.com',

  // Set the /<baseUrl>/ pathname under which your site is served
  // For GitHub pages deployment, it is often '/<projectName>/'
  baseUrl: '/',

  // GitHub pages deployment config.
  // If you aren't using GitHub pages, you don't need these.
  organizationName: 'tangibleinc', // Usually your GitHub org/user name.
  projectName: 'blocks-docs', // Usually your repo name.

  onBrokenLinks: 'warn',
  onBrokenMarkdownLinks: 'warn',

  // Even if you don't use internationalization, you can use this field to set
  // useful metadata like html lang. For example, if your site is Chinese, you
  // may want to replace "en" with "zh-Hans".
  // i18n: {
  //   defaultLocale: 'en',
  //   locales: ['en'],
  // },

  plugins: [
    'docusaurus-plugin-sass',
    'docusaurus-lunr-search',
  ],
  presets: [
    [
      'classic',
      {
        theme: {
          customCss: './style.scss',
        },
        // https://docusaurus.io/docs/next/api/plugins/@docusaurus/plugin-content-docs
        docs: {
          path: './pages',

          sidebarPath: './sidebars.ts',
          routeBasePath: '',
          breadcrumbs: false,

          // Please change this to your repo.
          // Remove this to remove the "edit this page" links.
          // editUrl: 'https://github.com/tangibleinc/design/tree/main/packages/create-docusaurus/templates/shared/',
        },
        // https://docusaurus.io/docs/next/api/plugins/@docusaurus/plugin-content-pages
        pages: {
          path: './app',
        },

        // blog: {
        //   showReadingTime: true,
        //   feedOptions: {
        //     type: ['rss', 'atom'],
        //     xslt: true,
        //   },
        //   // Please change this to your repo.
        //   // Remove this to remove the "edit this page" links.
        //   editUrl:
        //     'https://github.com/tangibleinc/design/tree/main/packages/create-docusaurus/templates/shared/',
        //   // Useful options to enforce blogging best practices
        //   onInlineTags: 'warn',
        //   onInlineAuthors: 'warn',
        //   onUntruncatedBlogPosts: 'warn',
        // },

      } satisfies Preset.Options,
    ],
  ],

  themeConfig: {
    colorMode: {
      defaultMode: 'dark', // 'light'
    },
    image: 'img/logo.png',
    navbar: {
      title,
      logo: {
        alt: 'Logo',
        src: 'img/logo.svg',
        width: 24,
      },
      items: [
        // {
        //   type: 'docSidebar',
        //   sidebarId: 'mainSidebar',
        //   position: 'left',
        //   label: 'Documentation',
        // },

        // {to: '/blog', label: 'Blog', position: 'left'},

        {
          href: 'https://github.com/tangibleinc/blocks-docs',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'light',
      links: [
        // {
        //   title: 'Docs',
        //   items: [
        //     {
        //       label: 'Tutorial',
        //       to: '/intro',
        //     },
        //   ],
        // },
        // {
        //   title: 'Community',
        //   items: [
        //     {
        //       label: 'Stack Overflow',
        //       href: 'https://stackoverflow.com/questions/tagged/docusaurus',
        //     },
        //     {
        //       label: 'Discord',
        //       href: 'https://discordapp.com/invite/docusaurus',
        //     },
        //     {
        //       label: 'Twitter',
        //       href: 'https://twitter.com/docusaurus',
        //     },
        //   ],
        // },
        // {
        //   title: 'Links',
        //   items: [
        //     // {
        //     //   label: 'Blog',
        //     //   to: '/blog',
        //     // },
        //     {
        //       label: 'GitHub',
        //       href: 'https://github.com/tangibleinc/docusaurus',
        //     },
        //   ],
        // },
      ],
      copyright: `Copyright © ${new Date().getFullYear()} Tangible, Inc.`,
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
    },
  } satisfies Preset.ThemeConfig,
};

export default config;
