<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Select2 extends piotnetforms_Base_Control {

	public function get_type() {
		return 'select2';
	}

	public function get_control_template() {
		?>
		<select class='piotnet-select2' name='<%= data.name %>' multiple="multiple" <%= data_type_html(data) %>>
			<% if (Array.isArray(data.value)) { %>
				<% for ( var key in data.options ) { %>
					<option value="<%- key %>" <%= (data.value.includes(key)) ? "selected" : "" %>><%- data.options[key] %></option>
				<% } %>
			<% } else { %>
				<% for ( var key in data.options ) { %>
					<option value='<%= key %>' <%= (key == data.value) ? "selected" : "" %>><%= data.options[key] %></option>
				<% } %>
			<% } %>
		</select>
		<?php
	}
}
