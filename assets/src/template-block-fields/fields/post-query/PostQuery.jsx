import Order from './Order';
import Type from './Type';
import Taxonomy from '../taxonomy'

const { Tangible, wp } = window
const { useState, useEffect } = wp.element

const {
  allPostTypes = {}
} = Tangible.postQueryControlData || {}


const PostQuery = ({ initialData, save, fields = [] }) => {

  const fieldList = fields.replace(/\s+/g, '').split(',')

  const parseData = json => {
    try { return JSON.parse(json) } 
    catch { return {} }
  }

  const [args, setArgs] = useState( parseData(initialData) )

  useEffect(() => setArgs( parseData(initialData) ), [initialData])

  // Update result
  useEffect( () => {
    save( args )
  }, [ args ])

  // Getter function used by child components of Post Query
  const getData = key => {
    return args[key] && args[key] !== ''
      ? args[key]
      : null
  }

  // Setter function used by child components of Post Query
  const updateArgs = ( field, value ) => setArgs( oldArgs => {

    if( field !== 'orderby' ) return { ...oldArgs, [ field ]: value }
    
    if( value !== 'meta_value' && oldArgs.orderby_field ) {
      delete oldArgs.orderby_field
    }

    if( value !== 'meta_value_num' && oldArgs.orderby_field_number ) {
      delete oldArgs.orderby_field_number
    }

    return { ...oldArgs, [ field ]: value }
  })

  // Taxonomy child component
  const taxonomy = <Taxonomy
    initialData={ args.hasOwnProperty('taxonomies') ? JSON.stringify( args.taxonomies ) : '{}' }
    save={ newParams => updateArgs( 'taxonomies', newParams ) }
  />

  return (
    <>
      { ( fieldList.includes('order') || fieldList.includes('orderby') ) &&
        <div className='tangible-logic-rule-group-box'>
          <h4 style={{ fontWeight: '600' }}>Order</h4>
          <Order
            getData={ getData }
            setData={ updateArgs }
            includeOrder={ fieldList.includes('order') }
            includeOrderBy={ fieldList.includes('orderby') }
          />
        </div>
      }

      { fieldList.includes('type') &&
        <div className='tangible-logic-rule-group-box'>
          <h4 style={{ fontWeight: '600' }}>Type</h4>
          <Type
            initialLoopType={ getData('loopType') !== null ? getData('loopType') : 'post' }
            initialType={ getData('type') }
            setData={ updateArgs }
            fields={ Object.keys( allPostTypes ).map( val => {
              return { label: allPostTypes[val], value: val }
            }) }
          />
        </div>
      }

      { fieldList.includes('taxonomy') &&
        <div className='tangible-logic-rule-group-box'>
          <h4 style={{ fontWeight: '600' }}>Filter by Taxonomy</h4>
          { taxonomy }
        </div>
      }
    </>
  )
}

export default PostQuery
