import { 
  useState, 
  useEffect 
} from 'react'

import Modal from '../common/modal/Modal'
import { getControl } from './'

const Control = props => {

  const { 
    config, 
    field, 
    builder, 
    initialValue 
  } = props

  const isPopup = config.popup

  const [isOpen, setIsOpen] = useState(builder === 'beaver-builder')
  const [value = initialValue, setValue] = useState()
  const [saved = initialValue, setSaved] = useState()
  
  const save = data => isPopup ? setValue(data) : (setSaved(data), setValue(data))

  useEffect(() => props.save(saved), [saved])
  useEffect(() => isPopup && isOpen && setValue(saved), [isOpen])

  const control =
    <div className={ `tangible-block-control tangible-block-control-${config.type}` }>
      { getControl(save, saved, field, builder) }
    </div>
  
  if( ! isPopup ) return control;

  const cancel = () => setIsOpen(false)
  const submit = () => { setIsOpen(false), setSaved(value) }
  
  /**
   * The button/onClick logic is not handled here in the case of beaver-builder because we need to create 
   * the button and the popup at 2 different places in the DOM
   * 
   * @see assets/src/beaver-template-editor/fields/dynamic/index.js
   */

  const button = builder !== 'beaver-builder' &&
    <div className={ `tangible-control-modal-button-container tangible-control-modal-button-container-${builder}` }>

      { builder === 'elementor' && 
        <label className='elementor-control-title'>
          { field.label }
        </label> }
    
      <button onClick={ () => setIsOpen(!isOpen) } class="components-button is-secondary tangible-control-btn">
        Open Settings
      </button>
    </div>
    

  if( ! isOpen ) return button;

  return(
    <>
      { button }
      <Modal submit={ submit } cancel={ cancel } title={ field.label }>
        { control }
      </Modal>
    </>
  )
}

export default Control
