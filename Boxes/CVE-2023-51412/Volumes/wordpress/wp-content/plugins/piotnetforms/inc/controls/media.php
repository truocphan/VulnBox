<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Media extends piotnetforms_Base_Control {

	public function get_type() {
		return 'media';
	}

	public function get_control_template() {
		?>
		<%
			var display = 'none';
			var image = ' button">Upload image';
			var id = data.value && data.value.id ? data.value.id : '';
			var url = data.value && data.value.url ? data.value.url : '';
			if (data.value && data.value.id) {
				display = 'inline-block';
				image = '"><img src="' + url + '" style="display:block;" />';
			}
		%>
		<div data-piotnet-control-media-wrapper data-piotnet-control-name="<%= data.name %>" data-piotnetforms-settings-not-field <%= data_type_html(data) %>>
			<a href="#" data-piotnet-control-media-upload class="piotnet-control-media-upload<%= image %></a>
			<input type="hidden" data-piotnet-control-media="id" data-piotnetforms-settings-field value="<%= id %>" />
			<input type="hidden" data-piotnet-control-media="url" data-piotnetforms-settings-field value="<%= url %>" />
			<a href="#" data-piotnet-control-media-remove class="piotnet-control-media-remove" style="display:<%= display %>">Remove image</a>
		</div>
		<?php
	}
}
