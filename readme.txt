=== Tangible Blocks ===
Stable tag: trunk
Requires at least: 6.0
Tested up to: 6.1
Requires PHP: 7.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags:



== Description ==



== Installation ==

1. Install & activate in the admin: *Plugins -&gt; Add New -&gt; Upload Plugins*

== Changelog ==

= 3.1.7 =

Release Date: 2023-03-14

- Gutenberg integration
  - Improve content filter logic to protect template HTML
    - Ensure it applies only when inside do_blocks before do_shortcode
    - Support block themes
  - Improve getting current post ID when inside builder
- Elementor integration:
  - Fix error when using wrong control name
- New controls:
  - Repeater: Add support for nested repeaters
  - Repeater: Add duplicate button
  - Gradient: Correct spelling
- Style variables: Improve variable type detection  

= 3.1.6 =

Release Date: 2023-03-07

- Block controls:
  - Introduce new controls
  - Introduce new syntax for block values
- Improve compatibility with PHP 8.2

= 3.1.5 =

Release Date: 2023-03-02

- Calendar loop types
  - For week number, use Carbon method isoWeek() instead of format('W') which adds unnecessary prefix "0" (zero)
  - Month loop type: Ensure the "year" attribute is taken into consideration; Organize how the attributes "year", "quarter", "from" and "to" are handled
- Format tag: Add support for replace/with string that includes HTML
- Gutenberg integration
  - Improve content filter logic
  - Improve getting current post ID when inside builder
  - Improve workaround for Full-Site Editor bug
    https://github.com/WordPress/gutenberg/issues/46702
- Redirect tag: Disable when inside page builder, AJAX, or REST API
- Switch tag: Improve converting non-default "When" to "Else if"
- Template post types: Remove max-width to let editor take up the full available width
- WP Grid Builder integration: Improve compatibility for PHP version before 7.3

= 3.1.3 =

Release Date: 2023-02-27

- Add WP Grid Builder integration with Tangible Template widget
- Embed module: Use CSS feature for aspect-ratio, and remove padding-top workaround
- Gutenberg integration
  - Improve compatibility with Full-Site Editor, which is still in beta stage
  - Solve issue with shortcode inside pagination loop - Protect template HTML result from Gutenberg applying content filters, such as wptexturize and do_shortcode, after all blocks have been rendered
- Sass module: Solve issue with first style rule selector - Prevent compiler from adding @charset rule or "byte-order mark", which are only valid for CSS stylesheet as a file, when it detects a multibyte character within the Sass source code
- Table module: Make column filter case-insensitive, and add support for multibyte characters
- Template post types
  - Add support for user option "Disable the visual editor when writing" by preventing it from filtering template content
  - Improve generating template slug from title, including converting em dash to regular dash

= 3.1.2 =

Release Date: 2023-02-01

- Improve compatibility with PHP 8.2
- Loop: Improve logic to set current post as loop context for templates loaded inside shortcodes and builder-specific post loops, such as Elementor Loop Grid widget and Beaver Post Loop
- Plugin framework: Fix invalid hook name of ready action specific to module and version
- Post Loop: Add alias "current" (same as "today") for parameter "custom_date_field_value"
- Taxonomy Term Loop: Support multiple IDs for parameter "post"

= 3.1.1 =

Release Date: 2022-12-30

- Loop: Improve getting default loop context for search results archive
- Sass module
  - Upgrade compiler library to ScssPhp 1.11.0
    - Improve compatibility with newer CSS features such as variables, functions, selectors, media queries
    - Improve compatibility with PHP 7 and 8
    - Improve error handling
  - Remove Autoprefixer and its dependency CSS Parser; Internet Explorer no longer supported
  - Improve passing Sass variables - Handle all known value types to be compatible with new compiler
  - Convert any compiler error message to CSS comment
- JavaScript and Sass variable types: Make default value type "raw" (unquoted) instead of "string" (quoted)
- Template post types
  - Support any database table prefix including `wp_`
  - Remove default slug metabox in edit screen to support AJAX save; Related issue in WP core: [Can't change page permalink if slug metabox is removed](https://core.trac.wordpress.org/ticket/18523)

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
