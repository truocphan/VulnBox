<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Division_Output extends piotnetforms_Base_Control {

	public function get_type() {
		return 'division-output';
	}

	public function get_control_template() {
	}

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-template">
			<!--
			<%
			const division_type = data.division_type;
			%>
			<div <%= view.render_attributes('widget_wrapper_editor') %>>
				<div class="<%= division_type %>__controls">
					<div class="<%= division_type %>__controls-item <%= division_type %>__controls-item--edit" title="Edit" data-piotnet-control-edit>
						<i class="fas fa-th"></i>
					</div>
					<div class="<%= division_type %>__controls-item <%= division_type %>__controls-item--duplicate" title="Duplicate" data-piotnet-control-duplicate>
						<i class="far fa-clone"></i>
					</div>
					<div class="<%= division_type %>__controls-item <%= division_type %>__controls-item--remove" title="Delete" data-piotnet-control-remove>
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div <%= view.render_attributes('widget_wrapper_container') %>></div>
			</div>
			-->
		</div>
		<?php
	}
}