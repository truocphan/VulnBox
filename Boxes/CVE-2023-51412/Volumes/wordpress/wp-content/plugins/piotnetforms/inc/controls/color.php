<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Color extends piotnetforms_Base_Control {

	public function get_type() {
		return 'color';
	}

	public function get_control_template() {
		?>
		<input type="text" class="piotnet-pick-color" data-format="rgb" data-opacity="1" value="<%- data.value%>" name="<%= data.name %>" <%= data_type_html(data) %>>
		<?php
	}
}
