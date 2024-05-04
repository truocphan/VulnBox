<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Box_Shadow extends piotnetforms_Base_Control {

	public function get_type() {
		return 'box-shadow';
	}

	public function get_control_template() {
		?>
		<div class="piotnet-tooltip" data-piotnet-tooltip><label class="piotnet-tooltip__label" data-piotnet-tooltip-label><i class="fas fa-pencil-alt" aria-hidden="true"></i></label><div class="piotnet-tooltip__body">
			<%
				var horizontal = data['value']['horizontal'] ? data['value']['horizontal'] : '';
				var vertical   = data['value']['vertical'] ? data['value']['vertical'] : '';
				var blur       = data['value']['blur'] ? data['value']['blur'] : '';
				var spread     = data['value']['spread'] ? data['value']['spread'] : '';
				var color      = data['value']['color'] ? data['value']['color'] : 'rgba(0, 0, 0, 0.75)';
			%>

			<div class="piotnet-control__field-group--10" data-piotnet-control-boxshadow data-piotnetforms-settings-not-field data-piotnet-control-boxshadow-name="<%= data.name %>" <%= data_type_html(data) %>>
			<div class="piotnet-control__field-group">
			<lable class="piotnet-control__label">Horizontal</lable>
			<div class="piotnet-control__field">
			<input type="number" data-piotnet-control-boxshadow-settings="horizontal" class="piotnet-boxshadow-horizontal" data-piotnetforms-settings-field name="horizontal" min="-100" max="100" value="<%- horizontal %>">
			</div></div>
			<div class="piotnet-control__field-group">
			<lable class="piotnet-control__label">Vertical</lable>
			<div class="piotnet-control__field">
			<input type="number" data-piotnet-control-boxshadow-settings="vertical" class="piotnet-boxshadow-vertical" data-piotnetforms-settings-field name="vertical" min="-100" max="100" value="<%- vertical %>">
			</div></div>
			<div class="piotnet-control__field-group">
			<lable class="piotnet-control__label">Blur</lable>
			<div class="piotnet-control__field">
			<input type="number" data-piotnet-control-boxshadow-settings="blur" class="piotnet-boxshadow-blur" data-piotnetforms-settings-field name="blur" min="0" max="100" value="<%- blur %>">
			</div></div>
			<div class="piotnet-control__field-group">
			<lable class="piotnet-control__label">Spread</lable>
			<div class="piotnet-control__field">
			<input type="number" data-piotnet-control-boxshadow-settings="spread" class="piotnet-boxshadow-spread" data-piotnetforms-settings-field name="spread" min="-100" max="100" value="<%- spread %>">
			</div></div>
			<div class="piotnet-control__field-group">
			<lable class="piotnet-control__label">Color</lable>
			<div class="piotnet-control__field">
			<input type="text" data-piotnet-control-boxshadow-settings="color" class="piotnet-pick-color" data-piotnetforms-settings-field data-format="rgb" data-opacity="1" value="<%- color %>">
			</div></div></div>
		</div>
	</div>
		<?php
	}
}
