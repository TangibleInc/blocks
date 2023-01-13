const getControl = (
  control, 
  handler, 
  value, 
  props
) => (
  tangibleFields({
    onChange: value => handler(
      Array.isArray(value) || typeof value === 'object'
        ? JSON.stringify(value) 
        : value
    ),
    value: value,
    ...props 
  })
)

export { getControl }
