<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Output extends piotnetforms_Base_Control {

	public function get_type() {
		return 'output';
	}

	public function get_control_template() {
	}

	public function get_template() {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $this->get_type() ); ?>-template">
			<!--
			<%
			view.add_attribute('widget_wrapper_editor', 'class', 'piotnet-widget');
			view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-widgets-item', JSON.stringify( data.widget_info ));
			view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-widgets-item-id', data.widget_id);
			view.add_attribute('widget_wrapper_editor', 'draggable', 'true');
			%>
			<div <%= view.render_attributes('widget_wrapper_editor') %>>
				<div class="piotnet-widget__controls">
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--edit" title="Edit" data-piotnet-control-edit>
						<i class="fas fa-th"></i>
					</div>
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--duplicate" title="Duplicate" data-piotnet-control-duplicate>
						<i class="far fa-clone"></i>
					</div>
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--remove" title="Delete" data-piotnet-control-remove>
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="piotnet-widget__container"></div>
			</div>
			-->
		</div>
		<?php
	}
}
