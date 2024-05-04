<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Tab_Widget extends piotnetforms_Base_Control {

	public function get_type() {
		return 'tab-widget';
	}

	public function get_control_template() {
	}

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-template">
			<!--
			<div class="piotnetforms-widget-controls" data-piotnetforms-widget-controls="<%= data['widget_id'] %>">
				<div class="piotnet-tabs" data-piotnet-tabs="">
					<% for ( var key in data['tabs'] ) { var tab = data['tabs'][key]; %>
					<div class="piotnet-tabs__item <%= tab.active ? 'active' : '' %>" data-piotnet-tabs-item="<%= tab['name'] %>"><%= tab['label'] %></div>
					<% } %>
				</div>
				<% for ( var key in data['tabs'] ) {
					var tab = data['tabs'][key];
					var sections = tab['sections'];
				%>
				<div class="piotnet-tabs-content <%= tab.active ? 'active' : '' %>" data-piotnet-tabs-content="<%= tab['name'] %>">
					<%
					for ( var key in sections ) {
						var section = sections[key];
						const field_group_attributes = [];
						if ( section.conditions ) {
						field_group_attributes.push("data-piotnet-control-conditions='" + JSON.stringify(section.conditions) + "'");
						}
					%>
					<div class="piotnet-controls-section <%= section.active ? 'active' : '' %>" data-piotnet-controls-section="<%= section['name'] %>" <% _.each(field_group_attributes, function(field_group_attribute) { %><%= " " + field_group_attribute %><% }); %>>
						<div class="piotnet-controls-section__header" data-piotnet-controls-section-header="">
							<div class="piotnet-controls-section__header-label"><%= section['label'] %></div>
							<div class="piotnet-controls-section__header-icon">
								<i class="fas fa-caret-down"></i>
								<i class="fas fa-caret-up"></i>
							</div>
						</div>
						<div class="piotnet-controls-section__body" data-piotnet-controls-section-body=""></div>
					</div>
					<% } %>
				</div>
				<% } %>
			</div>
			-->
		</div>
		<?php
	}
}
