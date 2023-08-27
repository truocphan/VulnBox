<?php

namespace JupiterX_Core\Condition\Documents;

use JupiterX_Core\Raven\Core\Library\Documents\Archive;

class Product_Archive extends Archive {
	public function get_name() {
		return 'product-archive';
	}

	public static function get_type() {
		return 'archive';
	}

	public static function get_title() {
		return esc_html__( 'Product Archive', 'jupiterx-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'Product Archive', 'jupiterx-core' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		return $properties;
	}
}
