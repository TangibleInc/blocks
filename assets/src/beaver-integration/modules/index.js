import { initModule } from './module'

const { blockConfig } = window.Tangible
const { fields } = blockConfig.visibility

jQuery(() => { for( const blockId in fields ) initModule(blockId) })
