<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Content_Tab extends piotnetforms_Base_Control {

	public function get_type() {
		return 'content-tab';
	}

	public function get_control_template() {
	}

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-control-template">
			<!--
			<%
			const field_group_attributes = [];
			if ( data.conditions ) {
			field_group_attributes.push("data-piotnet-control-conditions='" + JSON.stringify(data.conditions) + "'");
			}
			%>
			<div class="piotnet-controls-tab-content <%= data.active ? 'active' : '' %>" data-piotnet-tab-content="<%= data.name %>" <%= data_type_html(data) %> <% _.each(field_group_attributes, function(field_group_attribute) { %><%= " " + field_group_attribute %><% }); %>>
				<div class="piotnet-start-controls-tab"></div>
			</div>
			-->
		</div>
		<?php
	}
}
