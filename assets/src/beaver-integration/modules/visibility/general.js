import ControlVisibility from '../../../template-controls-visibility'

import { init as initRepeater } from './repeater'
import { onFormChange, getFormFields } from '../form'

const $ = jQuery

const init = module => {

  const visibility = new ControlVisibility(
    module.conditions.general,
  )

  onFormChange(
    module.form,
    () => setVisibility(module, visibility)
  )

  initRepeater(module)
}

const setVisibility = (module, visibility) => {

  // Evaluate and apply visibility of tabs/sections/fields
  
  const tabSettings     = module.getTabSettings()
  const sectionSettings = module.getSectionSettings()
  const fieldSettings   = getFormFields(module.form, module.fields)

  const getFieldValue = name => fieldSettings[name]
    ? fieldSettings[name].value
    : undefined

  // Tabs

  for (const tabName in tabSettings) {

    const tabSetting = tabSettings[ tabName ]

    if ( ! tabSetting.conditions
      || ! tabSetting.conditions.length
    ) continue // Always visible

    const isTabVisible = visibility.evaluateConditions(
      tabSetting.conditions,
      getFieldValue
    )

    const $tab = $(tabSetting.element)

    // NOTE: Always show currently active tab
    if ( ! $tab.hasClass('fl-active') ) {
      $tab[ isTabVisible ? 'show' : 'hide' ]()
    }
  }

  // Sections

  for (const sectionName in sectionSettings) {

    const sectionSetting = sectionSettings[ sectionName ]

    if ( ! sectionSetting.conditions
      || ! sectionSetting.conditions.length
    ) continue // Always visible

    const isSectionVisible = visibility.evaluateConditions(
      sectionSetting.conditions,
      getFieldValue
    )

    const $section = $(sectionSetting.element)

    $section[ isSectionVisible ? 'show' : 'hide' ]()
  }

  // Fields
  
  for ( const fieldName in fieldSettings ) {

    const fieldSetting = fieldSettings[ fieldName ]

    if ( ! fieldSetting.conditions
      || ! fieldSetting.conditions.length
    ) continue // No conditions: The field is always visible
    
    const isFieldVisible = visibility.evaluateConditions(
      fieldSetting.conditions,
      getFieldValue
    )
    
    const $field = $(fieldSetting.element).closest('.fl-field')

    $field[ isFieldVisible ? 'show' : 'hide' ]()
  }
}

export { init }
