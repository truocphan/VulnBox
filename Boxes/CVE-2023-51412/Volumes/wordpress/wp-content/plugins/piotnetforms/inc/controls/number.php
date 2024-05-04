<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Number extends piotnetforms_Base_Control {

	public function get_type() {
		return 'number';
	}

	public function get_control_template() {
		?>
		<input type="number" name="<%= data.name %>" value="<%- data.value %>" min="<%= data.min %>" max="<%= data.max %>" placeholder="<%- data.placeholder %>" <%= data_type_html(data) %>>
		<?php
	}
}
