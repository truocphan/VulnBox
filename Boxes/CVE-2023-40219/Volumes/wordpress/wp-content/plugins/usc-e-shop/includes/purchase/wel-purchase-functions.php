<?php
/**
 * Welcart Purchase Functions
 *
 * Functions for Purchase related.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Function to get information about the products in the cart buyer is purchasing.
 *
 * @since 2.2.2
 *
 * @param boolean $decode Set this parameter to "true" to decode urlencoded characters.
 * @return array cart data. If there is no item in the cart returns an empty array.
 */
function wel_get_cart( $decode = false ) {
	global $usces;
	$carts = $usces->cart->get_cart();

	foreach ( $carts as $index => $cart_row ) {

		$carts[ $index ]['post_id']    = $cart_row['post_id'];
		$carts[ $index ]['price']      = $cart_row['price'];
		$carts[ $index ]['unit_price'] = $cart_row['unit_price'];
		$carts[ $index ]['quantity']   = $cart_row['quantity'];

		if ( true === $decode ) {
			$carts[ $index ]['sku'] = urldecode( $cart_row['sku'] );

			$options = array();
			foreach ( $cart_row['options'] as $key => $value ) {
				$options[ urldecode( $key ) ] = urldecode( $value );
			}
			$carts[ $index ]['options'] = $options;
		}
	}
	return $carts;
}
/**
 * Function to get purchaser information and purchase conditions.
 *
 * @since 2.2.2
 *
 * @return array Entries data. If there is no item in the cart returns an initial array.
 */
function wel_get_entry() {
	global $usces;
	$entries = $usces->cart->get_entry();

	return $entries;
}

/**
 * Function to get total amount of items in the cart.
 *
 * @since 2.2.2
 *
 * @param boolean $crform Set this parameter to "true" to add currency symbol etc.
 * @return int|string Total amount. If there is no item in the cart returns an empty array.
 */
function wel_get_total_amount_in_cart( $crform = false ) {
	global $usces;
	$price = $usces->get_total_price();

	if ( $crform ) {
		$res = usces_crform( $price, true, false, 'return' );
	} else {
		$res = $price;
	}
	return $res;
}

/**
 * Campaign discount message.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @param int    $post_id Post ID.
 * @param string $out Outpu type. If the value of $out is 'return', return; otherwise, echo.
 *
 * @return string Message.
 */
function wel_campaign_message( $post_id = 0, $out = 'out' ) {
	global $post, $usces;

	if ( 0 === $post_id ) {
		$post_id = $post->ID;
	}

	$html    = '';
	$options = $usces->options;

	if ( 'Promotionsale' === $options['display_mode'] && in_category( (int) $options['campaign_category'], $post_id ) ) {

		if ( 'discount' === $options['campaign_privilege'] && ! empty( $options['privilege_discount'] ) ) {
			$html = '<div class="campaign_message campaign_discount">' . sprintf( __( '%d&#37; OFF', 'usces' ), $options['privilege_discount'] ) . '</div>';
		} elseif ( 'point' === $options['campaign_privilege'] && ! empty( $options['privilege_point'] ) ) {
			$html = '<div class="campaign_message campaign_point">' . sprintf( __( '%d times more points', 'usces' ), $options['privilege_point'] ) . '</div>';
		}
	}

	$html = apply_filters( 'welcart_basic_filter_campaign_message', $html, $post_id );

	if ( 'return' === $out ) {
		return $html;
	} else {
		echo wp_kses_post( $html );
	}
}

/**
 * Whether or not the cart contains regular merchandising items.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @return boolean
 */
function wel_have_shipped() {
	$shipped = true;
	if ( defined( 'WCEX_DLSELLER' ) ) {
		$shipped = dlseller_have_shipped();
	}
	return $shipped;
}

/**
 * Curt page URL.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @return string URL.
 */
function wel_get_cart_url() {
	global $usces;
	$cart_url = USCES_CART_URL . $usces->delim;
	return $cart_url;
}

/**
 * Whether there are digital or service items in the cart.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @return boolean
 */
function wel_have_dlseller_content() {
	if ( function_exists( 'dlseller_has_terms' ) ) {
		$res = ( defined( 'WCEX_DLSELLER' ) && dlseller_have_dlseller_content() && dlseller_has_terms() ) ? true : false;
	} else {
		$res = ( defined( 'WCEX_DLSELLER' ) && dlseller_have_dlseller_content() ) ? true : false;
	}
	return $res;
}

/**
 * Subscription history of logged-in members.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 */
function wel_autodelivery_history() {
	$html = '';
	if ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
		$html = wcad_autodelivery_history( 'return' );
	}
	echo wp_kses_post( $html );
}

/**
 * Whether there are digital or service items in the cart.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @return boolean
 */
function wel_have_ex_order() {
	$ex_order = false;
	if ( defined( 'WCEX_DLSELLER' ) ) {
		$ex_order = ( ! dlseller_have_dlseller_content() && ! dlseller_have_continue_charge() ) ? false : true;
	} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
		$ex_order = wcad_have_regular_order();
	}
	return $ex_order;
}

/**
 * Display the password policy.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 */
function wel_password_policy_message() {
	if ( function_exists( 'usces_password_policy_message' ) ){
		usces_password_policy_message();
	}
}

/**
 * Whether the product can use points.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @return boolean
 */
function wel_is_available_point() {
	$res = true;
	if ( function_exists( 'usces_is_available_point' ) ) {
		$res = usces_is_available_point();
	} else {
		if ( defined( 'WCEX_DLSELLER_VERSION' ) && function_exists( 'dlseller_have_continue_charge' ) ) {
			if ( dlseller_have_continue_charge() ) {
				$res = false;
			}
		}
	}
	return $res;
}

/**
 * Get Charging Type.
 * Welcart Basic Template Tag.
 *
 * @since 2.2.3
 *
 * @param int $post_id Post ID.
 * @return string ChargingType( service|data|shipped ).
 */
function wel_get_item_chargingtype( $post_id = 0 ) {
	global $post, $usces;

	if ( 0 === $post_id ) {
		$post_id = $post->ID;
	}

	$charging = $usces->getItemChargingType( $post_id );

	if ( 'continue' === $charging && ! defined( 'WCEX_DLSELLER' ) ) {
		$charging = '';
	}

	if ( 'regular' === $charging && ! defined( 'WCEX_AUTO_DELIVERY' ) ) {
		$charging = '';
	}

	return $charging;
}
