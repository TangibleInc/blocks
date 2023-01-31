import Select from "../../../common/Select";

const { useState, useEffect } = wp.element

const Type = ({ initialLoopType, initialType, setData, fields }) => {

  const [loopType, setLoopType] = useState(initialLoopType ? initialLoopType : 'post')
  const [type, setType] = useState(initialType)

  useEffect(() => setData('loopType', loopType), [loopType])
  useEffect(() => setData('type', type), [type])

  // Core content types (Only post supported for now)
  const contentTypeOptions = [
    { label: 'Post', value: 'post'},
    // { label: 'Attachment', value: 'attachment'},
    // { label: 'Taxonomy', value: 'taxonomy'},
    // { label: 'Taxonomy Term', value: 'taxonomy term'},
    // { label: 'User', value: 'user'},
    // { label: 'Calendar', value: 'calendar'},
  ]

  const contentType = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label">Content type</label>
      <Select
        labelForEmptyValue="Choose a Type"
        options={ contentTypeOptions }
        value={ loopType }
        onChange={ value => setLoopType(value) }
        style={{ width: '100%' }}
      />
    </div>
  </div>

  // Fields associated with this 'content type'
  const contentFields = loopType === 'post' 
    ? <div className="tangible-components-control">
        <div className="tangible-components-control__field">
          <label className="tangible-components-control__label" htmlFor="post_type">Fields</label>
          <Select
            labelForEmptyValue="Choose Fields"
            value={ type ? type.split(',') : ['null'] }
            options={ fields }
            multiSelect={ true }
            style={{ width: '100%' }}
            onChange={ values => values == 'null'
              ? setType('')
              : setType(values.toString())
            }
          />
        </div>
      </div> 
    : ''


  return (
    <div className='tangible-logic-rule'>
      { contentType }
      { contentFields }
    </div>
  )
}

export default Type
