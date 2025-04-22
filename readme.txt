=== Tangible Blocks ===
Stable tag: trunk
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags:



== Description ==



== Installation ==

1. Install & activate in the admin: *Plugins -&gt; Add New -&gt; Upload Plugins*

== Changelog ==

= 4.2.1 =

Release Date: 2025-04-21

- Atomic CSS: Utility classes and variables compatible with Tailwind v4
- Attachment loop type
  - Add field "audio" for audio file metadata from ID3 tags
  - Add field "filepath" for path to file
- Editor: Improve passing language definition of closed tags to formatter
- Elandel (template language in TypeScript): Start Expression and Interactivity modules
- Enable by default new features that reached stability; Can be deactivated in settings page
  - Elementor integration: Use new code editor based on CodeMirror 6
  - Object cache for parsed and pre-processed templates
- Export page: Improve select template types for L&L and Blocks
- Field tag: Support "." dot syntax for subfields (object/array/loop)
- Gutenberg integration: Improve enqueue editor assets in iframe
- Improve compatibility with PHP 8.4
- Improve development setup and tests for supported PHP versions with Docker and wp-env; end-to-end tests with Playwright; and running tests on plugin zip archive before publish
- Layout template type: Improve loading logic to pass through redirects
- REST API: Improve compatibility with Checkview
- Start new features: Content Structure and Form templates; ACF Extended integration; Tangible Fields integration
- WP Grid Builder facet integration with support for pagination; Thanks to @zackpyle!
