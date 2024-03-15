=== Tangible Blocks ===
Stable tag: trunk
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags:



== Description ==



== Installation ==

1. Install & activate in the admin: *Plugins -&gt; Add New -&gt; Upload Plugins*

== Changelog ==

= 4.1.2 =

Release Date: 2024-03-15

- ACF integration
  - Date field types: Ensure unformatted value is passed to date conditions
  - Group, Flexible Content, Repeater: Correctly set up subfield loop after change to List loop type to support extended parameters such as offset/count/sort/filter
- Post loop: Handle case when extended query parameter for post/user/category/tag slug is not array
- Sass module: Revert to SCSS-PHP 1.11.1 to keep compatibility with PHP 7.4
- Taxonomy term loop: Correctly pass post object IDs to query

= 4.1.0 =

Release Date: 2024-03-12

- ACF integration
  - **Breaking change**: Date field types now get their default formatting from the field setting for Return Format. Previously the defaults were from site setting (Date field), "Y-m-d H:i:s" (Date/Time), and "H:i:s" (Time). Now they use the selected format in each field's settings, or ACF's default return format: "d/m/Y" (Date), "d/m/Y g:i a" (Date/Time), and "g:i a" (Time).
  - Improve handling of "format" and "locale" attributes
- Editor
  - Formatter
    - Add keyboard shortcuts to support formatting by entire document or selected lines
    - Start a fork of Prettier HTML formatter to customize based on template language definition
  - Linter: Improve HTML linter rule for unique ID so it applies only to static tags
  - Update CodeMirror modules and Prettier
- HTML module
  - Add comprehensive HTML test suite with test files from Parse5, Prettier, and Unified
  - Refactor to improve performance: ~3% faster
- Loop types
  - Consolidate everywhere that accepts a list to support comma-separated list and JSON array
  - Improve sort by field using "field_compare" 
  - List, Map, Map Keys: Support query parameters for base loop, such as offset, count, sort, filter, pagination
    - List: Use field name "value", like "sort_field=value"
    - Map keys: Use field name "key" or "value". Keep default order of keys as defined, unless "sort_field=key" is applied - previously was sorted alphabetically.
