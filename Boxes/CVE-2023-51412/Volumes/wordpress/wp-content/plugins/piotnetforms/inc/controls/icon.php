<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Icon extends piotnetforms_Base_Control {

	public function get_type() {
		return 'icon';
	}

	public function get_control_template() {
		?>
		<div data-piotnet-select-icon>
			<a class="piotnet-icon-library" href="javascript:void(0)">Icon Library</a>
			<input type="text" name="<%= data.name %>" value="<%- data.value %>" <%= data_type_html(data) %>>
		</div>
		<div class="piotnet-modal" data-piotnet-modal>
			<div class="piotnet-modal-content" data-piotnet-modal-content>
				<div class="piotnet-con-search-bar">
					<input type="text" placeholder="Search for names.." title="Type in a name" data-piotnet-search-icon>
				</div>
				<div class="piotnet-icon-items">
					<div class="piotnet-icon-items-content">
					<% for ( var key in data.options ) { %>
						<div data-piotnet-control-icon="<%- key %>" class="piotnet-icon-item">
							<div class="piotnet-icon-item__inner">
								<i class="<%- key %>"></i>
								<div class="piotnet-icon-value"><%- data.options[key] %></div>
							</div>
						</div>
						<% } %>
					</div>
				</div>
				<div class="piotnet-button-insert-icon">
					<a type="button" class="piotnet-button-insert-icon__button" data-piotnet-modal-close>Select</a>
				</div>
			</div>
		</div>
		<?php
	}
}
