<?php

$countries = include XOO_AFF_DIR.'/countries/countries.php';

?>
<# if ( "section" === data.type ) { #>
<div class="xoo-aff-field-section xoo-aff-field-section-{{data.id}}">
	<label class="xoo-aff-section-label">{{data.title}}</label>
	<div>
<# }else{ #>	
	
<div class="xoo-aff-setting-{{data.type}} xoo-aff-setting-{{data.id}} xoo-aff-fs-{{data.width}} xoo-aff-fs-input {{{ ( data.required && data.required === 'yes' ) ? 'xoo-aff-required' : '' }}}" data-id="{{data.id}}">
	<label for="xoo_aff_{{data.id}}">{{data.title}}
		<# if ( data.info ) { #>
		<div class="xoo-aff-info">
			<span class="xoo-aff-info-icon fas fa-info-circle"></span>
			<span class="xoo-aff-infotext">{{data.info}}</span>
		</div>
		<# } #>
	</label>

	<# if ( "text" === data.type ) { #>
		<input type="text" id="xoo_aff_{{data.id}}" name="xoo_aff_{{data.id}}" placeholder="{{data.placeholder}}" value="{{data.value}}" class="{{data.class}}" {{data.disabled}}>
	<# } #>

	<# if ( "checkbox" === data.type ) { #>
		<label for="xoo_aff_{{data.id}}" class="xoo-aff-switch">
		  <input type="checkbox" id="xoo_aff_{{data.id}}" name="xoo_aff_{{data.id}}" value="yes" {{data.disabled}} {{{ ( data.value === 'yes' ) ? 'checked' : '' }}} >
		  <span class="xoo-aff-slider"></span>
		</label>
	<# } #>

	<# if ( "number" === data.type ) { #>
		<input type="number" id="xoo_aff_{{data.id}}" name="xoo_aff_{{data.id}}" min="{{data.minlength}}" placeholder="{{data.placeholder}}" class="{{data.class}}" value="{{data.value}}">
	<# } #>


	<# if ( "iconpicker" === data.type ) { #>
		<input type="text" class="xoo-aff-iconpicker" name="xoo_aff_{{data.id}}" id="xoo_aff_{{data.id}}" placeholder="{{data.placeholder}}" class="{{data.class}}" value="{{data.value}}" autocomplete="off">
	<# } #>

	<# if ( "date" === data.type ) { #>
		<input type="text" class="xoo-aff-datepicker" name="xoo_aff_{{data.id}}" id="xoo_aff_{{data.id}}" placeholder="{{data.placeholder}}" class="{{data.class}}" value="{{data.value}}" autocomplete="off">
	<# } #>

	<# if ( "select" === data.type ) { #>
		<select id="xoo_aff_{{data.id}}" class="{{data.class}}" name="xoo_aff_{{data.id}}">
			<# _.each( data.options , function(option_title, option_value) { #>
				<option value="{{option_value}}" {{{ ( data.value == option_value ) ? 'selected="selected"' : '' }}} >{{option_title}}</option>
			<# }) #>
		</select>
	<# } #>

	<# if ( "checkbox_list" === data.type || "radio" === data.type || "select_list" === data.type || "checkbox_single" === data.type ) { #>
		<div class="xoo-aff-multiple-options" id="xoo_aff_{{data.id}}">
			<button class="xoo-add-option"><span class="fas fa-plus-circle"></span></button>
			<ul class="xoo-aff-options-list">
				<# _.each( data.value , function(option, index) { #>
					<li class="xoo-aff-opt">
						<span class="fas fa-bars"></span>

						<# if ( "checkbox_list" === data.type || "checkbox_single" === data.type ) { #>
							<input type="checkbox" {{option.checked}} class="option-check">
						<# } #>

						<# if ( "radio" === data.type || "select_list" === data.type ) { #>
							<input type="radio" name="xoo_aff_radio_single" {{option.checked}} class="option-check">
						<# } #>

						<input type="text" value="{{option.label}}" class="mcb-label" placeholder="Label">
						<input type="text" value="{{option.value}}" class="mcb-value" placeholder="Value">
						<span class="mcb-del fas fa-minus-circle"></span>
					</li>
				<# }) #>
			</ul>
			<input type="hidden" class="xoo-aff-trigger-change" id="xoo_aff_{{data.id}}" name="xoo_aff_{{data.id}}">
		</div>
	<# } #>


	<# if ( "select_multiple" === data.type) { #>
		
		<div class="xoo-aff-select-multiple-container">
			<select multiple id="xoo_aff_{{data.id}}" class="xoo-aff-select-multiple" style="display: none;">
				<# _.each( data.options , function(option_title, option_value) { #>
					<option value="{{option_value}}" class="{{ data.value[option_value] && (data.value[option_value]).checked === 'checked' ? 'aff-default' : '' }}" {{ data.value[option_value] ? 'selected=selected' : '' }}>{{option_title}}</option>
				<# }) #>
			</select>

			<div class="xoo-aff-select-multiple-textarea">
				<ul>
					<# _.each( data.value , function(option_data, option_value) { #>
					<li data-value={{option_value}} class="{{option_data.checked === 'checked' ? 'aff-default' : ''}}"><span class="xoo-aff-sel-remove dashicons dashicons-no-alt"></span>{{option_data.label}}</li>
					<# }) #>
				</ul>
			</div>

			<ul class="xoo-aff-multiple-list">
				<# _.each( data.options , function(option_title, option_value) { #>
					<li data-value="{{option_value}}">{{option_title}}</li>
				<# }) #>
			</ul>
			<input type="hidden" class="xoo-aff-trigger-change" id="xoo_aff_{{data.id}}" name="xoo_aff_{{data.id}}">
		</div>
	<# } #>
</div>
<# } #>	

<# if ( "section" === data.type ) { #>
	</div>
</div>
<# } #>	