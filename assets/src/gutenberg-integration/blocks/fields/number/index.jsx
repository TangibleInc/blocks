const { wp } = window
const {
  element: { useEffect, useState }
} = wp

const Number = props => {

  const [value, setValue] = useState(!props.value
    ? 0
    : props.value
  )

  useEffect(() => {
    props.onChange( value )
  }, [value])

  return (
    <input type="number" value={ value }
      // @see https://stackoverflow.com/a/41031849
      { ...(props.min && { min: props.min }) }
      { ...(props.max && { max: props.max }) }
      onChange={
        (e) => {
          setValue( e.target.value !== '' ? parseInt(e.target.value) : 0 )
        }
      }
    />
  )
}

export default Number
