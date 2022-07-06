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

- [Text](#text)
- [Select](#select)
- [Date](#date)
- [Color](#color)
- [Text Align](#text-align)
- [WYSIWYG Editor](#wysiwyg-editor)
- [Image](#image)

#### Text

```json
{
  "type": "text",
  "name": "text-control-name",
  "label": "Text control",
  "default": "default text" // Optional
}
```

### Select

```json
{
  "type": "select",
  "name": "select-control-name",
  "label": "Select control",
  "options": {
    "one": "Option one",
    "two": "Option two",
    "three": "Option three"
  },
  "multiple": "true", // Optional: Allows selection of multiple items
  "default": "one" // Optional
}
```

### Date

```json
{
  "type": "date",
  "name": "date-control-name",
  "label": "Date",
  "format": "d/m/Y g:i a" // Optional
}
```

### Color

```json
{
  "type": "color",
  "name": "color-control-name",
  "label": "Color control",
  "default": "#FFFFFF", // Optional
  "alpha": false // Optional
}
```

### Text Align

```json
{
  "type": "align",
  "name": "text-align-control-name",
  "label": "Text align control",
  "default": "right" // Optional
}
```

### WYSIWYG Editor

```json
{
  "type": "wysiwyg",
  "name": "wysiwyg-control-name",
  "label": "Wysiwyg editor control",
  "default": "Some <strong>text</strong>",
}
```

### Image

```json
{
  "type": "image",
  "name": "image-control-name",
  "label": "Image",
  "default": "https://ps.w.org/tangible-loops-and-logic/assets/banner-1544x500.jpg"
}
```

### Switch

```json
{
  "type": "switch",
  "name": "switch-control-name",
  "label": "Switch",
  "label_on": "On Label", // Optional
  "label_off": "Off Label", // Optional
  "value_on": "on", // Optional
  "value_off": "off" // Optional
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
