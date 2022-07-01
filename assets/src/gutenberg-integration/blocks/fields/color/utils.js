/**
 * Used to convert default value to rgba if needed
 * 
 * @see https://stackoverflow.com/a/53936623/10491705
 */

export const isHex = hex => (
  /^#([A-Fa-f0-9]{3,4}){1,2}$/.test(hex) || /^([A-Fa-f0-9]{3,4}){1,2}$/.test(hex)
)

export const hexToRGBA = (hex, alpha) => {

  const chunkSize = Math.floor((hex.length - 1) / 3)
  const hexArr = getChunksFromString(hex.slice(1), chunkSize)
  const [r, g, b, a] = hexArr.map(convertHexUnitTo256)

  return `rgba(${r}, ${g}, ${b}, ${getAlphafloat(a)})`
}

const getChunksFromString = (st, chunkSize) => st.match(new RegExp(`.{${chunkSize}}`, "g"))

const convertHexUnitTo256 = hexStr => parseInt(hexStr.repeat(2 / hexStr.length), 16)

const getAlphafloat = alpha => (typeof alpha !== 'undefined' ? parseFloat(alpha / 255).toFixed(2) : 1)

