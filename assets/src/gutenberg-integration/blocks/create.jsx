import ControlVisibility from '../../template-controls-visibility'
import { getField } from './fields/index'

const { wp, Tangible } = window
const {
  blockEditor: { InspectorControls },
  blocks: { registerBlockType },
  components: { Panel, PanelBody, PanelRow },
  element: { useState },
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
      const [activeTab, setActiveTab] = useState(data.tabs[0])

      const getFieldValue = name =>
        Number.isInteger(props.attributes[ name ])
          ? props.attributes[ name ].toString()
          : props.attributes[ name ]

      const visibility = new ControlVisibility(
        blockConfig.conditions[ 
          data.universal_id ? data.universal_id : data.content_id 
        ]
      )

      const isVisible = conditions => (
        visibility.evaluateConditions(conditions, getFieldValue)
      )

      // We will need this unique ID in the server-side render function to create a wrapper
      if ( ! block_id ) props.setAttributes({ block_id: props.clientId })

      /**
       * Current post ID
       * Used in integrations/gutenberg/render to set loop context
       */
      if ( ! props.attributes.current_post_id ) {
        props.setAttributes({ current_post_id: blockConfig.current_post_id })
      }

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

            { activeTab.sections.map((section, index) =>
              isVisible(section.conditions) &&
                <Panel key={`${section.name}-panel-${index}`} className={ 'tangible-block-editor-section' }>
                  <PanelBody title={ section.label } initialOpen={ index === 0 }>
                    { section.fields.map( item =>
                      isVisible(item.conditions) &&
                        <PanelRow>
                          { getField(
                            item, 
                            props.attributes[item.name], 
                            props.setAttributes
                          ) }
                        </PanelRow>
                    ) }
                  </PanelBody>
                </Panel>
            ) }
          </InspectorControls>

          <ServerSideRender
            block={blockType}
            attributes={props.attributes}
            EmptyResponsePlaceholder={ EmptyLoopBlock }
            LoadingResponsePlaceholder={ EmptyLoopBlock }
            onFetchResponseRendered={el => {
              moduleLoader(el)
            }}
          />
        </>
      )
    },

    save() {
      // Dynamic block
      return null
    }
  })
}

const EmptyLoopBlock = () => <div className="tangible-loop-block">&nbsp;</div>
