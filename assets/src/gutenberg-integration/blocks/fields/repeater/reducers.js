export const reducer  = (items, action) => {

  switch (action.type) {
  
    case 'add':
      const item = {}
      for( const control of action.controls ) {
        item[control.name] = control.default ?? ''
      }
      return [ ...items, item ]
    
    case 'remove':
      return [
        ...items.slice(0, action.item),
        ...items.slice(action.item + 1)
      ]
    
    case 'update':
      items[ action.item ][ action.control ] = action.value
      return [ ...items ]
    
    case 'clear': return []

    default: return items

  }

}
