<?php
/**
 * The Jupiter WooCommerce global integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

$checkout_steps = get_theme_mod( 'jupiterx_jupiterx_checkout_cart_elements', [
	'steps',
] );

if ( is_array( $checkout_steps ) && in_array( 'steps', $checkout_steps, true ) ) {
	jupiterx_add_smart_action( 'jupiterx_main_content_before_markup', 'jupiterx_wc_add_steps' );
}

/**
 * Add WooCommerce steps to cart, checkout and order page.
 *
 * @since 1.0.0
 */
function jupiterx_wc_add_steps() {
	if ( ! is_cart() && ! is_checkout() ) {
		return;
	}

	$steps = [
		'cart'     => [
			'icon'    => '',
			'number'  => __( '1', 'jupiterx' ),
			'icon'    => 'jupiterx-icon-shopping-cart-6',
			'title'   => get_theme_mod( 'jupiterx_checkout_cart_steps_1', __( 'Cart', 'jupiterx' ) ),
			'class'   => [ 'jupiterx-wc-step' ],
			'divider' => true,
		],
		'checkout' => [
			'icon'    => '',
			'number'  => __( '2', 'jupiterx' ),
			'icon'    => 'jupiterx-icon-credit-card',
			'title'   => get_theme_mod( 'jupiterx_checkout_cart_steps_2', __( 'Delivery & Payment', 'jupiterx' ) ),
			'class'   => [ 'jupiterx-wc-step' ],
			'divider' => true,
		],
		'order'    => [
			'icon'   => '',
			'number' => __( '3', 'jupiterx' ),
			'icon'   => 'jupiterx-icon-check',
			'title'  => get_theme_mod( 'jupiterx_checkout_cart_steps_3', __( 'Complete Order', 'jupiterx' ) ),
			'class'  => [ 'jupiterx-wc-step' ],
		],
	];

	$style = get_theme_mod( 'jupiterx_checkout_cart_steps_step_style', 'number' );

	if ( is_cart() ) {
		$steps['cart']['class'][] = 'jupiterx-wc-step-active';
	}

	if ( is_wc_endpoint_url( 'order-received' ) ) {
		$steps['order']['class'][] = 'jupiterx-wc-step-active';
	} elseif ( is_checkout() ) {
		$steps['checkout']['class'][] = 'jupiterx-wc-step-active';
	}

	jupiterx_open_markup_e( 'jupiterx_wc_steps', 'div', 'class=jupiterx-wc-steps' );

	foreach ( $steps as $key => $value ) {

		jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key, 'div', 'class=' . esc_attr( implode( ' ', $value['class'] ) ) );

		if ( 'number' === $style ) {

			jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key . '_number', 'span', 'class=jupiterx-wc-step-number' );

				jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key . '_number_text', 'span', 'class=jupiterx-wc-step-text' );

					jupiterx_output_e( 'jupiterx_wc_step_' . $key . '_number_text', $value['number'] );

				jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key . '_number_text', 'span' );

			jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key . '_number', 'span' );

		}

		if ( 'icon' === $style ) {

			jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key . '_icon', 'i', 'class=jupiterx-wc-step-icon ' . $value['icon'] );

			jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key . '_icon', 'i' );

		}

			jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key . '_title', 'span', 'class=jupiterx-wc-step-title' );

				jupiterx_open_markup_e( 'jupiterx_wc_step_' . $key . '_title_text', 'span', 'class=jupiterx-wc-step-text' );

					jupiterx_output_e( 'jupiterx_wc_step_' . $key . '_title_text', $value['title'] );

				jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key . '_title_text', 'span' );

			jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key . '_title', 'span' );

		jupiterx_close_markup_e( 'jupiterx_wc_step_' . $key, 'div' );

		if ( ! empty( $value['divider'] ) ) {

			jupiterx_open_markup_e( 'jupiterx_wc_step_divider', 'div', 'class=jupiterx-wc-step-divider' );

			jupiterx_close_markup_e( 'jupiterx_wc_step_divider', 'div' );

		}
	}

	jupiterx_close_markup_e( 'jupiterx_wc_steps', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_footer_after_markup', 'jupiterx_wc_cart_quick_view' );
/**
 * Add WooCommerce cart quick view.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_wc_cart_quick_view() {

	if ( '_custom' === jupiterx_get_field_mod( 'jupiterx_header_type', 'global' ) ) {
		return;
	}

	if ( ! get_theme_mod( 'jupiterx_header_shopping_cart', false ) || is_checkout() || is_cart() ) {
		return;
	}

	$position = get_theme_mod( 'jupiterx_header_shopping_cart_position', 'right' );

	jupiterx_open_markup_e(
		'jupiterx_cart_quick_view',
		'div',
		[
			'class'         => 'jupiterx-cart-quick-view',
			'data-position' => esc_attr( $position ),
		]
	);

		jupiterx_open_markup_e( 'jupiterx_mini_cart_header', 'div', 'class=jupiterx-mini-cart-header' );

			jupiterx_open_markup_e( 'jupiterx_mini_cart_title', 'p', 'class=jupiterx-mini-cart-title' );

				jupiterx_output_e( 'jupiterx_mini_cart_title_text', __( 'Shopping cart', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_mini_cart_title', 'p' );

			jupiterx_open_markup_e(
				'jupiterx_mini_cart_close',
				'button',
				[
					'class' => 'btn jupiterx-mini-cart-close jupiterx-icon-long-arrow',
					'role'  => 'button',
				]
			);

			jupiterx_close_markup_e( 'jupiterx_mini_cart_close', 'button' );

			jupiterx_close_markup_e( 'jupiterx_mini_cart_header', 'div' );

		the_widget( 'WC_Widget_Cart', [ 'title' => '' ] );

	jupiterx_close_markup_e( 'jupiterx_cart_quick_view', 'div' );

}

jupiterx_add_smart_action( 'jupiterx_navbar_content_append_markup', 'jupiterx_navbar_cart', 15 );
/**
 * Echo header navbar cart.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_navbar_cart() {
	$cart_url = esc_url( wc_get_cart_url() );
	$count    = WC()->cart->cart_contents_count;

	if ( true === get_theme_mod( 'jupiterx_header_shopping_cart', false ) ) {
		$cart_url = '#';
	}

	if ( empty( $count ) ) {
		$count = ' 0';
	}

	jupiterx_open_markup_e(
		'jupiterx_navbar_cart',
		'a',
		[
			'class' => 'jupiterx-navbar-cart',
			'href'  => $cart_url,
		]
	);

		jupiterx_open_markup_e( 'jupiterx_navbar_cart_icon', 'span', 'class=jupiterx-navbar-cart-icon jupiterx-icon-shopping-cart-6' );

		jupiterx_close_markup_e( 'jupiterx_navbar_cart_icon', 'span' );

		jupiterx_open_markup_e( 'jupiterx_navbar_cart_count', 'span', 'class=jupiterx-navbar-cart-count' );

			jupiterx_output_e( 'jupiterx_navbar_brand_count_text', $count );

		jupiterx_close_markup_e( 'jupiterx_navbar_cart_count', 'span' );

	jupiterx_close_markup_e( 'jupiterx_navbar_cart', 'a' );
}

add_action( 'jupiterx_main_header_post_title_text_output', 'jupiterx_woocommerce_account_title' );
add_action( 'jupiterx_breadcrumb_item[_active]_text_output', 'jupiterx_woocommerce_account_title' );
/**
 * Show proper title in account page.
 *
 * @since 1.18.0
 *
 * @param array $title The default title.
 *
 * @return $title the title.
 */
function jupiterx_woocommerce_account_title( $title ) {

	if ( is_account_page() ) {
		$endpoint       = WC()->query->get_current_endpoint();
		$endpoint_title = WC()->query->get_endpoint_title( $endpoint );
		$title          = $endpoint_title ? $endpoint_title : $title;
	}

	return $title;
}

/**
 * Add our classname to Sale badge.
 *
 * @since 1.23.0
 *
 * @param string $output The output.
 *
 * @return $output The output.
 */
function jupiterx_wc_product_page_custom_sale_badge( $output ) {
	$output = str_replace( 'onsale', 'onsale jupiterx-sale-badge', $output );

	return $output;
}

/**
 * Check woocommerce page.
 *
 * @since 1.25.0
 *
 * @return boolean
 */
function jupiterx_is_woocommerce() {
	if ( is_woocommerce() ) {
		return true;
	}

	return false;
}

if ( ! jupiterx_is_woocommerce() ) {
	return;
}

/**
 * Jupiter comment filter causes issue for single product rating field.
 *
 * @since 1.0.0
 */
jupiterx_remove_action( 'jupiterx_comment_form_comment' );

/**
 * Hide WooCommerce breadcrumb.
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
