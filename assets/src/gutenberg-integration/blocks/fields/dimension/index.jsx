const { wp } = window
const {
  components: { BaseControl, __experimentalBoxControl, SelectControl },
  element: { useEffect, useState }
} = wp

const Dimension = props => {

  const [values, setValues] = useState( props.value )
  const [unit, setUnit] = useState( props.value.unit ? props.value.unit : props.defaultUnit )

  const className = props.multipleValues !== 'false' 
    ? props.className 
    : `${props.className} ${props.className}-single-value`
  
  const unitsList = []
  for (let i in props.units) {
    unitsList.push({ value: props.units[i], label: props.units[i] })
  }

  useEffect(() => {

    // Keep only number, we don't care about the unit
    const value = {
      'top':    values.top ? values.top.replace(/[^0-9]/g, '') : '',
      'right':  values.right ? values.right.replace(/[^0-9]/g, '') : '',
      'bottom': values.bottom ? values.bottom.replace(/[^0-9]/g, '') : '',
      'left':   values.left ? values.left.replace(/[^0-9]/g, '') : '',
      'unit':   unit
    }

    props.onChange(value)
  }, [values, unit])

  return (
    <BaseControl className={ className  }>
      <__experimentalBoxControl
        label = { props.label }
        values={ props.multipleValues !== 'false' ? values : { top : values.top } }
        units = { [{ value: 'px', label: 'px' }] } // We will not use unit from this component because it dosen't behave well when fields unlinked
        onChange={ values => setValues(values) }
        sides={ props.multipleValues !== 'false' ? [] : [ 'top' ] }
      />
      <SelectControl
        onChange={ unit => setUnit(unit)  }
        value={ unit }
        label={ 'Unit' }
        options={ unitsList }
      />
    </BaseControl>
  )
}

export default Dimension
