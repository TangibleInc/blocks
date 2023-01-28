const { wp } = window
const {
  element: { useEffect, useRef, useState }
} = wp

const TextArea = props => {

  const editorElement = useRef(null)
  const [value, setValue] = useState(props.value)

  useEffect(() => {
    tinyMCE.init({
      target: editorElement.current,
      // Customize editor options: https://www.tiny.cloud/docs/general-configuration-guide/basic-setup/
      setup: function(editor){
        editor.on('input', function(e){
          props.updateVal(editor.getContent())
        })
        editor.on('ExecCommand', function(e){
          props.updateVal(editor.getContent())
        })
      }
    })
  }, [])


  return(
    <textarea ref={ editorElement }>{ value }</textarea>
  )
}

export default TextArea
