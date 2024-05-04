<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Date extends piotnetforms_Base_Control {

	public function get_type() {
		return 'date';
	}

	public function get_control_template() {
		?>
		<input type="text" name="<%= data.name %>" value="<%- data.value %>" class="piotnet-flatpickr" placeholder="<%- data.placeholder %>" <%= data_type_html(data) %>>
		<?php
	}
}
