import QueryGroup from './query-group'
import Select from "../../../common/Select"

const { wp } = window
const { useState, useEffect } = wp.element
const MAXQUERYNUMBER = 3 // Only a max of 3 are supported at the moment

// Not checking whether queries 1-3 are choosing the same taxonomy
// Do we want Query relation to be and on default?
const Taxonomy = ({ initialData, save }) => {

  const [rows, setRows] = useState([])
  const [relation, setRelation] = useState('')

  /**
   * Convert previously saved json to an array
   */
  useEffect(() => {

    if( !initialData ) return;

    const data = JSON.parse(initialData)
    const initialRows = []

    if( data.taxonomy_relation ) setRelation(data.taxonomy_relation)
    if( !data.taxonomy ) return;

    let suffix = ''
    let i = 1

    while(data[`taxonomy${suffix}`] && i <= MAXQUERYNUMBER) {

      suffix = i !== 1 ? `_${i}` : ''

      if( !data[`taxonomy${suffix}`] ) break;

      initialRows.push({
        taxonomy: data[`taxonomy${suffix}`], 
        terms:  data[`terms${suffix}`],
        taxonomy_compare:  data[`taxonomy_compare${suffix}`]
      })
      i++
    }

    setRows(initialRows)
  }, [])

  /**
   * Convert and save our array to data expected by the save (taxnonomy, taxnonomy_2 ... etc)
   */
  useEffect(() => {

    const savedData = {}

    if( relation ) savedData.taxonomy_relation = relation

    for (let i = 0; i < rows.length; i++) {
      
      const suffix = i !== 0 ? `_${i + 1}` : ''

      savedData[`taxonomy${suffix}`] = rows[i].taxonomy
      savedData[`terms${suffix}`] = rows[i].terms
      savedData[`taxonomy_compare${suffix}`] = rows[i].taxonomy_compare
    }

    save(savedData)
  }, [rows, relation])

  const taxonomyRelation = <div className="tangible-logic-rule tangible-logic-clear">
    <div className="tangible-components-control">
      <div className="tangible-components-control__field">
        <label className="tangible-components-control__label">Relation</label>
        <Select
          style={{ width: '100%' }}
          labelForEmptyValue="Choose Relation"
          options={[ 
            { label: 'And', value: 'and'}, 
            { label: 'Or', value: 'or'} ]}
          value={ relation }
          onChange={ value => setRelation(value) }
        />
      </div>
    </div>
  </div>

  const addRow = () => {
    setRows([...rows, { 
      taxonomy: '', 
      terms: '',
      taxonomy_compare: ''
    }])
  } 

  const saveRow = (index, data) => {
    rows[index] = data
    setRows([...rows])
  }

  const removeRow = index => {
    setRows([
      ...rows.slice(0, index),
      ...rows.slice(index + 1) ])
  }

  return (
    <div className="tangible-logic-rule-group-box-bordered">

      { rows.length > 1 &&
        <div className="tangible-logic-taxonomy-group">
          { taxonomyRelation }
        </div>
      }

      <div className="tangible-logic-taxonomy-group">
        { rows.length > 0 && rows.map((item, i) => (
          <QueryGroup
            key={ i + JSON.stringify(item) }
            value={ i + 1 }
            initialData={ item }
            showBtn={ true }
            save={ data => saveRow(i, data) }
            remove={ () => removeRow(i) }
          />
        ))}
      </div>
    
    { rows.length < MAXQUERYNUMBER &&
      <div className="tangible-logic-group-actions">
        <button className="tangible-components-button is-secondary" type="button" onClick={ addRow }>
          Add taxonomy filter
        </button>
        <button className="tangible-components-button is-secondary" type="button" onClick={ () => setRows([]) }>
          Remove
        </button>
      </div> 
    }
    
    </div>
  )
}

export default Taxonomy
