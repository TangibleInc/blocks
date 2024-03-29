import {
  render, 
  unmountComponentAtNode
} from 'react'

import Control from '../../template-block-fields/Control'
import { initLegacyControl } from './legacy'

const $ = jQuery
const { 
  blockConfig: { 
    controls,
    legacy_controls 
  } 
} = Tangible

$(window).on('elementor:init', () => {
  
  for( const controlName in controls ) {
    initControl(controls[controlName])
  }
  
  for( const controlName in legacy_controls ) {
    initLegacyControl(legacy_controls[controlName])
  }

})

/**
 * Init custom controls
 * 
 * @see tangible-block-fields/Control.js  
 */

const initControl = control => {

  const elementorControl = elementor.modules.controls.BaseData.extend({

    onReady: function() {

      const wrapper = this.ui.contentEditable.prevObject[0]
      const reactDiv = wrapper.getElementsByClassName(control.prefixed_type)[0]

      const field = JSON.parse(reactDiv.getAttribute('data-field'))

      const saveValue = this.saveValue.bind(this)

      render(<Control 
        config={ control }
        field={ field }
        initialValue={ this.ui.input.val() }
        builder={ 'elementor' }
        save={ saveValue } 
      />, reactDiv)
    },

    saveValue: function(value) {
      this.ui.input.val(value)
      this.setValue(this.ui.input.val()) 
    },

    /**
     * Called automatically by elementor
     */
    onBeforeDestroy: function() {

      this.saveValue(this.ui.input.val())

      const wrapper = this.ui.contentEditable.prevObject[0]
      unmountComponentAtNode(wrapper)
    }
  })

  elementor.addControlView(control.prefixed_type, elementorControl)
}
