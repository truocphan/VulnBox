<?php
/**
 * This file is responsible for Woocommerce functions.
 *
 * @package JupiterX_Core\Woocommerce
 *
 * @since 2.0.0
 */

remove_action( 'template_redirect', 'wc_track_product_view', 20 );
add_action( 'template_redirect', 'jupiterx_wc_track_product_view', 20 );
add_action( 'wp_enqueue_scripts', 'jupiterx_wc_scripts_method' );
/**
 * Always track viewed products.
 * It's a copy of WC functions with small modification to make sure viewed products are
 * always stored.
 *
 * @since 2.0.0
 *
 * @return void
 */
function jupiterx_wc_track_product_view() {
	if ( ! class_exists( 'WooCommerce' ) || ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
		$viewed_products = array();
	} else {
		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
	}

	// Unset if already in viewed products list.
	$keys = array_flip( $viewed_products );

	if ( isset( $keys[ $post->ID ] ) ) {
		unset( $viewed_products[ $keys[ $post->ID ] ] );
	}

	$viewed_products[] = $post->ID;

	if ( count( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	// Store for session only.
	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}

/**
 * After click on load more in products widget or in shop page, the quick view doesn't work correctly and it has some styling issues in quantity input.
 * We will fix it with adding some jquery codes.
 *
 * @since 3.0.0
 *
 * @return void
 */
function jupiterx_wc_scripts_method() {
	wp_enqueue_script( 'jupiterx-wc-quickview', jupiterx_core()->plugin_url() . 'includes/woocommerce/wc-quick-view.js', [ 'jquery' ], JUPITERX_VERSION, true );
}

