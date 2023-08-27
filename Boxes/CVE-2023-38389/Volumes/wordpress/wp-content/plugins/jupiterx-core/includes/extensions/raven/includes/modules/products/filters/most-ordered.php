<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Most_Ordered extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Most Ordered Products', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'most_ordered';
	}

	public static function get_order() {
		return 30;
	}

	public static function get_filter_args() {
		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		return [
			'meta_key' => 'total_sales',
			'orderby' => 'meta_value_num',
		];
		// phpcs:enable
	}
}
