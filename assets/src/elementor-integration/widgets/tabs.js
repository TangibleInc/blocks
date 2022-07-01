const $ = jQuery

/**
 * Workaround to be able to use custom tabs
 *
 * @see maybe_use_tabs_workaround() in ./includes/integrations/elementor/dynamic/base.php
 */

export const maybeInitTabsWorkaround = tabs => {
  
  if( !tabs.content || !tabs.content.conditions ) return
  if( tabs.content.conditions !== 'default-tab-workaround' ) return

  const firstTab = document.querySelector('.elementor-component-tab:not([data-tab="content"])')
  $(firstTab).trigger('click')
}
