<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Select extends piotnetforms_Base_Control {

	public function get_type() {
		return 'select';
	}

	public function get_control_template() {
		?>
		<select name="<%= data.name %>" <%= data_type_html(data) %>>
			<% for ( var key in data.options ) { %>
			<option value="<%- key %>" <%= (key == data.value) ? "selected" : "" %>><%- data.options[key] %></option>
			<% } %>
		</select>
		<?php
	}
}
