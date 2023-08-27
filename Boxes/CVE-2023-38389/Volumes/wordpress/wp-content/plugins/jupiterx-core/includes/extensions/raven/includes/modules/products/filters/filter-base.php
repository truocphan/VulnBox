<?php
/**
 * Add Filter Base.
 *
 * @package JupiterX_Core\Raven
 * @since 2.0.0
 */

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;
use JupiterX_Core\Raven\Modules\Products\Classes\Shortcode_Products;

defined( 'ABSPATH' ) || die();

/**
 * Filter Base class.
 *
 * @since 2.0.0
 * @abstract
 */
abstract class Filter_Base {

	/**
	 * The Products widget instance.
	 *
	 * @since 2.0.0
	 * @var object
	 */
	public static $widget;

	/**
	 * The Products widget settings.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public static $settings;

	/**
	 * Get filter title.
	 *
	 * @since 2.0.0
	 * @return string Filter title.
	 */
	public static function get_title() {
		return '';
	}

	/**
	 * Get filter name.
	 *
	 * @since 2.0.0
	 * @return string Filter name.
	 */
	public static function get_name() {
		return '';
	}

	/**
	 * Get filter order.
	 *
	 * @since 2.0.0
	 * @return string Filter order.
	 */
	public static function get_order() {
		return '';
	}

	/**
	 * Get filter specific attributes.
	 *
	 * @since 2.0.0
	 * @return array Filter specific attribues.
	 */
	public static function force_no_result() {
		return [ 'post__in' => [ 0 ] ];
	}

	/**
	 * Get common attributes. order, orderby, paginate, ... based on widget types.
	 * Widget types are raven-products-carousel and raven-products.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public static function get_attributes() {
		$widget = self::$widget;

		if ( 'raven-products-carousel' === $widget->get_data( 'widgetType' ) ) {
			return self::get_carousel_attributes();
		}

		return self::get_grid_attributes();
	}

	/**
	 * Get common attributes. order, orderby, paginate, ... for raven-products.
	 *
	 * @since 2.0.0
	 * @return array Common attributes.
	 */
	public static function get_grid_attributes() {
		$attributes = [];
		$settings   = self::$settings;

		$sort = self::get_products_sort_form();

		$attributes['orderby'] = $settings['query_orderby'];
		$attributes['order']   = ! empty( $sort ) ? $sort : $settings['query_order'];
		$attributes['columns'] = $settings['columns'] ?? null;
		$attributes['rows']    = $settings['rows'] ?? null;

		if ( '_wc_average_rating' === $attributes['orderby'] ) {
			$attributes['orderby'] = 'rating';
		}

		if ( isset( $settings['layout'] ) && 'custom' === $settings['layout'] ) {
			$attributes['columns'] = ! empty( $settings['columns_custom'] ) ? $settings['columns_custom'] : 3;

			if ( in_array( $settings['general_layout'], [ 'matrix', 'metro' ], true ) ) {
				$attributes['columns'] = empty( $settings['show_all_products'] ) ? $settings['number_of_products'] : -1;
				$attributes['rows']    = 1;
			}
		}

		if ( 'featured' === $settings['query_filter_by'] ) {
			$attributes['visibility'] = 'featured';
		}

		if ( 'yes' === $settings['show_pagination'] ) {
			$attributes['paginate'] = true;
		}

		$attributes = array_merge(
			$attributes,
			static::get_filter_attributes()
		);

		return $attributes;
	}

	/**
	 * Get common attributes. order, orderby, paginate, ... for raven-products-carousel.
	 *
	 * @since 2.0.0
	 * @return array Common attributes.
	 */
	public static function get_carousel_attributes() {
		$attributes = [];
		$settings   = self::$settings;

		$sort = self::get_products_sort_form();

		$attributes['orderby']        = $settings['query_orderby'];
		$attributes['order']          = ! empty( $sort ) ? $sort : $settings['query_order'];
		$attributes['posts_per_page'] = $settings['query_posts_per_page'];
		$attributes['columns']        = $settings['slides_view'];
		$attributes['rows']           = 1;

		if ( '_wc_average_rating' === $attributes['orderby'] ) {
			$attributes['orderby'] = 'rating';
		}

		if ( 'featured' === $settings['query_filter_by'] ) {
			$attributes['visibility'] = 'featured';
		}

		$attributes['paginate'] = false;

		$attributes = array_merge(
			$attributes,
			static::get_filter_attributes()
		);

		return $attributes;
	}

	/**
	 * Get orderby data from url.
	 *
	 * @since 2.0.6
	 * @return string|null
	 */
	public static function get_products_sort_form() {
		if ( empty( get_query_var( 'orderby' ) ) ) {
			return;
		}

		$sort = 'menu_order title' === get_query_var( 'orderby' ) ? '' : get_query_var( 'orderby' );

		return $sort;
	}

	/**
	 * Get filter specific attributes.
	 *
	 * @since 2.0.0
	 * @return array Filter specific attribues.
	 */
	public static function get_filter_attributes() {
		return [];
	}

	/**
	 * Return query arguments based on the widget type.
	 * Widget types are raven-products-carousel and raven-products
	 *
	 * @since 2.0.0
	 * @return array Query args.
	 */
	public static function get_args( $query_args ) {
		$widget   = self::$widget;
		$settings = self::$settings;

		if ( 'sale' === $settings['query_filter_by'] ) {
			$query_args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
		}

		if ( ! empty( $settings['query_offset'] ) ) {
			$query_args['offset'] = $settings['query_offset'];
		}

		if ( ! empty( $settings['query_excludes_ids'] ) ) {
			$query_args['post__not_in'] = $settings['query_excludes_ids'];
		}

		if ( 'raven-products-carousel' === $widget->get_data( 'widgetType' ) ) {
			$query_args['posts_per_page'] = $settings['query_posts_per_page'];
		}

		$query_args = array_merge(
			$query_args,
			static::get_filter_args()
		);

		return $query_args;
	}

	/**
	 * Get filter specific args.
	 *
	 * @since 2.0.0
	 * @return array Filter specific args.
	 */
	public static function get_filter_args() {
		return [];
	}

	/**
	 * Query the products.
	 *
	 * @since 2.0.0
	 * @return object WC_Shortcode_Products Object.
	 */
	public static function query( $widget, $settings ) {
		self::$widget   = $widget;
		self::$settings = $settings;

		/**
		 * Development note.
		 *
		 * Shortcode_Products accepts $attributes, and it supports most of the
		 * query args but not all of them. So in addition to use $attributes, we need to
		 * filter $query_vars to handle necessary queries.
		 */

		add_filter( 'woocommerce_shortcode_products_query', [ static::class, 'get_args' ] );

		$products = new Shortcode_Products( static::get_attributes() );

		remove_filter( 'woocommerce_shortcode_products_query', [ static::class, 'get_args' ] );

		return $products;
	}

}
