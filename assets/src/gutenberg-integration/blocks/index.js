import { createBlock } from './create'

const { blocks } = window.Tangible

blocks.map(block => createBlock(block))
