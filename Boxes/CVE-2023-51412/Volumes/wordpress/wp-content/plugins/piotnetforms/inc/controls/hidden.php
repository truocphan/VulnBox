<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Hidden extends piotnetforms_Base_Control {

	public function get_type() {
		return 'hidden';
	}

	public function get_control_template() {
		?>
		<input type="hidden" name="<%= data.name %>" value="<%- data.value %>" <%= data_type_html(data) %>>
		<?php
	}
}
