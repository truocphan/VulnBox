<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Icon_List extends Base_Widget_Piotnetforms {

	public $is_pro = true;

	public function get_type() {
		return 'icon-list';
	}

	public function get_class_name() {
		return 'piotnetforms_Icon_List';
	}

	public function get_title() {
		return 'Icon List';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-preview-submissions.svg',
		];
	}

	public function get_categories() {
		return [ 'pafe-form-builder' ];
	}

	public function get_keywords() {
		return [ 'text' ];
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
