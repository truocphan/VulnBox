<li class="xoo-aff-fs-display xoo-aff-fsd-{{data.type_data.type}} {{{ ( data.type_data.can_delete !== 'yes'  ) ? 'xoo-aff-locked-field' : '' }}} {{{ ( data.type_data.is_sortable !== 'yes'  ) ? 'xoo-aff-no-sort' : '' }}}" id="{{data.field_id}}" data-type="{{data.type_data.type}}">
	<div class="xoo-aff-label xoo-aff-label-{{data.type_data.type}}">
		<span class="xoo-aff-type-icon {{data.type_data.icon}}"></span>
		<span>{{data.type_data.title}}</span>
		<span></span>
	</div>
	<span class="fas fa-edit xoo-aff-fsd-edit"></span>
	<div class="xoo-aff-fsd-cta">
		<# if ( data.type_data.can_delete === "yes" ) { #>
			<span class="xoo-aff-fsd-cta-del fas fa-trash"></span>	
		<# } else { #>
			<span class="fas fa-lock"></span>
        <# } #>
		
	</div>
</li>