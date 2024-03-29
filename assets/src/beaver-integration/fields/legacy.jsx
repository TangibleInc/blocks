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
    
    const controlContainers = window.parent.document.getElementsByClassName(`${control.prefixed_type}-container`)

    if( controlContainers.length === 0 ) return;
    
    document.body.classList.add('tangible-block-legacy-is-edited')

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
        
        document.body.classList.add('tangible-block-legacy-popup-is-open')
        const onClose = () => document.body.classList.remove('tangible-block-legacy-popup-is-open')

        render( 
          initComponent(control, $input, data, onClose), 
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

    document.body.classList.remove('tangible-block-legacy-is-edited')

    const controlContainers = window.parent.document.getElementsByClassName(`tangible-block-control-${control.prefixed_type}-container`)

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

  /**
   * Remove edit class when change edited widget without closing lightbox
   */
  FLBuilder.addHook('hideContentPanel', () => {
    document.body.classList.remove('tangible-block-legacy-is-edited')
  })

}

const initComponent = (control, $input, field, onPopupClose = false) => (
  <LegacyControl
    config={ control }
    initialValue={ $input.val() }
    builder={ 'beaver-builder' }
    field={ field }
    save={ value => {
      $input.val(value)
      $input.trigger('change')
    }}
    onPopupClose={ onPopupClose }
  />
)

export { 
  initLegacyControl 
}
