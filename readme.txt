=== Tangible Blocks ===
Stable tag: trunk
Requires at least: 4.0
Tested up to: 6.0
Requires PHP: 7
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags:



== Description ==



== Installation ==

1. Install & activate in the admin: *Plugins -&gt; Add New -&gt; Upload Plugins*

== Changelog ==

= 3.0.1 =

Release Date: 2022-10-05

- Calendar loop types
  - Improve handling in case invalid values are passed
  - Week loop: Correctly handle January which can have a week row that starts in the previous year
- HTML Hint: Add exception for Shortcode tag to allow self-closing raw tag
- Loop and Field tags: Get current post context inside builder preview when post status is other than publish
- Template editor: Improve compatibility with Beaver Builder's CSS
- Elementor
  - Fix control conditions for blocks with a uniqid
  - Fix custom tabs when default tab is not used for blocks with a uniqid
- Controls style: Fix style not being enqueued for custom controls

= 3.0.0 =

Release Date: 2022-09-13

- Compatibility with PHP 8
- Compatibility with WordPress 6
- Update template system
- Consolidate namespace to Tangible/Blocks
- Gutenberg and Elementor integration: Ensure default loop context is set to current post in block preview
- Simplify admin menu and import/export
- Block scripts: Define control values as variables
