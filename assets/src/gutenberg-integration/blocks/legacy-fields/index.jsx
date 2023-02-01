import Color from './color/index'
import Dimension from './dimension/index'
import TextArea from './textarea/index'
import Number from './number/index'

// Controls from custom fields
import LegacyControl from '../../../template-block-fields/legacy-fields/LegacyControl'

const { wp, Tangible } = window
const {
  components: {
    BaseControl,
    Button,
    ButtonGroup,
    DatePicker,
    GradientPicker, __experimentalGradientPicker, // Before WP v6
    Icon,
    SelectControl,
    TextControl,
    ToggleControl,
  },
  blockEditor: { MediaUpload, MediaUploadCheck },
} = wp
const { blockConfig: { legacy_controls } } = Tangible

/**
 * Extract only needed attachment fields
 *
 * Otherwise, Gutenberg throws an error "Request-URI Too Long"
 * because it sends all block attributes in GET request to JSON API.
 *
 * Used in "image" and "gallery" controls
 */

const extractMediaFields = media => ({
  id: media.id,
  url: media.url
})

/**
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/
 */
export const getLegacyField = (item, props) => {

  const value = props.attributes[item.name]
  const defaultValue = typeof item.default !== 'undefined' ? item.default : false

  const className = `tangible-block-editor-control-${ item.type }`

  switch(item.type) {

    case 'text':
      return(
        <TextControl
          className={ className }
          label={ item.label }
          value={ value }
          onChange={ value => props.setAttributes({ [item.name]: value }) }
        />
      )

    case 'select':
      const options = Object.keys(item.options).map((key) => (
        { value: key, label: item.options[key] }
      ))
      return(
        <SelectControl
          className={ item.multiple ? className + '-multiple' : className }
          label={ item.label }
          value={ value !== '' || !defaultValue ? value : defaultValue }
          options={ options }
          multiple={ item.multiple }
          onChange={ value => props.setAttributes({ [item.name]: value }) }
        />
      )

    case 'date':
      return(
        <BaseControl label={ item.label } className={ className  }>
          <DatePicker
            currentDate={ value ? new Date(value) : new Date() }
            onChange={ value  => props.setAttributes({ [item.name]: value }) }
            // Fix issue with month navigation, seems to be fixed but not released yet
            // @see https://github.com/WordPress/gutenberg/commit/749088ddf0072ca82ae297318dd29cd12d0a3c41
            onMonthPreviewed={ () => (true) }
          />
        </BaseControl>
      )

    /**
     * Maybe we could get a better control for this
     *
     * @see https://github.com/WordPress/gutenberg/tree/trunk/packages/components/src/
     */
    case 'color':
      return(
        <Color
          label={ item.label }
          value={ value }
          defaultValue={ defaultValue }
          alpha={ item.alpha }
          onChange={ (color) => props.setAttributes({ [item.name]: color }) }
          className={ className  }
        />
      )

    case 'align':

      if(value === '' && defaultValue) {
        props.setAttributes({ [item.name]: defaultValue })
      }
      
      return(
        <BaseControl label={ item.label } className={ className  }>
          <ButtonGroup style={ { display: 'block', marginTop: '10px' } }>
            <Button variant="secondary" onClick={ () => props.setAttributes({ [item.name]: "left" }) } ><Icon icon="editor-alignleft" /></Button>
            <Button variant="secondary" onClick={ () => props.setAttributes({ [item.name]: "center" }) } ><Icon icon="editor-aligncenter" /></Button>
            <Button variant="secondary" onClick={ () => props.setAttributes({ [item.name]: "right" }) } ><Icon icon="editor-alignright" /></Button>
          </ButtonGroup>
        </BaseControl>
      )

    case 'image':
      return(
        <BaseControl className={ className  }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( media ) => props.setAttributes({
                [item.name]: extractMediaFields(media)
              }) }
              allowedTypes={ [ 'image' ] }
              value={ value }
              render={ ( { open } ) => (
                <div>
                  <p>{ item.label }</p>
                  <img src={ value.url } style={ { width:'150px' } }/>
                  <Button isSecondary onClick={ open } style={ { display: 'block', marginTop: '10px' } }>
                    <Icon icon="upload" />  Open Media Library
                  </Button>
                </div>
              ) }
            />
          </MediaUploadCheck>
        </BaseControl>
      )

    case 'editor':
      // https://www.codexworld.com/add-wysiwyg-html-editor-to-textarea-website/
      return(
        <BaseControl className={ className  }>
          <div>
            <p>{ item.label }</p>
            <TextArea
              value={ value }
              updateVal={ value  => props.setAttributes({ [item.name]: value }) }
            />
          </div>
        </BaseControl>
      )

    case 'switch':
      const valueOn = item.hasOwnProperty('value_on') ? item.value_on : 'on'
      const valueOff = item.hasOwnProperty('value_off') ? item.value_off : 'off'
      const labelOn = item.hasOwnProperty('label_on') ? item.label_on : 'On'
      const labelOff = item.hasOwnProperty('label_off') ? item.label_off : 'Off'

      return(
        <BaseControl className={ className  }>
          <ToggleControl
            label={ item.label }
            help={ value === valueOn ? labelOn : labelOff }
            checked={ value === valueOn ? true : false }
            onChange={ e => e ? props.setAttributes({ [item.name]: valueOn }) : props.setAttributes({ [item.name]: valueOff }) }
          />
        </BaseControl>
      )

    case 'dimension':
      return(
        <Dimension
          className={ className }
          value={ value }
          label={ item.label }
          units={ item.units ? item.units.replace(/ /g, '').split(',') : '' }
          defaultUnit={ item.default_unit ? item.default_unit : 'px' }
          onChange={ value => props.setAttributes({ [item.name]: value }) }
          multipleValues={ item.multiple_values ? item.multiple_values : true }
        />
      )

    case 'gallery':
      let ids = []
      if(value){
        value.forEach((val, key) => {
          ids[key] = val['id'] ? val['id'] : val
        })
      }
      return(
        <BaseControl className={ className  }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( medias ) => props.setAttributes({
                [item.name]: medias.map(extractMediaFields)
              }) }
              allowedTypes={ [ 'image' ] }
              value={ ids }
              gallery={ true }
              multiple={ true }
              render={ ( { open } ) => (
                <div>
                  <p>{ item.label }</p>
                  <Button isSecondary onClick={ open } style={ { display: 'block', marginTop: '10px' } }>
                    <Icon icon="upload" />  Open Media Library
                  </Button>
                </div>
              ) }
            />
          </MediaUploadCheck>
        </BaseControl>
      )

    case 'number':
      return (
        <BaseControl className={ className  }>
          <div>
            <p>{ item.label }</p>
            <Number
              className={ className }
              value={ value }
              min={ item.min ? item.min : false }
              max={ item.max ? item.max : false }
              onChange={ value => { props.setAttributes({ [item.name]: value }) } }
            />
          </div>
        </BaseControl>
      )

    case 'gradient':
      let C = __experimentalGradientPicker || GradientPicker
      return (
        <BaseControl>
          <p>{ item.label }</p>
          <C
            value={ value }
            onChange={ value => { props.setAttributes({ [item.name]: value }) } }
            gradients={[
              {
                name: 'Vivid cyan blue to vivid purple',
                gradient:
                  'linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%)',
                slug: 'vivid-cyan-blue-to-vivid-purple',
              },
              {
                name: 'Light green cyan to vivid green cyan',
                gradient:
                  'linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)',
                slug: 'light-green-cyan-to-vivid-green-cyan',
              },
              {
                name: 'Luminous vivid amber to luminous vivid orange',
                gradient:
                  'linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%)',
                slug: 'luminous-vivid-amber-to-luminous-vivid-orange',
              }
            ]}
          />
        </BaseControl>
      )
  }

  /**
   * Check if custom control
   *
   * @see tangible-block-fields/Control.js
   */
  if( legacy_controls[ item.type ] ) {

    const legacy_control = legacy_controls[ item.type ]

    return (
      <BaseControl label={ item.label } className={ className  }>
        <LegacyControl
          config={ legacy_control }
          initialValue={ value }
          builder={ 'gutenberg' }
          field={ item }
          save={ value => props.setAttributes({ [item.name]: value }) }
        />
      </BaseControl>
    )
  }

}
