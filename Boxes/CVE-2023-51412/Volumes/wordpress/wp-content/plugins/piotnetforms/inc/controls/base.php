<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

abstract class piotnetforms_Base_Control {
	abstract public function get_type();

	abstract public function get_control_template();

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-control-template">
			<!--
			<%
				let field_group_class = "piotnet-control__field-group";
				if (data.separator) {
					field_group_class += " piotnet-control__field-group--separator-" + data.separator;
				}

				const field_group_attributes = ["data-piotnet-control"];
				if (data.responsive) {
					field_group_attributes.push('data-piotnet-responsive="' + data.responsive + '"');
				}

				if (data.label_block) {
					field_group_attributes.push("data-piotnet-control-label-block");
				}

				if ( data.conditions ) {
					field_group_attributes.push("data-piotnet-control-conditions='" + JSON.stringify(data.conditions) + "'");
				}

			%>
			<div class="<%= field_group_class %>"<% _.each(field_group_attributes, function(field_group_attribute) { %><%= " " + field_group_attribute %><% }); %>>
				<%= data.label ? '<label class="piotnet-control__label">' + data.label + '</label>' : "" %>
				<div class='piotnet-control__field'<%= data.field_width ? ' style="width:' + data.field_width + '!important"' : "" %>>
					<% if (data.responsive) { %>
					<div class="piotnet-control__responsive">
						<span class="piotnet-control__responsive-item active" data-piotnet-control-responsive="desktop">Desktop</span>
						<span class="piotnet-control__responsive-item" data-piotnet-control-responsive="tablet">Tablet</span>
						<span class="piotnet-control__responsive-item" data-piotnet-control-responsive="mobile">Mobile</span>
					</div><% } %>
					<?php $this->get_control_template(); ?>
				</div>
				<% if (data.description) { %>
				<div class="piotnet-control__description"><%= data.description %></div>
				<% } %>
			</div>
			-->
		</div>
		<?php
	}
}

