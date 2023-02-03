import {
  render, 
  unmountComponentAtNode
} from 'react'

import LegacyControl from '../../template-block-fields/legacy-fields/LegacyControl'

/**
 * Init custom legacy controls
 * 
 * @see tangible-block-fields/Control.js  
 */

const initLegacyControl = control => {

  const elementorControl = elementor.modules.controls.BaseData.extend({

    onReady: function() {

      const wrapper = this.ui.contentEditable.prevObject[0]
      const reactDiv = wrapper.getElementsByClassName(control.prefixed_type)[0]

      const field = JSON.parse(reactDiv.getAttribute('data-field'))

      const saveValue = this.saveValue.bind(this)

      render(<LegacyControl 
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

export {
  initLegacyControl
}