- Gutenberg integration: Template block: Remember previously selected template when switching tabs
- Sass module: Upgrade Sass compiler (scssphp) to 1.12.1, and CodeMirror Sass language support
- Taxonomy term loop: Ensure "post" attribute accepts list variable
- Template post types: Ensure templates always have a universal ID assigned, during post save and before exporting. This improves how duplicate templates are handled during import.
- Template tag/shortcode: Ensure no post matches if attribute "name" is an empty string - See [WP_Query matches *any* post when query parameter "name" is an empty string](https://core.trac.wordpress.org/ticket/60468)
- Blocks
  - Sass variables: Improve variable formatting, and make it possible to use % as a value
- Controls
  - Repeater and field group: Exclude control types with no style context for sass map
  - BeaverBuilder: Resolve popover issues when using the responsive iframe UI
  - Repeater, ComboBox, Editor and Text: Various style improvements

= 4.0.2 =

Release Date: 2024-01-18

- ACF integration: For relational fields, apply loop query parameters such as sort, order, paged, and exclude
- Editor: Change key map to expand Emmet abbreviation to Shift+TAB, to prevent conflict with TAB to select autocomplete suggestion
- Framework: Improve plugin settings page styles
- Paginator: Improve how AJAX script is loaded
- Post loop: Support use of `exclude` and `include` together, which is not natively supported by WP_Query
- Fix issues related to code reorganization: Mobile Detect and WP Fusion; Add integration tests to ensure no regression

= 4.0.0 =

Release Date: 2024-01-03

- [Documentation](https://docs.loopsandlogic.com/reference/template-system): Reference pages for developers and contributors, with technical details of how the codebase is organized.
- [Editor](https://docs.loopsandlogic.com/reference/template-system/editor/): New code editor based on CodeMirror 6 is enabled by default for template post types, Gutenberg, and ACF Template field. The old editor is still used for Elementor and Beaver Builder until integration is complete.
- Framework and Modules: Features have been organized into modules which can be independently built, documented, tested, and optionally published. This replaces the previous Plugin Framework and Interface module.
- [GitHub repository](https://github.com/tangibleinc/template-system): New home of the Template System source code and project, with better developer experience and social collaboration. Welcome to start new issues, pull requests, and discussions.
- Testing: Improve coverage of unit tests, and prepare foundation for end-to-end tests with headless browser and WordPress environment in Docker. This is an on-going effort to exercise and verify all features of the plugin.

Other improvements:

- ACF integration: Add Field tag attribute "acf_textarea" to apply formatting based on field settings
- Archive screen: Add bulk action to move selected posts to trash
- Assets edit screen: Improve documentation
- Atomic CSS: Generate CSS utility classes on demand.
  Similar to Tailwind, this feature uses a style engine called [UnoCSS](https://unocss.dev/) to generate CSS rules from utility classes found in an HTML template, every time it is saved. On the frontend, the generated styles are minified together, removing any redundant rules. Enable in plugin settings.
- Edit screen: Add Preview pane with auto-refresh
- Editor: Hyperlink extension - Add clickable link icon next to a valid URL; Improve color picker
- Import/Export
  - Add export rule to include/exclude template categories
  - Update PNG Compressor with better support for Firefox
  - Use compressed format (PNG) by default
- Show admin menu, edit screens, and template editor (Gutenberg, Elementor, Beaver) only to admins with `unfiltered_html` capability. On multisite installs, by default only network admins have this capability, not subsite admins.
- Update included libraries
  - HJSON, Select2, Chart.js, Mermaid, ..
  - Prism: Update library to 1.29.0 - Replace Clipboard.js with browser-native `navigator.clipboard`
- Blocks
  - Sass variables: Rewrite and improve sass variable logic
  - Add a new sass map called "blocks", that contains blocks related data (wrapper, post_id, universal_id and builder)
- Controls
  - Repeaters and Field groups: Allows multiple level of nesting for visibility conditions and dependent values
  - Field groups: Fix unwanted re-render
  - Accordions: Fix content visibility being changed when using header switch
  - Text: Add support for read_only parameter
  - Button groups: Add support for read_only parameter
  - Select: Add support for read_only parameter
  - File: Improve styling for delete button
  - Beaver builder: Various style improvements in beaver-builder editor

= 3.3.1 =

Release Date: 2023-11-09

- Admin menu: For multisite installs, register menus per site, not network admin, because post types are site-specific and not shared across sites
- Remove use of deprecated function setImmediate when loading Select2; Fixes issue on import/export page

= 3.3.0 =

Release Date: 2023-11-02

- ACF integration
  - Flexible Content and Repeater field: Improve compatibility with PHP 8
  - Template field
    - Add support for editor inside repeater field
    - **Breaking change**: Make feature optional and disabled by default to prevent ACF from loading field assets (JS & CSS) on every admin screen; Enable in the new settings page 
- Admin settings page: Tangible -> Settings
  - Features under development: New editor based on CodeMirror 6
  - Optional features: ACF Template field
- Field tag: Add format shortcuts: join, replace, replace_pattern
- Format tag: Support capture groups for replace_pattern; When invalid regular expression is passed, emit a warning instead of throwing an error
- Gutenberg integration
  - Improve compatibility with standalone Gutenberg plugin
  - Remove dependency on lodash
- If tag: Pass attribute "matches_pattern" without rendering, to support regular expression with curly braces; Use syntax `<If check=".." matches_pattern="..">`; Support dynamic tags in attribute "matches_pattern" without delimiters; Display warning instead of throwing error for invalid pattern syntax
- Import/export
  - Add new template package format using browser-native gzip compressor and encoded as PNG image file; This could be useful for uploading and sharing on forums where JSON is not suitable
  - Ensure JSON and SVG file types are allowed during import
- Inteface module: Build Select2 from full version instead of minimal to improve compatibility with other plugins like ACF and WooCommerce which bundle their own Select2 library 
- Menu loop: Return empty list if menu not found
- Post loop: Add field "modified_author" and "modified_author_*"
- Taxonomy term loop: Support taxonomy fields created with PODS
- Controls
  - ComboBox: Async - Add map_results attribute
  - Switch to new Popover component (ColorPicker, Gradient, ComboBox, Select, DatePicker)
  - ListBox: Fix missing key errors

= 3.2.9 =

Release Date: 2023-07-17

- Format tag
  - Format list index: Fix warning from array_shift()
  - Support capture groups for replace_pattern, for example: replace_pattern="/(\d{3})/" with="$1"
  - When invalid regular expression is passed, emit a warning instead of throwing an error

= 3.2.8 =

Release Date: 2023-07-12

- Date module: Upgrade Carbon date library to version 2.68.1 with better PHP 8.x compatibility
- Format tag
  - Add list and string format methods
    - index, offset, length, words - Cut a piece of string by position
    - split, join - Split string to list, and back
    - trim, trim_left, trim_right - Remove whitespace or given characters from start/end 
    - prefix, suffix - Add string before or after
    - reverse - Reverse a string or list
  - Regular expressions - replace_pattern, match_pattern
  - Multibyte string: uppercase, lowercase, capital, capital_words
  - Format list
    - Format every item in a list
    - Nested list formats
- If tag
  - Add comparison "matches_pattern" to match value to regular expression
  - Improve comparison "includes" to support a List loop instance, for example: `<If acf_checkbox=field_name includes value=some_value>`
- Blocks variables
  - Disabled template render on SCSS and JavaScript variables
  - Added more data in JS block variable regarding control values
- BeaverBuilder: Fixed CSS conflicts with legacy controls in the editor
- Gutenberg: Fixed wrapper ID being the same when cloning an existing block
- Controls
  - Improved CSS for default and wp contexts
  - Fields:
    - Number: Added support for string values
    - Repeater: Added bulk action for table layout
    - ComboBox: Added support for placeholder in single value
  - Implemented dynamic value feature
    - Added post category: post_id, post_meta
    - Added user category: user_id, user_meta
    - Supported field types: color_picker, date_picker, number, text

= 3.2.7 =

Release Date: 2023-06-01

- Link tags: Ensure any null is converted to empty string before passing to str_replace - Compatibility with PHP 8.x

= 3.2.6 =

Release Date: 2023-05-31

- Beaver integration: Fix issue with BeaverBuilder 2.7 and above preventing control to be initialized in the editor
- Controls: Add new alignment_matrix control

= 3.2.5 =

Release Date: 2023-05-27

- Elementor integration: Improve dynamic module loader by removing AJAX library from dependency list of Template Editor script
- Post loop
  - Improve handling when called directly without "type" or "post_type" parameter
  - For loop types that inherit from post loop, such as attachment, ensure backward compatibility with deprecated query parameter "type"
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
