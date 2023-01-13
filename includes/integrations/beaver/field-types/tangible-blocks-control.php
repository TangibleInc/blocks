<?php defined('ABSPATH') or die(); ?>

<div 
  data-field="{{ JSON.stringify(data.field.data) }}" 
  class="tangible-block-control-container <?php echo $type; ?>-container tangible-block-control-beaver-builder"
></div>
<input 
  type='hidden' 
  name="{{ data.name }}" 
  value="{{ typeof data.value === 'object' ? JSON.stringify(data.value) : data.value }}" 
/>
