<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Current_Archive_Query extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Current Archive Query', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'current_archive_query';
	}

	public static function get_order() {
		return 5;
	}

	public static function get_filter_args() {
		//If it is editor or preview, it will return default query since we don't have queried object on these pages.
		if ( Module::is_editor_or_preview() ) {
			return [];
		}

		$settings = self::$settings;

		if ( isset( $settings['archive_query'] ) ) {
			return [
				'taxonomy' => $settings['archive_query']->taxonomy,
				'term'     => $settings['archive_query']->term,
			];
		}

		return self::get_filter_args_from_wp_query();
	}

	private static function get_filter_args_from_wp_query() {
		global $wp_query;
		$archive_query = $wp_query->get_queried_object();

		return [
			'taxonomy' => $archive_query->taxonomy,
			'term'     => $archive_query->slug,
		];
	}
}
