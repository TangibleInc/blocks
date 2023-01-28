import { isHex, hexToRGBA } from './utils'

const { wp } = window
const {
  components: { BaseControl, ColorPicker },
  element: { useEffect }
} = wp

const Color = props => {

  const color = props.value === '' && props.defaultValue !== false
    ? (isHex(props.defaultValue) ? hexToRGBA(props.defaultValue) : props.defaultValue)
    : props.value

  useEffect(() => props.onChange(color), [])

  return( 
    <BaseControl className={ props.className  }>
      <div>
        <p>{ props.label }</p>      
        <ColorPicker
          style={ { display: 'block', marginTop: '10px' } }
          color={  color }
          onChangeComplete={ value => {

            const valueString = props.alpha === false 
              ? `rgb(${value.rgb.r}, ${value.rgb.g}, ${value.rgb.b})`
              : `rgba(${value.rgb.r}, ${value.rgb.g}, ${value.rgb.b}, ${value.rgb.a})`        
            
            props.onChange(valueString)
          }}
          disableAlpha={ props.alpha === false || props.alpha === 'false' }
        />
      </div>
    </BaseControl>
  )
} 



export default Color
