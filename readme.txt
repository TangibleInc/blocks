=== Tangible Blocks ===
Stable tag: trunk
Requires at least: 6.0
Tested up to: 6.2
Requires PHP: 7.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags:



== Description ==



== Installation ==

1. Install & activate in the admin: *Plugins -&gt; Add New -&gt; Upload Plugins*

== Changelog ==

= 3.2.4 =

Release Date: 2023-05-24

- Elementor integration: Improve dynamic module loader by removing AJAX library from dependency list of Template Editor script
- Post loop: Improve handling when called directly without "type" or "post_type" parameter
- Blocks
  - Define a "block" variable to access the current block information form the JavaScript context (builder, post_id, controls, universal_id, wrapper) 
  - Set new blocks to new controls by default
- Controls
  - Number: If defined, "min" attribute will be used as default if no value set
  - Editor: Switch from TinyMCE to ProseMirror - Can still use TinyMCE by adding editor="tinymce"
  - Repeater: Block layout - Add support for bulk actions

= 3.2.3 =

Release Date: 2023-05-18

- Elementor integration: Enqueue dynamic module loader only when inside preview iframe
- Gutenberg integration: Improve compatibility with WP 6.2.1 - Remove the do_shortcode filter workaround that was necessary in previous versions; See https://core.trac.wordpress.org/ticket/58333#comment:59
- List and Map tag
  - Add Item/Key tag attribute "type" for value type: number, boolean, list, map
  - Improve Item/Key tag to treat single List or Map inside as direct value, instead of converting it to string
- Loop tag
  - Add attribute "post_type" as the recommended way to create a post loop with custom post type
    This makes it distinct from attribute "type", which creates an instance of a loop type (such as "post" or "user") and only falls back to post loop if there's no loop type with the same name
  - Fix pagination issue when attribute "tag" is used

= 3.2.1 =

Release Date: 2023-05-08

- Elementor integration: Ensure dynamic modules are activated inside preview iframe
- Format tag: Add attribute "remove_html" to remove HTML and make plain text
- Post loop: Improve sticky posts - Ensure "orderby" is only applied to non-sticky posts

= 3.2.0 =

Release Date: 2023-04-28

- Add JSON-LD tag: Create a map and generate script tag for [JSON Linked Data](https://json-ld.org/)
- Add Raw tag: Prevents parsing its inner content; Useful for passing literal text, such as HTML, to other tags and tag attributes
- Format tag
  - Add attributes "start_slash" and "end_slash" to add slash to URL or any string; Use "start_slash=false" and "end_slash=false" to remove slash; These can be combined in one tag
  - Improve support for replace/with text that includes HTML
- HTML module: Improve "tag-attributes" feature to support dynamic tags
- Layout template type
  - Add theme position "Document Head" for adding Meta tags, JSON-LD schema, or link tag to load CSS files
  - Add theme position "Document Foot" for adding script tag to load JavaScript files
- Loop tag
  - Add attribute "sticky" for improved sticky posts support
    - Without sticky set, treat sticky posts as normal posts; this is the default behavior (backward compatible)
    - With sticky=true, put sticky posts at the top
    - With sticky=false, exclude sticky posts
    - With sticky=only, include sticky posts only
  - Deprecate "ignore_sticky_posts" due to WP_Query applying it only on home page
- Query variable type: Support passing loop attributes via AJAX, such as for pagination
- Url tag
  - Add attribute "query=true" to include all query parameters in current URL
  - Add attributes "include" and "exclude" to selectively pass query parameters by name; Accepts comma-separated list for multiple names
- New controls
  - SASS variable: Add support for lists inside maps
  - Repeater: Fix issue with repeater fields not being formated properly
  - Repeater: Add confirmation prompt before deleting an item
  - Gallery: Fix issue with image preview
  - Gallery and File: Fix issue that happens after closing the media library
  - Color picker: Add text input to directly set a color value
- Blocks
  - Gutenberg: Avoid unnecessary re-render while editing blocks
- Improve PHP 7.4 compatibility

= 3.1.9 =

Release Date: 2023-04-06

- Format: Improve handling of spaces for kebab and snake case
- If tag
  - Deprecate "is_not" in favor of "not", which supports all condition types and operators including "is"
  - Convert "is_not" to "not" and "is" for backward compatibility
- Improve PHP 8.2 compatibility
- Template post types: Fix drag-and-drop sort in post archive

= 3.1.8 =

Release Date: 2023-03-20

- Gutenberg integration
  - Improve content filter logic to protect template HTML: Handle edge case when a template shortcode is rendered inside an HTML attribute, and its content is a URL
- SCSS block variables:
  - Add SCSS maps for controls with multiple values (dimensions and gradients)
  - Add SCSS list for controls with multiple items (repeater, files, gallery and field_group)
- New controls:
  - Gallery: Add clear button
  - Date: Improve value change from text input

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
