<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Slider extends piotnetforms_Base_Control {

	public function get_type() {
		return 'slider';
	}

	public function get_control_template() {
		?>
        <% var size_units = data['size_units']; %>
		<div class="piotnet-control-slider" data-piotnet-control-slider data-piotnet-control-slider-name="<%= data.name %>" data-piotnetforms-settings-not-field <%= data_type_html(data) %>>
			<div class="piotnet-control-size-units">
				<% for ( var size_unit in size_units ) { %>
				<span class="<%= data['value']['unit'] == size_unit ? 'active' : '' %>" data-piotnet-control-size-unit="<%= size_unit %>"><%= size_unit %></span>
				<% } %>
			</div>
			<input type="hidden" data-piotnet-control-unit data-piotnet-control-slider="unit" value="<%= data['value']['unit'] %>" <%= data_type_html(data) %>>
			<% for ( var key in size_units ) { var size_unit = size_units[key]; %>
			<div class="piotnet-control-slider-wrapper<%= data['value']['unit'] == key ? ' active' : '' %>" data-piotnet-control-slider-wrapper data-piotnet-control-slider-unit="<%= key %>">
				<div class="piotnet-range-slider2" data-piotnet-control-slider-name="<%= data.name %>" <%= data_type_html(data) %>>
					<div class="piotnet-range-slider2_range">
						<input type="range" min="<%= size_unit['min'] %>" max="<%= size_unit['max'] %>" step="<%= size_unit['step'] %>" class="slider piotnet-range-slider2_range-input" value="<%= data['value']['size'] %>">
					</div>
					<div class="piotnet-range-slider2__input">
						<input type="number" min="<%= size_unit['min'] %>" max="<%= size_unit['max'] %>" step="<%= size_unit['step'] %>" class="piotnet-range-slider2__input-value" data-piotnet-control-slider="size" value="<%= data['value']['size'] %>" <%= data_type_html(data) %>>
					</div>
				</div>
			</div>
			<% } %>
			</div>
		</div>
		<?php
	}
}
