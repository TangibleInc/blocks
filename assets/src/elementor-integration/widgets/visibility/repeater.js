import ControlVisibility from '../../../template-controls-visibility'

const $ = jQuery

const setVisibility = ( 
  $container, 
  repeater,
  conditions
) => {

  const visibility = new ControlVisibility(conditions)
  
  const items  = repeater.value.models
  const $items = $container.find('.elementor-repeater-fields')
  
  items.forEach((item, i) => {
    
    const $item    = $($items[i])
    const values   = item.attributes
    const controls = item.controls

    const getValue = name => ( values[ 'tangible_control_' + name ] ?? undefined )

    for( const name in values ) {

      const $control = $item.find(`.elementor-control-${name}`)

      if( ! $control ) continue;

      const conditions = controls[ name ].tangible_conditions ?? []
      
      const isVisible = visibility.evaluateConditions(
        conditions, 
        getValue
      )
 
      $control[ isVisible ? 'show' : 'hide' ]()
    }

  })

}

const refresh = () => repeater.callback ? repeater.callback() : false

const observer = new MutationObserver(
  mutations => mutations.forEach( refresh ) 
)

const init = () => {

  const repeaters = document.querySelectorAll('.elementor-repeater-fields-wrapper')

  repeaters.forEach(
    repeater => observer.observe(repeater, { childList: true }) 
  )

  $('.elementor-repeater-fields elementor-control input').on('keyup change', refresh)
  $('.elementor-repeater-fields elementor-control select').on('select2:select', refresh)
} 

const clear = () => {

  observer.disconnect()

  $('.elementor-repeater-fields elementor-control input').off('keyup change', refresh)
  $('.elementor-repeater-fields elementor-control select').off('select2:select', refresh)
}

const repeater = {
  setVisibility : setVisibility,
  init          : init,
  clear         : clear,
  callback      : false,
}

export default repeater
