const { wp } = window
const {
  element: { useEffect, useState }
} = wp
const { ajax } = window.Tangible

const AjaxSelect = ({ handler, initialValue, fields = [] }) => {

  const [value, setValue] = useState(initialValue ? JSON.parse(initialValue) : [])
  const [search, setSearch] = useState('')

  const [options, setOptions] = useState( [] )
  const [isOpen, setIsOpen] = useState( false )

  const className = `tangible-block-editor-control-ajax-select`
  const isMultiple = fields.multiple && fields.multiple === 'true'
  
  /**
   * Value can be a string even if isMultiple is true, if default value is a string 
   */
  useEffect(() => {
    if( isMultiple && ! Array.isArray(value) ) setValue([ value ])
  })

  useEffect(() => {

    const data = {
      'search': search,
      'field': fields // Pass all field's attributes in case it's needed by ajax action
    }

    ajax(fields.ajax_action_name, data)
      .then(results => setOptions(results))
      .catch(errors => console.error(errors))

  }, [search]) 

  useEffect(() => handler(JSON.stringify(value)), [value])
  useEffect(() => {
    if( isOpen === false ) setSearch('')
  }, [isOpen])

  const saveValue = (saveValue) => {
    
    if( isMultiple ){
      setValue([...value, saveValue])
    } else {
      setValue([saveValue])
    }
  }

  const removeValue = (removeValue) => {

    const currentValue = value.filter(
      val => (val.value !== removeValue.value)
    )

    setValue([...currentValue])
  }

  const selectedValues = Array.isArray(value) ? value.map(item => (item.value)) : []
  const crossIcon = <span className='dashicons dashicons-no-alt'></span>

  return(
    <div className={ isOpen ? `${className} is-open` : className }>
      { isMultiple && Array.isArray(value) ? 
        <ul className={ className + '-choice' } >  
          { value.map((val) => (
            <li 
              id={ val.name } 
            ><button onClick={ () => removeValue(val) }>{ crossIcon }</button> <span>{val.label}</span> </li>
          )) }
        </ul> 
        : ''
      }
      <input 
        type="text"
        className={ className + '-input' }
        onFocus={ () => setIsOpen(true) }
        onBlur={ () => setIsOpen(false) }
        onChange={(e) => {
          setSearch( e.target.value )
        }}
        placeholder={ fields.placeholder ? fields.placeholder : 'Select' }
        value={ !isOpen && !isMultiple && value[0] ? value[0].label : search }
      />  
      { isOpen && (<ul 
          class={className+'-ul'}
        >
        { options ? 
          options.map((option) => {

            if( isMultiple && selectedValues.includes(option.value) ) return ''

            /**
             * Use on onMouseDown instead of onClick otherwise it conflict with onBlur
             * 
             * @see https://stackoverflow.com/a/44142331/10491705
             */
            return( <li 
                id={ option.name } 
                onMouseDown={ () => saveValue(option) } 
              >{ option.label !== '' 
                ? option.label 
                : <i>Untitled</i> }
              </li> )
          }) 
          : ''
        }
      </ul>) }
    </div>
  )
}

export default AjaxSelect
