const getControl = (
  handler, 
  value, 
  props
) => (
  tangibleFields.render({
    onChange: value => handler(
      Array.isArray(value) || typeof value === 'object'
        ? JSON.stringify(value) 
        : String(value)
    ),
    ...props,
    value: value // Important: Value must not be overwrited by props 
  })
)

export { getControl }
