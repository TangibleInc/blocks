<span>
  <div data-field="{{ JSON.stringify(data.field.field) }}" class="tangible-block-control-container <?php echo $type; ?>-container"></div>
  <input type='hidden' name="{{ data.name }}" value="{{ typeof data.value === 'object' ? JSON.stringify(data.value) : data.value }}" />
</span>
