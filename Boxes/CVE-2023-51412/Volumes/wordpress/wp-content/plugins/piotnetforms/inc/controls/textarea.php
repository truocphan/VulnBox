<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_TextArea extends piotnetforms_Base_Control {

	public function get_type() {
		return 'textarea';
	}

	public function get_control_template() {
		?>
		<textarea type="textarea" name="<%= data.name %>" rows="3" cols="3" placeholder="<%- data.placeholder %>" value="<%- data.value %>" <%= data_type_html(data) %>><%- data.value %></textarea>
		<?php
	}
}
