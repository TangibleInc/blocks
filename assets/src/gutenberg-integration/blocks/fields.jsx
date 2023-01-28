import Control from '../../template-block-fields/Control'

const { components: { BaseControl } } = wp
const { blockConfig: { controls } } = Tangible

/**
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/
 */
const getField = (item, value, save) => {

  const className = `tangible-block-editor-control-${ item.type }`

  /**
   * Check if custom control
   *
   * @see tangible-block-fields/Control.js
   */

  if( controls[ item.type ] ) {

    const control = controls[ item.type ]
    
    return (
      <BaseControl label={ item.label } className={ className  }>
        <Control
          config={ control }
          initialValue={ value }
          builder={ 'gutenberg' }
          field={ item }
          save={ value => save({ [item.name]: value }) }
        />
      </BaseControl>
    )
  }

}

export { getField }
