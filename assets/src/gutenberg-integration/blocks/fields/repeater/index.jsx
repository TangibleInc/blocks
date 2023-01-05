import ControlVisibility from '../../../../template-controls-visibility'

import { BlockContext } from '../../create'
import { getField } from '../index'
import { reducer } from './reducers'

const { wp } = window
const {
  components: {
    Button,
    ButtonGroup,
    Card, 
    CardBody,
    CardHeader,
    CardFooter
  },
  element: { 
    useState, 
    useReducer,
    useEffect,
    useContext
  }
} = wp

const Repeater = props => {

  const [items, dispatch] = useReducer(reducer, props.value)
  const [activeItem, setActiveItem] = useState(false)

  const { conditions } = useContext(BlockContext)

  const visibility = new ControlVisibility(
    conditions.repeater[ props.name ] ?? []
  )

  const isVisible = (conditions, i) => (
    visibility.evaluateConditions( 
      conditions, 
      name => getFieldValue(name, i) 
    )
  )

  const getFieldValue = (name, i) => (
    Number.isInteger(items[i][ name ])
      ? items[i][name].toString()
      : items[i][name]
  )

  useEffect(() => props.onChange(items), [items])
  
  return(
    <>
      { items.map((item, i) => (
        <Card>
          <CardHeader>
            <strong>Item { i + 1 }</strong>
          </CardHeader>
          { activeItem === i && props.controls.map(
            control => (
              isVisible(control.conditions ?? [], i) 
                ? 
                  <CardBody>
                    { getField(
                      control, 
                      item[control.name] ?? '', 
                      data => dispatch({ 
                        type: 'update', 
                        item: i, 
                        control: control.name, 
                        value: data[control.name]
                      })
                    ) } 
                  </CardBody>
                : '' 
            )
          ) }
          <CardFooter>
            <ButtonGroup>
              <Button
                onClick={ () => setActiveItem( i !== activeItem ? i : false )}
                variant={ 'secondary' } 
              >
               { activeItem !== i ? 'Edit' : 'Close' }
              </Button>
              <Button 
                onClick={ () => dispatch({ 
                  type: 'remove', 
                  item: i 
                }) } 
                variant={ 'secondary' } 
                isDestructive
              >
                Delete
              </Button>
            </ButtonGroup>
          </CardFooter>
        </Card>    
      )) }
      <ButtonGroup style={{ 
        display: 'flex', 
        marginTop: '10px', 
        justifyContent: 'right' 
      }}>
        <Button 
          onClick={ () => dispatch({ 
            type: 'add', 
            controls: props.controls,
            structure: props.structure 
          }) }
          variant={ 'secondary' } 
        >
          Add
        </Button>
        <Button 
          onClick={ () => dispatch({ type: 'clear' }) }
          variant={ 'secondary' } 
          isDestructive
        >
          Clear
        </Button>
      </ButtonGroup>
    </>
  )
}

export default Repeater
