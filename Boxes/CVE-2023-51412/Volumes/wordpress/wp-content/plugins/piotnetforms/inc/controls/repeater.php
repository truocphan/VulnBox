<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Repeater extends piotnetforms_Base_Control {

	public function get_type() {
		return 'repeater';
	}

	public function get_control_template() {
		?>
		<div class="piotnet-control-repeater" data-piotnet-control-repeater>
			<div class="piotnet-control-repeater-list" data-piotnet-control-repeater-list="<%= data.name %>"></div>
			<div class="piotnet-control-repeater-add-item" data-piotnet-control-repeater-add-item><%= data.add_label %></div>
		</div>
		<?php
	}
}
