<?php
/**
 * Single Product Accordions
 *
 * This template is based on default WooCommerce single-product/tabs/tabs.php
 * This has been modified to be used for accordions.
 *
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) {
	$accordions = '<div class="woocommerce-tabs accordion" id="jupiterxWooAccordion">';
	$index = 0;

	foreach ( $tabs as $key => $tab ) {
		$index++;
		$accordions .= '<div class="card">';
		$accordions .= '<div id="jupiterx-wc-header-' . $key . '" class="card-header ' . ( $index > 1 ? 'collapsed' : '' )  . '" data-toggle="collapse" data-target="#collapse' . $key . '" aria-expanded="true" aria-controls="collapse' . $key . '">';
		$accordions .= '<h6 class="card-title">' . esc_html( $tab['title'] ) . ' <span class="jupiterx-icon-angle-down"></span></h6>';
		$accordions .= '</div>';

		$accordions .= '<div id="collapse' . $key . '" class="collapse ' . ( $index == 1 ? 'show' : 'collapsed' ) . '" aria-labelledby="heading' . $key . '" data-parent="#jupiterxWooAccordion">';
		$accordions .= '<div class="card-body">';

		if ( isset( $tab['callback'] ) ) {
			ob_start();
			call_user_func( $tab['callback'], $key, $tab );
			$accordions .= ob_get_clean();
		}

		$accordions .= '</div>';
		$accordions .= '</div>';
		$accordions .= '</div>';
	}

	$accordions .= '</div>';

	echo $accordions;
}
