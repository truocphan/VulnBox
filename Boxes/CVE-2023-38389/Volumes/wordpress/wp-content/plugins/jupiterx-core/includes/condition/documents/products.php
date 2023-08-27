<?php

namespace JupiterX_Core\Condition\Documents;

use Elementor\Modules\Library\Documents\Library_Document as Elementor_Library_Document;
use Elementor\Modules\Library\Documents\Page;

class Products extends Page {
	public function get_name() {
		return 'product';
	}

	public static function get_type() {
		return 'page';
	}

	public static function get_title() {
		return esc_html__( 'Products', 'jupiterx-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'Products', 'jupiterx-core' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		return $properties;
	}
}
