import Select from '../../../common/Select';

const { Tangible, wp } = window
const { useState, useEffect } = wp.element

// Data from /includes/block/controls/types/post-query.php
const {
  allTaxonomies = {}
} = Tangible.postQueryControlData


const QueryGroup = ({ value, initialData, save, showBtn, remove }) => {

  const isFirst = value === 1
  const taxonomies = Object.keys(allTaxonomies)
  const [ params, setParams ] = useState( initialData )

  useEffect( () => {
    save( params )
  }, [ params ])

  const getTerms = ( selectedTaxonomy ) => {
    const terms = allTaxonomies[ selectedTaxonomy ]

    if ( terms == null || terms.length === 0 ) return []

    let options = []
    terms.forEach( term => {
      options.push({ label: term.name, value: term.slug })
    })
    return options
  }

  const getParam = ( x ) => {
    if ( params.hasOwnProperty(x) ){
      if ( params[x] === '' ) return null
      return params[x]
    }
    return null
  }

  const updateTerms = ( value ) => {
    // Reinitialize options - Forcing re-render
    setTermOptions( oldTerms => {
      return []
    } )

    if ( value.length > 0 ){
      setTermOptions( oldTerms => {
        return value
      } )
    }
  }

  const updateParam = ( field, value ) => setParams( oldParams => {
    return { ...oldParams, [ field ]: value }
  })

  const [ termOptions, setTermOptions ] = useState(
    params.hasOwnProperty(`taxonomy`) ? getTerms( params[`taxonomy`] ) : []
  )

  const option = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label">Taxonomy { !isFirst && value }</label>
      <Select
        style={{ width: '100%' }}
        labelForEmptyValue={ `Taxonomy ${ isFirst ? '' : value }` }
        options={ taxonomies.map( tax => {
          return { label: tax, value: tax }
        } )}
        value={ getParam(`taxonomy`) }
        onChange={ value => {
          updateParam( 'terms', '')
          updateParam( 'taxonomy', value)

          const newTerms = getTerms( value )
          updateTerms( newTerms )
        } }
      />
    </div>
  </div>

  const compare = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label">Compare { !isFirst && value }</label>
      <Select
        style={{ width: '100%' }}
        labelForEmptyValue={ `Compare ${ isFirst ? '' : value }` }
        options={[
          { label: 'In', value: 'in'},
          { label: 'Not', value: 'not'},
          { label: 'And', value: 'and'},
          { label: 'Exists', value: 'exists'},
          { label: 'Not exists', value: 'not exists'},
        ]}
        value={ params.taxonomy_compare }
        onChange={ value => updateParam(`taxonomy_compare`, value) }
      />
    </div>
  </div>

  const selectedValues = getParam(`terms`)
  const values = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label">Term { !isFirst && value }</label>
      <Select
        style={{ width: '100%' }}
        labelForEmptyValue={ `Terms ${ isFirst ? '' : value }` }
        multiSelect={ true }
        options={ termOptions }
        value={ selectedValues ? selectedValues.split(',') : ['null'] }
        onChange={ values => setParams( oldParams => {
          let selection = ''
          if ( values != 'null' ) selection = values.toString()

          return { ...oldParams, [`terms`]: selection }
        }) }
      />
    </div>
  </div>


  return (
    <div className="tangible-logic-rule">
      { option }
      { compare }
      { values }

      { showBtn &&
      <button id="logic-button-rule" className="tangible-components-button is-secondary" type="button" onClick={ remove }>
        Remove
      </button>
      }
    </div>
  )
}

export default QueryGroup
