import Modal from '../common/modal/Modal'
import { getControl } from './'

const { useState, useEffect } = wp.element

const Control = props => {

  const { config, field, builder, initialValue } = props

  const isPopup = config.popup

  const [isOpen, setIsOpen] = useState(builder === 'beaver-builder')
  const [value = initialValue, setValue] = useState()
  const [saved = initialValue, setSaved] = useState()
  
  const save = data => isPopup ? setValue(data) : (setSaved(data), setValue(data))

  useEffect(() => props.save(saved), [saved])
  useEffect(() => { isPopup && isOpen ? setValue(saved) : null }, [isOpen])

  const labelStyle = { display: 'flex', marginBottom: '10px' }

  const control =
    <>
      { builder === 'elementor' && !isPopup ?
        <label className={ 'elementor-control-title' } style={ labelStyle }>
          { field.label }
        </label> : '' }

      <div className={ `tangible-block-control tangible-block-control-${config.type}` }>
        { getControl(config, save, saved, field) }
      </div>
    </>
  
  if( !isPopup ) return control;

  const cancel = () => setIsOpen(false)
  const submit = () => { setIsOpen(false), setSaved(value) }
  
  /**
   * The button/onClick logic is not handled here in the case of beaver-builder because we need to create 
   * the button and the popup at 2 different places in the DOM
   * 
   * @see assets/src/beaver-template-editor/fields/dynamic/index.js
   */

  const button = builder !== 'beaver-builder'
    ? <div className={ `tangible-control-modal-button-container tangible-control-modal-button-container-${builder}` }>

        { builder === 'elementor' ? <label className='elementor-control-title' style={ labelStyle } >{ field.label }</label> : '' }
      
        <button onClick={ () => setIsOpen(!isOpen) } className='components-button is-secondary tangible-control-btn'>
          Open Settings
        </button>
      </div>
    : ''

  if(!isOpen) return button;

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
