import { widgetVisibility } from './visibility/general'

jQuery(() => {

  /**
   * Triggered each time Elementor's editor open something
   */
  elementor.hooks.addAction( 'panel/open_editor/widget', (panel, model) => {
    
    if( model.attributes.elType !== 'widget' ) return;

    widgetVisibility(model)
    
  })

})

