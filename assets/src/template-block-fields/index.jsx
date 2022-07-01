import Text from './fields/Text'
import PostQuery from './fields/post-query/PostQuery'
import Taxonomy from './fields/taxonomy'
import Select from '../common/Select'
import AjaxSelect from './fields/ajax-select'

export const getControl = (control, handler, value, field) => {

  switch(control.type) {

  case 'post_query':
    return <PostQuery
      save={ value => handler(JSON.stringify(value)) }
      initialData={ value }
      fields={ field.include_fields ? field.include_fields : 'type, order, orderby' }
    />

    /**
     * Not ready yet: Needs to determine what kind of render we want when used as a separate control
     */
  case 'taxonomy':
    return <Taxonomy save={ value => handler( JSON.stringify( value ) ) } initialData={ value } { ...field } />

  case 'select2':

    const isMultiple = field.multiple && field.multiple === 'true'
    const selectValue = typeof value === 'string' && isMultiple
      ? ( value !== '' ? value.split(',') : [] )
      : ( value !== '' ? value : null )

    return <Select
      labelForEmptyValue='Choose...'
      options={ field.options
        ? Object.keys(field.options).map(key => ({ value: key, label: field.options[key] }))
        : [] }
      onChange={ value => value !== null
        ? handler( Array.isArray(value) ? value.join(',') : value )
        : handler('') }
      multiSelect={ isMultiple }
      value={ selectValue }
      style={{ width: '100%' }}
    />

  case 'ajax_select':
    return <AjaxSelect handler={ handler } initialValue={ value } fields={ field } />

    // Fallback to a text field
  default:
    return <Text handler={ handler } initialValue={ value } { ...field } />

  }
}
