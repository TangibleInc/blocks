import Select from "../../../common/Select";

const Order = ({ getData, setData, includeOrder, includeOrderBy }) => {

  // Order label and select
  const order = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label" htmlFor="order">Order</label>
      <Select
        labelForEmptyValue="Choose Order"
        options={[ { label: 'ASC', value: 'asc' }, { label: 'DESC', value: 'desc' } ]}
        value={ getData('order') }
        style={{ width: '100%' }}
        onChange={ value => setData( 'order', value ) }
      />
    </div>
  </div>

  // Orderby label and select
  const orderByOptions = [
    { label: 'Title', value: 'title'},
    { label: 'ID', value: 'id'},
    { label: 'Author', value: 'author'},
    { label: 'Name', value: 'name'},
    { label: 'Type', value: 'type'},
    { label: 'Date', value: 'date'},
    { label: 'Modified', value: 'modified'},
    { label: 'Random', value: 'random'},
    { label: 'Comment count', value: 'comment_count'},
    { label: 'Relevance', value: 'relevance'},
    { label: 'Menu', value: 'menu'},
    { label: 'Custom value', value: 'meta_value'},
    { label: 'Custom value number', value: 'meta_value_num'},
  ]
  const orderBy = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label" htmlFor="orderby">Order by</label>
      <Select
        labelForEmptyValue="Order by"
        options={ orderByOptions }
        value={ getData('orderby') }
        style={{ width: '100%' }}
        onChange={ value => setData( 'orderby', value ) }
      />
    </div>
  </div>

  // Orderbyfield label and select
  // Could use argument _builtin in query to get select?
  const orderByField = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label" htmlFor="orderby_field">Order by custom field</label>
      <input
        className="tangible-components-text-control__input"
        type="text" name="orderby-field" id="orderby_field"
        onChange={ e => setData( 'orderby_field', e.target.value ) }
        placeholder="Enter field key"
        value={ getData('orderby_field')}
      />
    </div>
  </div>

  // Custom field whose value is a number
  const orderByFieldNumber = <div className="tangible-components-control">
    <div className="tangible-components-control__field">
      <label className="tangible-components-control__label" htmlFor="orderby_field_number">Order by custom field with numeric value</label>
      <input
        className="tangible-components-text-control__input"
        type="text" id="orderby_field_number" name="orderby-field-number"
        onChange={ e => setData( 'orderby_field_number', e.target.value ) }
        placeholder="Enter field key"
        value={ getData('orderby_field_number')}
      />
    </div>
  </div>


  return (
    <div className='tangible-logic-rule'>
      { includeOrder && order }
      { includeOrderBy && orderBy }
      { getData('orderby') === 'meta_value' && orderByField }
      { getData('orderby') === 'meta_value_num' && orderByFieldNumber }
    </div>
  )
}

export default Order
