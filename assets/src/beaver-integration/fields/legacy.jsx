import { 
  render, 
  unmountComponentAtNode 
} from 'react'

import LegacyControl from '../../template-block-fields/legacy-fields/LegacyControl'

const $ = jQuery

/**
 * Init custom controls
 *
 * @see tangible-block-fields/Control.js
 */
const initLegacyControl = control => {
  
  FLBuilder.addHook('didShowLightbox', () => {

    const controlContainers = document.getElementsByClassName(`${control.prefixed_type}-container`)

    if( controlContainers.length === 0 ) return;
    
    for (let i = 0; i < controlContainers.length; i++) {

      const container = controlContainers[ i ]
      const $input = $(container).next()

      const data = JSON.parse(container.getAttribute('data-field'))
      
      if( ! control.popup ) {
        render( initComponent(control, $input, data), container )
        continue;
      }

      /**
       * We can't create the popup directly in the lightbox, but the button needs to be there
       */

      const popupContainer = document.createElement('div')
      
      popupContainer.setAttribute('class', `${control.prefixed_type}-popup-container`)
      document.body.append(popupContainer)

      const buttonClasses = `tangible-block-control-button ${control.prefixed_type}-popup-container`
      
      const onClickAction = () => {
        
        unmountComponentAtNode(popupContainer)
        
        render( 
          initComponent(control, $input, data), 
          popupContainer 
        )
      }

      render(
        <button className={ buttonClasses } onClick={ onClickAction }>
          Open Settings
        </button>
      , container)
    }

  })

  /**
   * @see _lightboxClosed() in /bb-plugin/js/fl-builder.js
   */
  FLBuilder.addHook('settings-lightbox-closed', () => {

    const controlContainers = document.getElementsByClassName(`tangible-block-control-${control.prefixed_type}-container`)

    if( controlContainers.length === 0 ) return;

    for (let i = 0; i < controlContainers.length; i++) {
      unmountComponentAtNode(controlContainers[i])
    }

    if( ! control.popup ) return;

    const popupContainers = document.getElementsByClassName(`${control.prefixed_type}-popup-container`)

    for (let i = 0; i < popupContainers.length; i++) {
      unmountComponentAtNode(popupContainers[i])
    }
  })

}

const initComponent = (control, $input, field) => (
  <LegacyControl
    config={ control }
    initialValue={ $input.val() }
    builder={ 'beaver-builder' }
    field={ field }
    save={ value => {
      $input.val(value)
      $input.trigger('change')
    }}
  />
)

export { 
  initLegacyControl 
}
