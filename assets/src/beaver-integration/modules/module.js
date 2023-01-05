import { init as initVisibility } from './visibility/general'
import { getEditor } from './form'

const {
  blockConfig: { 
    slug,
    visibility 
  }
} = Tangible

const $ = jQuery

export const initModule = blockId => {

  const moduleName = slug + blockId

  /**
   * Beaver builder field condtional logic is not enough to do what we want, we will implement 
   * our own logic system instead
   *
   * @see https://community.wpbeaverbuilder.com/t/problem-in-multiple-dependecy/7803/3
   */

  FLBuilder.registerModuleHelper(moduleName, {

    init() {

      this.bindMethods()

      this.form = $('.fl-builder-settings')
      this.blockId = blockId

      this.tabs       = visibility.tabs[ blockId ]       || {}
      this.sections   = visibility.sections[ blockId ]   || {}
      this.fields     = visibility.fields[ blockId ]     || {}
      this.repeaters  = visibility.repeaters[ blockId ]  || {}
      this.conditions = visibility.conditions[ blockId ] || {}

      if ( ! Object.keys(this.tabs).length
        && ! Object.keys(this.sections).length
        && ! Object.keys(this.fields).length
      ) {
        return // No visibility conditions
      }

      initVisibility(this)
    },

    bindMethods() {
      this.getTabSettings     = this.getTabSettings.bind(this)
      this.getSectionSettings = this.getSectionSettings.bind(this)
    },

    getTabSettings() {

      const settings = {}
      const $tabs = this.form.find('.fl-builder-settings-tabs a')

      $tabs.each( i => {

        /**
         * Get tab name from element href (!)
         * TODO: More reliable if name was passed from a data attribute
         */

        const element = $tabs[i]
        const href = element.getAttribute('href')
        const name = href.replace('#fl-builder-settings-tab-', '')

        if( ! name ) return;

        const setting = { name }

        setting.conditions = this.tabs[ setting.name ] || false
        setting.element = element

        settings[ setting.name ] = setting
      })

      return settings
    },

    getSectionSettings() {

      const settings = {}
      const $sections = this.form.find('.fl-builder-settings-section')

      $sections.each( i => {

        /**
         * Get section name from element ID
         * TODO: More reliable if name was passed from a data attribute
         */

        const element = $sections[i]
        const id = element.getAttribute('id')
        const name = id.replace('fl-builder-settings-section-', '')

        if( ! name ) return;

        const setting = {
          name
        }

        setting.conditions = this.sections[ setting.name ] || false
        setting.element = element

        settings[ setting.name ] = setting
      })

      return settings
    }

  })

}
