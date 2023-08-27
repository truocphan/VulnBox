<?php
namespace JupiterX_Core\Raven\Modules\Products\Classes;

defined( 'ABSPATH' ) || die();

class Shortcode_Products extends \WC_Shortcode_Products {

	public function get_content() {
		$results = $this->get_query_results();

		return [
			'data' => parent::get_content(),
			'query_results' => $results,
		];
	}

}
