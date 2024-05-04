<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Html extends piotnetforms_Base_Control {

	public function get_type() {
		return 'html';
	}

	public function get_control_template() {
		?>
		<%= data.raw %>
		<?php
	}
}
