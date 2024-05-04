<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Switch extends piotnetforms_Base_Control {

	public function get_type() {
		return 'switch';
	}

	public function get_control_template() {
		?>
		<%
		if (!data.return_value) {
			data.return_value = 'true';
		}
		%>
		<label class="piotnet-switch">
            <input type="checkbox" class="piotnet-switch__inner" name="<%= data.name %>" value="<%- data.return_value %>" <%= data.value ? "checked" : "" %> <%= data_type_html(data) %>>
			<span class="piotnet-slider piotnet-round"></span>
		</label>
		<?php
	}
}
