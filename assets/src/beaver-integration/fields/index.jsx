import Control from '../../template-block-fields/Control'

const { render, unmountComponentAtNode } = wp.element
const { blockConfig: { controls } } = Tangible

const $ = jQuery
const d = document

$(() => {
  for(const controlName in controls) initControl(controls[controlName])
})

/**
 * Init custom controls
 *
 * @see tangible-block-fields/Control.js
 */

const initControl = control => {

  FLBuilder.addHook('didShowLightbox', () => {

    const controlContainers = d.getElementsByClassName(`${control.prefixed_type}-container`)
    if( controlContainers.length === 0 ) return;

    for (let i = 0; i < controlContainers.length; i++) {

      const container = controlContainers[ i ]
      const $input = $(container).next()
      const field = JSON.parse(container.getAttribute('data-field'))

      if( !control.popup ) {
        render( initComponent(control, $input, field), container )
        continue;
      }

      // We can't create the popup directly in the lightbox, but the button need to be there

      const popupContainer = d.createElement('div')
      popupContainer.setAttribute('class', `${control.prefixed_type}-popup-container`)
      d.body.append(popupContainer)

      const buttonClasses = `tangible-block-control-button ${control.prefixed_type}-popup-container`
      const onClickAction = () => {
        unmountComponentAtNode(popupContainer)
        render( initComponent(control, $input, field), popupContainer )
      }

      render(<button className={ buttonClasses } onClick={ onClickAction }>Open Settings</button>, container)
    }

  })

  /**
   * @see _lightboxClosed() in /bb-plugin/js/fl-builder.js
   */
  FLBuilder.addHook('settings-lightbox-closed', () => {

    const controlContainers = d.getElementsByClassName(`tangible-block-control-${control.prefixed_type}-container`)

    if( controlContainers.length === 0 ) return;

    for (let i = 0; i < controlContainers.length; i++) {
      unmountComponentAtNode(controlContainers[i])
    }

    if( !control.popup ) return;

    const popupContainers = d.getElementsByClassName(`${control.prefixed_type}-popup-container`)

    for (let i = 0; i < popupContainers.length; i++) {
      unmountComponentAtNode(popupContainers[i])
    }
  })

}

const initComponent = (control, $input, field) => (
  <Control
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