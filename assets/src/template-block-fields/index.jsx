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
    value: value,
    ...props 
  })
)

export { getControl }
