import ControlVisibility from '../../template-controls-visibility'

import { getField } from './fields'
import { getLegacyField } from './legacy-fields/index'

const {
  blockEditor: { InspectorControls },
  blocks: { registerBlockType },
  components: { Panel, PanelBody, PanelRow },
  element: { useState, createContext, useEffect },
  i18n: { __ },
  serverSideRender: _ServerSideRender
} = wp

const {
  blockConfig,
  moduleLoader,
  /**
   * From Template System
   * @see template-system/system/assets/src/gutenberg-template-editor/blocks/template/ServerSideRender.jsx
   */
  ServerSideRender = _ServerSideRender
} = Tangible

export const BlockContext = createContext()

const uniqueIds = []

export const createBlock = data => {

  /**
   * Block type must be the same on backend
   * @see /includes/integrations/gutenberg/dynamic/index.php
   */
  const blockType = `${blockConfig.package}/${
    data.universal_id
      ? 'block-'+data.universal_id
      : data.name
  }`

  registerBlockType(blockType, {

    title: data.label,
    description: data.description ? data.description : '',
    category: blockConfig.category,
    sections: data.sections,
    icon: blockConfig.icon,

    edit(props) {

      const { block_id } = props.attributes
      const { block_loaded } = props.attributes ?? false

      useEffect(() => {
        let id = block_id

        // We will need this unique ID in the server-side render function to create a wrapper
        if (!id || uniqueIds.includes(id)) {
          id = props.clientId
          props.setAttributes({ block_id: id })
        }

        if ( !uniqueIds.includes(id) ) uniqueIds.push(id)
        props.setAttributes({ block_loaded: true })
      }, [])
      const [activeTab, setActiveTab] = useState(data.tabs[0])

      const conditions = blockConfig.conditions[ 
        data.universal_id ? data.universal_id : data.content_id 
      ]
        // Provide default conditions in case it's undefined
        || { general: [], repeater: [] }

      const visibility = new ControlVisibility(conditions.general)

      const getFieldValue = name =>
        Number.isInteger(props.attributes[ name ])
          ? props.attributes[ name ].toString()
          : props.attributes[ name ]

      const isVisible = conditions => (
        visibility.evaluateConditions(conditions, getFieldValue)
      )
      
      /**
       * Current post ID
       * Used in integrations/gutenberg/render to set loop context
       */
      if ( ! props.attributes.current_post_id ) {
        props.setAttributes({ current_post_id: blockConfig.current_post_id })
      }

      if ( block_loaded ) {
        return(
          <>
            <InspectorControls>
  
              { data.tabs.length > 1 &&
                <div className='tangible-block-editor-tabs'>
                  { data.tabs.map(tab =>
                    (tab.name === activeTab.name // Active tab is always visible
                      || isVisible(tab.conditions)
                    ) &&
                    <div key={`tab-${tab.name}`}
                      className={ "tangible-block-editor-tab components-button edit-post-sidebar__panel-tab"
                        +(tab.name === activeTab.name ? ' is-active' : '')
                      }
                      onClick={ () => setActiveTab(tab) }
                    >
                      <p>{ tab.label === 'default' ? 'Content' : tab.label }</p>
                    </div>
                  ) }
                </div>
              }
  
              <BlockContext.Provider value={{ 
                id: block_id, 
                conditions: conditions
              }}>
                { activeTab.sections.map((section, index) =>
                  isVisible(section.conditions) &&
                    <Panel key={`${section.name}-panel-${index}`} className={ 'tangible-block-editor-section' }>
                      <PanelBody title={ section.label } initialOpen={ index === 0 }>
                        { section.fields.map( item =>
                          isVisible(item.conditions) && 
                            <PanelRow>
                              { data.legacy_controls
                                ? getLegacyField(
                                    item, 
                                    props
                                  )
                                : getField(
                                    item, 
                                    props.attributes[item.name], 
                                    props.setAttributes
                                  ) }
                            </PanelRow>
                        ) }
                      </PanelBody>
                    </Panel>
                ) }
              </BlockContext.Provider>
  
            </InspectorControls>
  
            <ServerSideRender
              /**
               * Note: Ensure props are equal on every render - for example,
               * don't create new function here because it fetches on prop change.
               */
              block={ blockType }
              attributes={ props.attributes }
              httpMethod={ 'POST' }
              EmptyResponsePlaceholder={ EmptyLoopBlock }
              LoadingResponsePlaceholder={ EmptyLoopBlock }
              onFetchResponseRendered={ moduleLoader }
            />
          </>
        )
      }
    },

    save() {
      // Dynamic block
      return null
    }
  })
}

const EmptyLoopBlock = () => <div className="tangible-loop-block">&nbsp;</div>
