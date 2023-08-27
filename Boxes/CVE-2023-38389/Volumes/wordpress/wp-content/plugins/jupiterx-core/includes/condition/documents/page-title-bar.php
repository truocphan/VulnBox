<?php

namespace JupiterX_Core\Condition\Documents;

use Elementor\Modules\Library\Documents\Section;

class Page_Title_Bar extends Section {
	public function get_name() {
		return 'page-title-bar';
	}

	public static function get_type() {
		return 'section';
	}

	public static function get_title() {
		return esc_html__( 'Page Title Bar', 'jupiterx-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'Page Title Bars', 'jupiterx-core' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
}
