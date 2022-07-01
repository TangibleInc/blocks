import ControlVisibility from '../../template-controls-visibility'
import { maybeInitTabsWorkaround } from './tabs'

const {
  Tangible
} = window

const { prefix, visibility } = Tangible.blockConfig
const $ = jQuery

const {
  conditions,
  tabs: tabsByBlock,
  sections: sectionsByBlock
} = visibility

/**
 * Hide/Show fields according to conditions, for a given widget (if any)
 *
 * @see index.js
 */
export const widgetVisibility = currentWidget => {

  const widgdetName = currentWidget.attributes.widgetType

  if( !widgdetName.startsWith(prefix.slug) ) return

  const blockId = widgdetName.slice(prefix.slug.length)
  const controls = getBlockControls(blockId)

  let tabs = tabsByBlock[blockId] || {}
  let sections = sectionsByBlock[blockId] || {}

  // When an object is empty, PHP's json_encode passes an array
  if (Array.isArray(tabs)) tabs = {}
  if (Array.isArray(sections)) sections = {}

  const visibility = new ControlVisibility(
    conditions[ blockId ]
  )

  const getElementorSettings = () => currentWidget.get('settings').attributes
  const refresh = () => {

    $('.elementor-control-type-section').off('click', refresh)
    $('.elementor-control input').off('keyup change', refresh)
    $('.elementor-control select').off('select2:select', refresh)
    $('.elementor-component-tab').off('click', refresh)

    setVisibility({
      controls,
      visibility,
      elementorSettings: getElementorSettings(),
      tabs,
      sections
    })

    $('.elementor-control-type-section').on('click', refresh)
    $('.elementor-control input').on('keyup change', refresh)
    $('.elementor-control select').on('select2:select', refresh)
    $('.elementor-component-tab').on('click', refresh)

  }

  maybeInitTabsWorkaround(tabs)

  refresh() // Initial visibility
}

/**
 * Get the info needed from ElementorConfig according to block id
 */
const getBlockControls = blockId => {

  const widgetName = prefix.slug + blockId
  const controls = ElementorConfig.widgets[ widgetName ].controls
  const tangibleControls = {}

  for( const controlName in controls ) {

    if( !controlName.startsWith(prefix.control) ) continue

    tangibleControls[ controlName ] = controls[ controlName ]
  }

  return tangibleControls
}

const element = name => ( $('#elementor-controls').find(`[data-setting='${ name }']`) )

/**
 * Evaluate and apply visibility of tabs/sections/fields
 */
const setVisibility = ({
  controls,
  visibility,
  elementorSettings,
  tabs,
  sections
}) => {

  const controlSettings = getControlSettings(controls, elementorSettings)

  const getFieldValue = name => controlSettings[ name ]
    ? controlSettings[ name ].value
    : undefined

  // Tabs

  for (const tabName of Object.keys(tabs)) {

    const tab = tabs[tabName]

    const isVisible = tab.conditions === 'default-tab-workaround'
      ? false
      : visibility.evaluateConditions(
        tab.conditions,
        getFieldValue
      )

    const $tab = $(`.elementor-tab-control-${tabName}`)

    // Currently active tab is always visible
    if ($tab.hasClass('elementor-active')) continue

    $tab[ isVisible ? 'show' : 'hide' ]()
  }

  // Sections

  for (const sectionName of Object.keys(sections)) {

    const section = sections[sectionName]

    const isVisible = visibility.evaluateConditions(
      section.conditions,
      getFieldValue
    )

    const $section = $(`.elementor-control-tangible_section_${sectionName}`)

    // Currently open section is always visible
    if ($section.hasClass('elementor-open')) continue

    $section[ isVisible ? 'show' : 'hide' ]()
  }

  // Controls

  for( const controlName in controlSettings ) {

    const control = controlSettings[ controlName ]

    const isVisible = visibility.evaluateConditions(
      control.conditions,
      getFieldValue
    )

    const $control = $(`.elementor-control-${control.dataName}`)

    $control[ isVisible ? 'show' : 'hide' ]()
  }

}

const getControlSettings = (controls, elementorSettings) => {

  const settings = {}

  for( const control in controls ) {

    const setting = {
      value:    getValue(elementorSettings[ control ], controls[ control ]),
      dataName: control,
      name:     control.slice(prefix.control.length)
    }

    if( controls[ control ].tangible_conditions ) {
      setting.conditions = controls[ control ].tangible_conditions
    }

    settings[ setting.name ] = setting
  }

  return settings
}

const getValue = (value, control) => {

  if ( control.type !== 'number' && control.type !== 'switcher' ) return value

  if ( control.type === 'number' ) return value ? value.toString() : value

  const $switch = element(control.name)

  // We can trust elementor switch value when value comes from a section that is not currently open
  if( $switch.length === 0 ) return value

  // Otherwise we get value ourself because it will be inaccurate
  return $switch.is(':checked') ? control.return_value : control.return_off
}
