export const reducer  = (items, action) => {

  switch (action.type) {
  
    case 'add':
      return [ 
        ...items, 
        { ...action.structure } 
      ]
    case 'remove':
      return [
        ...items.slice(0, action.item),
        ...items.slice(action.item + 1)
      ]  
    case 'update':
      items[ action.item ][ action.control ] = action.value
      return [ 
        ...items 
      ]
    case 'clear': return []

    default: return items
  }

}
