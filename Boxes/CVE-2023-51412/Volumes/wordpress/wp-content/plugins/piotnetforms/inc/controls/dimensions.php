<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Dimensions extends piotnetforms_Base_Control {

	public function get_type() {
		return 'dimensions';
	}

	public function get_control_template() {
		?>
		<div class="piotnet-control-dimensions" data-piotnet-control-dimensions data-piotnet-control-dimensions-name="<%= data.name %>" data-piotnetforms-settings-not-field <%= data_type_html(data) %>>
			<div class="piotnet-control-size-units">
				<% data['size_units'].forEach(function (size_unit) { %>
				<span class="<%= data['value']['unit'] == size_unit ? 'active' : '' %>" data-piotnet-control-size-unit="<%= size_unit %>"><%= size_unit %></span>
				<% }) %>
			</div>
			<input type="hidden" data-piotnet-control-unit data-piotnet-control-dimensions="unit" value="<%= data['value']['unit'] %>">
			<ul class="piotnet-control-dimensions-list">
				<li>
					<input type="number" data-piotnet-control-dimensions-group data-piotnet-control-dimensions="top" data-piotnetforms-settings-field value="<%= data['value']['top'] %>">
					<span class="piotnet-control-dimensions__label">TOP</span>
				</li>
				<li>
					<input type="number" data-piotnet-control-dimensions-group data-piotnet-control-dimensions="right" data-piotnetforms-settings-field value="<%= data['value']['right'] %>">
					<span class="piotnet-control-dimensions__label">RIGHT</span>
				</li>
				<li>
					<input type="number" data-piotnet-control-dimensions-group data-piotnet-control-dimensions="bottom" data-piotnetforms-settings-field value="<%= data['value']['bottom'] %>">
					<span class="piotnet-control-dimensions__label">BOTTOM</span>
				</li>
				<li>
					<input type="number" data-piotnet-control-dimensions-group data-piotnet-control-dimensions="left" data-piotnetforms-settings-field value="<%= data['value']['left'] %>">
					<span class="piotnet-control-dimensions__label">LEFT</span>
				</li>
				<li class="piotnet-control-dimensions-islinked">
					<input type="checkbox" class="fas fa-link" data-piotnet-control-dimensions="isLinked" value="1" checked="">
				</li>
			</ul>
		</div>
		<?php
	}
}
