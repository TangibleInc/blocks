import ControlVisibility from '../../../template-controls-visibility'
import { onFormChange, getFormFields } from '../form'

const $ = jQuery

const init = module => {
  
  FLBuilder.addHook('settings-form-init', () => {
    
    const init  = (e, data) => initVisibility(module, data)
    const clear = () => {
      FLBuilder.removeHook('didCompleteAJAX', init)
      FLBuilder.removeHook('didHideLightbox', clear)
    } 

    FLBuilder.addHook('didCompleteAJAX', init)
    FLBuilder.addHook('didHideLightbox', clear)
  })

}

const initVisibility = (module, data) => {

  if( ! data.fl_builder_data ) return;
  
  const formName = data.fl_builder_data.form ?? '' 
  const settings = data.settings ?? []

  const prefix = `_tangible_repeater_${module.blockId}_` // Should pass _tangible_repeater_ from backend
  const isRepeater = formName.startsWith(prefix)
  
  if( ! isRepeater ) return;

  const repeaterName = formName.replace(prefix, '')

  const repeaters = module.conditions.repeater ?? {}
  const conditons = repeaters[ repeaterName ] ?? false
  
  if( ! conditons ) return;

  const visibility = new ControlVisibility(conditons)
  const $repeaterForm = $(`form[data-form-id=${formName}]`)
  
  if( ! $repeaterForm ) return;

  onFormChange(
    $repeaterForm,
    () => setVisibility(
      $repeaterForm, 
      module, 
      visibility, 
      repeaterName
    )
  )
}

const setVisibility = ($form, module, visibility, repeaterName) => {

  const fields = getFormFields($form, module.repeaters[repeaterName] ?? {})
  const getValue = name => fields[name] ? fields[name].value : undefined

  for ( const fieldName in fields ) {

    const field = fields[ fieldName ]

    if ( ! field.conditions
      || ! field.conditions.length
    ) continue // No conditions: The field is always visible
    
    const isFieldVisible = visibility.evaluateConditions(
      field.conditions,
      getValue
    )
    
    const $field = $(field.element).closest('.fl-field')

    $field[ isFieldVisible ? 'show' : 'hide' ]()
  }

}

export { init }
