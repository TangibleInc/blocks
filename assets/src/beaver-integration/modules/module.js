import ControlVisibility from '../../template-controls-visibility'

const {
  blockConfig: { visibility, slug }
} = window.Tangible

const $ = jQuery

export const initModule = (blockId) => {

  const moduleName = slug + blockId

  /**
   * Beaver builder field condtional logic is not enough to do what we want, we will implement our own logic system instead
   *
   * @see https://community.wpbeaverbuilder.com/t/problem-in-multiple-dependecy/7803/3
   */

  FLBuilder.registerModuleHelper(moduleName, {

    init() {

      this.bindMethods()

      this.form = $('.fl-builder-settings')

      this.tabs     = visibility.tabs[ blockId ]     || {}
      this.sections = visibility.sections[ blockId ] || {}
      this.fields   = visibility.fields[ blockId ]   || {}

      if ( !Object.keys(this.tabs).length
        && !Object.keys(this.sections).length
        && !Object.keys(this.fields).length
      ) {
        return // No visibility conditions
      }

      this.visibility = new ControlVisibility(
        visibility.conditions[ blockId ]
      )

      this.initEvents()
    },

    bindMethods() {
      this.setVisibility      = this.setVisibility.bind(this)
      this.initEvents         = this.initEvents.bind(this)
      this.getEditor          = this.getEditor.bind(this)

      this.getTabSettings     = this.getTabSettings.bind(this)
      this.getSectionSettings = this.getSectionSettings.bind(this)
      this.getFieldSettings   = this.getFieldSettings.bind(this)
      this.getFieldSetting    = this.getFieldSetting.bind(this)
    },

    initEvents() {

      this.setVisibility() // Hide/Show on first init

      this.form.find('select').on('change', this.setVisibility)
      this.form.find('input').on('change', this.setVisibility)
      this.form.find('input[type=text], input[type=number]').on('input', this.setVisibility)

      // WYSIWYG editors
      const $editors = this.form.find('.fl-editor-field')
      $editors.each( i => {
        if( this.getEditor($editors[i]) ) {
          this.getEditor($editors[i]).on('change', this.setVisibility)
        }
      })
    },

    /**
     * For wysiwyg/editor, beaver builder use tinyMCE so we need to use it to get value/events
     */
    getEditor(div) {

      const id = $(div).find('.wp-editor-tabs button').attr('data-wp-editor-id')

      return window.tinyMCE ? window.tinyMCE.get(id) : false
    },

    /**
     * We get settings differently according to DOM element used
     */
    getFieldSetting(field) {

      switch(field.tagName) {

      case 'SELECT':
        if(field.multiple) {
          return field.name !== ''
            ? { name: field.name, value: $(field).val().join(',') }
            : false
        }

        // falls through - https://eslint.org/docs/rules/no-fallthrough

      case 'INPUT':
        return field.name !== ''
          ? { name: field.name, value: $(field).val() }
          : false

        // Tiny MCE content (should we support condition for this?)
      case 'DIV':

        const name = $(field).attr('data-name')
        const editor = this.getEditor(field)

        return { name: name, value: editor.getContent() }
      }

      return false
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

        if( !name ) return

        const setting = {
          name
        }

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

        if( !name ) return

        const setting = {
          name
        }

        setting.conditions = this.sections[ setting.name ] || false
        setting.element = element

        settings[ setting.name ] = setting
      })

      return settings
    },

    getFieldSettings() {

      const $fields = this.form.find('select, input, .fl-editor-field')
      const settings = {}

      $fields.each( i => {

        const setting = this.getFieldSetting( $fields[i] )
        if( !setting ) return

        setting.conditions = this.fields[ setting.name ] || false
        setting.element = $fields[i]

        settings[ setting.name ] = setting
      })

      return settings
    },

    setVisibility() {

      // Evaluate and apply visibility of tabs/sections/fields

      const tabSettings = this.getTabSettings()
      const sectionSettings = this.getSectionSettings()
      const fieldSettings = this.getFieldSettings()

      const getFieldValue = name => fieldSettings[name]
        ? fieldSettings[name].value
        : undefined

      // Tabs

      for (const tabName in tabSettings) {

        const tabSetting = tabSettings[ tabName ]

        if ( !tabSetting.conditions
            || !tabSetting.conditions.length
        ) continue // Always visible

        const isTabVisible = this.visibility.evaluateConditions(
          tabSetting.conditions,
          getFieldValue
        )

        // console.log('Tab visibility', tabName, isTabVisible)

        const $tab = $(tabSetting.element)

        // NOTE: Always show currently active tab
        if (!$tab.hasClass('fl-active')) {
          $tab[ isTabVisible ? 'show' : 'hide' ]()
        }
      }

      // Sections

      for (const sectionName in sectionSettings) {

        const sectionSetting = sectionSettings[ sectionName ]

        if ( !sectionSetting.conditions
            || !sectionSetting.conditions.length
        ) continue // Always visible

        const isSectionVisible = this.visibility.evaluateConditions(
          sectionSetting.conditions,
          getFieldValue
        )

        // console.log('Section visibility', sectionName, isSectionVisible)

        const $section = $(sectionSetting.element)

        $section[ isSectionVisible ? 'show' : 'hide' ]()
      }

      // Fields

      for ( const fieldName in fieldSettings ) {

        const fieldSetting = fieldSettings[ fieldName ]

        if ( !fieldSetting.conditions
            || !fieldSetting.conditions.length
        ) continue // No conditions: The field is always visible

        const isFieldVisible = this.visibility.evaluateConditions(
          fieldSetting.conditions,
          getFieldValue
        )

        // console.log('Field visibility', fieldName, isSectionVisible)

        const $field = $(fieldSetting.element).closest('.fl-field')

        $field[ isFieldVisible ? 'show' : 'hide' ]()
      }
    },

  })

}
