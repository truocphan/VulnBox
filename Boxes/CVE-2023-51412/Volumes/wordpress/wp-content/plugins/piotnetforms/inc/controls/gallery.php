<?php

namespace Controls_Piotnetforms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Control_Gallery extends piotnetforms_Base_Control {

	public function get_type() {
		return 'gallery';
	}

	public function get_control_template() {
		?>
		<div data-piotnet-control-gallery-wrapper data-piotnet-control-name="<%= data.name %>" data-piotnetforms-settings-not-field <%= data_type_html(data) %>>
			<a class="gallery-add button" href="#" data-piotnet-control-gallery-upload data-uploader-title="Add image(s) to gallery" data-uploader-button-text="Add image(s)">Add image(s)</a>
			<div data-piotnet-control-gallery-list>
		<%
			var gallery = Array.isArray(data.value) ? data.value : [];

			if (gallery.length > 0) {
				for ( var key in gallery ) {
					var image_id = gallery[key]['id'];
					var image_url = gallery[key]['url'];
		%>
					<div data-piotnet-control-gallery-item>
						<input type="hidden" data-piotnet-control-gallery="id" data-piotnetforms-settings-field value="<%= image_id %>" />
						<input type="hidden" data-piotnet-control-gallery="url" data-piotnetforms-settings-field value="<%= image_url %>" />
						<img data-piotnet-control-gallery="preview" src="<%= image_url %>">
						<a data-piotnet-control-gallery-change-image class="change-image button button-small" href="#"  data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a>
						<small><a data-piotnet-control-gallery-remove class="remove-image" href="#">Remove image</a></small>
					</div>
		<%
				}
			}
		%>
			</div>
		</div>
		<?php
	}
}
