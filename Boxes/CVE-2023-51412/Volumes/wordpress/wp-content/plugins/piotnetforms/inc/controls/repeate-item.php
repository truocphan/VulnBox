<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Repeater_Item extends piotnetforms_Base_Control {

	public function get_type() {
		return 'repeater-item';
	}

	public function get_control_template() {
	}

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-control-template">
			<!--
			<div class="piotnet-control-repeater-item" data-piotnet-control-repeater-item>
				<div class="piotnet-control-repeater-heading" data-piotnet-repeater-heading>
					<span class="piotnet-control-repeater-heading-text">Item</span>
					<span class="piotnet-control-repeater-remove-item" data-piotnet-control-repeater-remove-item><i class="fas fa-times" aria-hidden="true"></i></span>
				</div>
				<div class="piotnet-control-repeater-field" data-piotnet-repeater-field></div>
			</div>
			-->
		</div>
		<?php
	}
}
