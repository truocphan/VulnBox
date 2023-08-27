<?php
/**
 * Functions for WooCommerce.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

add_filter( 'woocommerce_template_path', 'jupiterx_wc_modify_template_path' );
/**
 * Override WooCommerce default template path.
 *
 * @param string $path The template path.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_template_path( $path ) {

	if ( is_dir( JUPITERX_TEMPLATES_PATH . '/woocommerce' ) ) {
		$path = 'lib/templates/woocommerce/';
	}

	return $path;
}

add_action( 'jupiterx_init', 'jupiterx_wc_add_theme_support' );
/**
 * Add WooCommerce theme support.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_wc_add_theme_support() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'woocommerce' );
}

add_filter( 'woocommerce_add_to_cart_fragments', 'jupiterx_wc_cart_count_fragments', 10, 1 );
/**
 * Get refreshed cart count.
 *
 * @param array $fragments The fragments.
 *
 * @since 1.0.0
 */
function jupiterx_wc_cart_count_fragments( $fragments ) {
	$count = WC()->cart->cart_contents_count;

	if ( empty( $count ) ) {
		$count = ' 0';
	}

	$markup = jupiterx_open_markup( 'jupiterx_navbar_cart_count', 'span', 'class=jupiterx-navbar-cart-count' );

		$markup .= jupiterx_output( 'jupiterx_navbar_brand_count_text', $count );

	$markup .= jupiterx_close_markup( 'jupiterx_navbar_cart_count', 'span' );

	$fragments['.jupiterx-navbar-cart-count'] = $markup;

	return $fragments;
}

add_action( 'woocommerce_product_query', 'jupiterx_wc_loop_shop_per_page' );
/**
 * Loop query post per page.
 *
 * @since 1.0.0
 *
 * @param object $query Query object.
 */
function jupiterx_wc_loop_shop_per_page( $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( 'none' === get_theme_mod( 'jupiterx_product_list_pagination', 'pagination' ) ) {
		$query->set( 'posts_per_page', -1 );

		return;
	}

	// Multiply rows and columns.
	$grid_columns = intval( get_theme_mod( 'jupiterx_product_list_grid_columns', 3 ) );
	$grid_rows    = intval( get_theme_mod( 'jupiterx_product_list_grid_rows', 3 ) );
	$grid_total   = $grid_columns * $grid_rows;

	// Set posts per page.
	$query->set( 'posts_per_page', $grid_total );
}

add_action( 'woocommerce_proceed_to_checkout', 'jupiterx_wc_continue_shopping_button', 5 );
add_action( 'woocommerce_review_order_after_submit', 'jupiterx_wc_continue_shopping_button' );
/**
 * Adds continue shopping button to cart and order page.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_wc_continue_shopping_button() {
	$shop_page_url = esc_url( get_permalink( wc_get_page_id( 'shop' ) ) );

	jupiterx_open_markup_e(
		'jupiterx_continue_shopping_button',
		'a',
		[
			'class' => 'button jupiterx-continue-shopping',
			'href'  => $shop_page_url,
		]
	);

		esc_html_e( 'Continue Shopping', 'jupiterx' );

	jupiterx_close_markup_e( 'jupiterx_continue_shopping_button', 'a' );
}

add_action( 'woocommerce_before_shop_loop_item', 'jupiterx_wc_loop_elements_enabled' );
/**
 * Enable or disable loop elements.
 *
 * @since 1.0.0
 */
function jupiterx_wc_loop_elements_enabled() {
	/**
	 * Key is the ID of the element from Customizer setting and its value is the element hook, function name and priority.
	 */
	$hooks = [
		'sale_badge'         => [ 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 15 ],
		'out_of_stock_badge' => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_out_of_stock', 15 ],
		'image'              => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_loop_product_thumbnail', 20 ],
		'category'           => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_item_category' ],
		'name'               => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_product_title_group' ],
		'rating'             => [ 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_rating' ],
		'price'              => [ 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_price' ],
		'add_to_cart'        => [ 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ],
	];

	$elements = get_theme_mod( 'jupiterx_product_list_elements', array_keys( $hooks ) );

	// Remove badges when image is hidden.
	if ( ! in_array( 'image', $elements, true ) ) {
		$elements = array_diff( $elements, [ 'sale_badge', 'out_of_stock_badge' ] );
	}

	$remove_elements = array_diff_key( $hooks, array_flip( $elements ) );

	// Remove actions from the hooks.
	foreach ( $remove_elements as $element ) {
		jupiterx_dynamic_remove_action( $element[0], $element[1], isset( $element[2] ) ? $element[2] : null );
	}
}

add_filter( 'loop_shop_columns', 'jupiterx_wc_loop_shop_columns' );
/**
 * Filter loop columns size.
 *
 * @since 1.0.0
 *
 * @param int $columns Number of columns.
 *
 * @return int
 */
function jupiterx_wc_loop_shop_columns( $columns ) {
	$grid_columns = intval( get_theme_mod( 'jupiterx_product_list_grid_columns', 3 ) );

	if ( ! empty( $grid_columns ) ) {
		return $grid_columns;
	}

	return $columns;
}

/**
 * Show product quick view.
 *
 * @since 1.11.0
 *
 * @return void
 */
function jupiterx_wc_product_quick_view() {
	if ( ! jupiterx_wc_is_product_quick_view_active() ) {
		return;
	}

	$opener = intval( get_theme_mod( 'jupiterx_product_list_quick_view_opener', 1 ) );

	add_action( 'woocommerce_after_shop_loop_item', 'jupiterx_wc_loop_item_after_quick_view' );

	if ( 2 === $opener ) {
		add_action( 'woocommerce_after_shop_loop_item', 'jupiterx_wc_loop_item_after_quick_view_btn' );
		add_filter( 'woocommerce_loop_add_to_cart_link', 'jupiterx_wc_after_add_to_cart_quick_view_btn', 10, 1 );
	} elseif ( 3 === $opener ) {
		add_action( 'jupiterx_wc_loop_product_image_append_markup', 'jupiterx_wc_thumbnail_quick_view_btn' );
	}
}

/**
 * Check product quick view is active.
 *
 * @since 1.11.0
 *
 * @return bool
 */
function jupiterx_wc_is_product_quick_view_active() {
	$quick_view_enabled = get_theme_mod( 'jupiterx_product_list_quick_view' );

	if ( empty( $quick_view_enabled ) ) {
		return false;
	}

	return true;
}

/**
 * Add social share in Woocommerce product page.
 *
 * @since 1.0.0
 */
function jupiterx_wc_product_page_social_share() {
	$elements = get_theme_mod( 'jupiterx_product_page_elements', [ 'social_share' ] );

	if ( in_array( 'social_share', $elements, true ) ) {
		jupiterx_post_social_share_shortcode( 'product_page', get_theme_mod( 'jupiterx_product_page_social_share_filter', [ 'email', 'facebook', 'twitter', 'pinterest', 'linkedin', 'reddit' ] ), false );
	}
}
