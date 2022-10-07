/**
 * Helpers to get fields data of a module form 
 */

const $ = jQuery

const onFormChange = ($form, callback) => {

  callback()

  $form.find('select').on('change', callback)
  $form.find('input').on('change', callback)
  $form.find('input[type=text], input[type=number]').on('input', callback)
  
  // WYSIWYG editors
  const $editors = $form.find('.fl-editor-field')
  
  $editors.each( i => {
    const editor = getEditor($editors[i]) 
    if( editor ) editor.on('change', callback)
  })
} 

const getFormFields = ($form, conditions) => {
  
  const $fields = $form.find('select, input, .fl-editor-field')
  const settings = {}

  $fields.each( i => {

    const setting = getFormField( $fields[i] )

    if( ! setting ) return;

    setting.conditions = conditions[ setting.name ] || false
    setting.element    = $fields[i]

    settings[ setting.name ] = setting
  })
  
  return settings
}

/**
 * We get settings differently according to DOM element
 */
const getFormField = field => {

  switch(field.tagName) {

    case 'SELECT':
      if(field.multiple) {
        return field.name !== ''
          ? { name: field.name, value: $(field).val().join(',') }
          : false
      }

      // falls through - https://eslint.org/docs/rules/no-fallthrough

    case 'INPUT':
      return field.name !== ''
        ? { name: field.name, value: $(field).val() }
        : false

    // Tiny MCE content (should we support condition for this?)
    case 'DIV':

      const name = $(field).attr('data-name')
      const editor = getEditor(field)

      return { name: name, value: editor.getContent() }
    }

  return false
}

/**
 * For wysiwyg/editor, beaver builder use tinyMCE so we need to use it to get value/events
 */
const getEditor = element => {

  const id = $(element).find('.wp-editor-tabs button').attr('data-wp-editor-id')

  return window.tinyMCE ? window.tinyMCE.get(id) : false
}

export {
  onFormChange,
  getFormFields,
  getEditor
}
