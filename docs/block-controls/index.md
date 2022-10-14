# Block Controls

## JSON structure

We can use JSON to generate sections and fields in a block.

### Tabs

Tabs are optional, if none are added sections will be added in the default panel ("General" for beaver-builder, "Content" for elementor).

In Gutenberg, tabs will be showned only if there is more than one tab.

```json
{
  "tabs": [
    {
    "title": "default", // If the title is default, tabs name will be different according to the builder
      "sections": [
        {
          "title": "Section 1",
          "controls": [
            // Controls
          ]
        },{
          "title": "Section 2",
          "controls": [
            // Controls
          ]
        }
      ]
    },
    {
      "title": "Custom tab",
      "sections": [
        {
          "title": "Section 3",
          "controls": [
            // Controls
          ]
        }
      ]
    }
  ]
}

```

### Sections

```json
[
  {
    "title": "Section 1",
    "controls": [
      // Controls
    ]
  },
  {
    "title": "Section 2",
    "controls": [
      // Controls
    ]
  }
]
```

### Controls

Here is the different type of controls implemented:

- [Ajax select](#ajax-select)
- [Align](#align)
- [Color](#color)
- [Date](#date)
- [Dimension](#dimension)
- [Editor](#editor)
- [Gallery](#gallery)
- [Gradient](#gradient)
- [Image](#image)
- [Number](#number)
- [Post query](#post-query)
- [Post select](#post-select)
- [Post types](#post-types)
- [Select](#select)
- [Select2](#select2)
- [Switch](#switch)
- [Text](#text)
- [User select](#user-select)

<a name=ajax-select></a>

#### Ajax select

```json
{
  "type": "ajax_select",
  "name": "ajax_select_control_name",
  "label": "Ajax select",
  "ajax_action_name": "ajax_action_name" // See https://docs.tangible.one/modules/plugin-framework/ajax/ 
}
```

<a name=align></a>

#### Align

```json
{
  "type": "align",
  "name": "align_control_name",
  "label": "Align control",
  "default": "right" // Optional - Accepted value: right, center, left 
}
```

<a name=color></a>

#### Color

```json
{
  "type": "color",
  "name": "color_control_name",
  "label": "Color control",
  "default": "#FFFFFF", // Optional
  "alpha": false // Optional - Default true
}
```

<a name=date></a>

#### Date

```json
{
  "type": "date",
  "name": "date_control_name",
  "label": "Date",
  "format": "d/m/Y g:i a" // Optional
}
```

<a name=dimension></a>

#### Dimension

```json
{
  "type": "dimension",
  "name": "dimension_control_name",
  "label": "Dimensions",
  "units": "px,vh,vw", // Optional - Default px
  "default": "0,0,0,0", // Optional - Default 0,0,0,0
  "default_unit": "vw", // Optional - Default px
  "multiple_values": true // Optional - Default true
}
```

<a name=editor></a>

#### Editor

```json
{
  "type": "editor",
  "name": "editor_control_name",
  "label": "Editor",
  "default": "Some <strong>text</strong>" // Optional
}
```

<a name=gallery></a>

#### Gallery

```json
{
  "type": "gallery",
  "name": "gallery_control_name",
  "label": "Gallery",
  "default": "1,2", // Optional - Attachment IDs
  "size": "full" // Optional - Default full
}
```

<a name=gradient></a>

#### Gradient

```json
{
  "type": "gradient",
  "name": "gradient_control_name",
  "label": "Gradient"
}
```

<a name=image></a>

#### Image

```json
{
  "type": "image",
  "name": "image_control_name",
  "label": "Image",
  "default": "1" // Optional - Attachment ID or URL
}
```

<a name=number></a>

#### Number

```json
{
  "type": "number",
  "name": "number_control_name",
  "label": "Number",
  "default": "10", // Optional
  "min": "0", // Optional
  "max": "100" // Optional
}
```

<a name=post-query></a>

#### Post query

```json
{
  "type": "post_query",
  "name": "post_query_control_name",
  "label": "Post query",
  "include_fields": "taxonomy, type, order, orderby", // Optional - Default type, order, orderby 
}
```

<a name=post-select></a>

#### Post select

```json
{
  "type": "post_select",
  "name": "post_select_control_name",
  "label": "Post select",
  "default": "1", // Optional - Post ID 
  "multiple": true, // Optional - Default false
  "result_length": 10, // Optional - Default 10
  "post_type": "post,page", // Optional - Default post
  "result_order": "ASC" // Optional - default ASC
}
```

<a name=post-types></a>

#### Post types

```json
{
  "type": "post_types",
  "name": "post_types_control_name",
  "label": "Post types",
  "multiple": true, // Optional - Default false
  "default": "post" // Optional
}
```

<a name=select></a>

#### Select

```json
{
  "type": "select",
  "name": "select_control_name",
  "label": "Select",
  "options": {
    "one": "Option one",
    "two": "Option two",
    "three": "Option three"
  },
  "multiple": true, // Optional - Default false
  "default": "one" // Optional
}
```

<a name=select2></a>

#### Select2

```json
{
  "type": "select2",
  "name": "select2_control_name",
  "label": "Select2",
  "options": {
    "one": "Option one",
    "two": "Option two",
    "three": "Option three"
  },
  "multiple": true, // Optional - Default false
  "default": "one" // Optional
}
```

<a name=switch></a>

#### Switch

```json
{
  "type": "switch",
  "name": "switch_control_name",
  "label": "Switch",
  "default": "on", // Optional - Default on
  "label_on": "On", // Optional - Default On 
  "label_off": "Off", // Optional - Default Off
  "value_on": "on", // Optional - Default on 
  "value_off": "off" // Optional - Default off
}
```

<a name=text></a>

#### Text

```json
{
  "type": "text",
  "name": "text_control_name",
  "label": "Text",
  "default": "default text" // Optional
}
```

<a name=user-select></a>

#### User select

```json
{
  "type": "user_select",
  "name": "user_select_control_name",
  "label": "User select",
  "default": "1", // Optional - User ID
  "multiple": true, // Optional - Default false
  "result_length": 10, // Optional - Default 1
  "role": "editor" // Optional - Default all
}
```


## Control values

We can use a control value in templates, scripts and styles by adding {{ control-name }}.

## Full example

### Template tab
```html
<div id="template">
  <img src="{{ image-name }}" />
  <p id="text">{{ before-click-text }}</p>
</div>
```

### Style tab
```css
#template {
  background: {{ background-color }};
}
```

### Script tab
```javascript
var container = document.getElementById('template')

container.addEventListener('click', function() {
  document.getElementById('text').textContent = "{{ after-click-text }}"
})
```

### Controls tab
```json
[
  {
    "title": "Section style",
    "controls": [
      {
        "type": "image",
        "name": "image-name",
        "label": "Image",
        "default": "https://ps.w.org/tangible-loops-and-logic/assets/banner-1544x500.jpg"
      },
      {
        "type": "color",
        "name": "background-color",
        "label": "Background color",
        "default": "#FF0000"
      }
    ]
  },
  {
    "title": "Section text",
    "controls": [
      {
        "type": "text",
        "name": "before-click-text",
        "label": "Before click text",
        "default": "Visible before the click"
      },
      {
        "type": "text",
        "name": "after-click-text",
        "label": "After click text",
        "default": "Visible after the click"
      }
    ]
  }
]
```
