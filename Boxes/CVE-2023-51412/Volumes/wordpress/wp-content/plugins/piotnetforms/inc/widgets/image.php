<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Image extends Base_Widget_Piotnetforms {

	public $is_pro = true;

	public function get_type() {
		return 'image';
	}

	public function get_class_name() {
		return 'piotnetforms_Image';
	}

	public function get_title() {
		return 'Image';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-image.svg',
		];
	}

	public function get_categories() {
		return [ 'pafe-form-builder' ];
	}

	public function get_keywords() {
		return [ 'image' ];
	}

	public function register_controls() {
	}

	public function render() {
		?>
			<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this widget, Go Pro Now</a></p>
		<?php
	}
	
	public function live_preview() {
	}

}
