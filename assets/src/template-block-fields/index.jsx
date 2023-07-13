const getControl = (
  handler, 
  value, 
  props,
  builder
) => (
  tangibleFields.render({
    context: builder === 'gutenberg' ? 'wp' : builder,
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
