<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Typography extends piotnetforms_Base_Control {

	public function get_type() {
		return 'typography';
	}

	public function get_control_template() {
		?>
		<div class="piotnet-tooltip" data-piotnet-tooltip data-piotnet-control-typography-wrapper data-piotnet-control-name="<%= data.name %>" data-piotnetforms-settings-not-field  <%= data_type_html(data) %>>
			<label class="piotnet-tooltip__label" data-piotnet-tooltip-label>
				<i class="<%= data.icon %>" aria-hidden="true"></i>
			</label>
			<div class="piotnet-tooltip__body"></div>
		</div
		<?php
	}
}
