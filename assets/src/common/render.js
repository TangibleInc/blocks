import {
  render,
  createRoot, 
  unmountComponentAtNode
} from 'react-dom'

/**
 * Make sure to use the right render according to each react version is used
 * 
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/#usage
 * @see https://react.dev/blog/2022/03/08/react-18-upgrade-guide
 */
const renderReact = (component, element) => {

  let root = false

  // React 18 and above
  if( createRoot ) {
    root = createRoot(element)
    root.render(component)
  }
  else {
    render(component, element)
  }

  return root
}

const unmountReact = (root, element) => {
  createRoot 
    ? root.unmount()
    : unmountComponentAtNode(element)
}

export {
  renderReact,
  unmountReact
}
