const { wp } = window
const { useState, useEffect } = wp.element

/**
 * Temporary test field (keep as a fallback?) until we start moving real custom componenets
 * in this system
 */

const Text = props => {

  const [value, setValue] = useState(props.initialValue)

  useEffect(() => props.handler(value), [value])

  return(
    <input type="text" value={ value } onChange={ (e) => setValue(e.target.value) }/>
  )
}

export default Text
