<?php
/**
 * Add Jupiter settings to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Pages.
JupiterX_Customizer::add_panel( 'jupiterx_pages', [
	'priority' => 800,
	'type'     => 'nested',
	'title'    => __( 'Third Party', 'jupiterx-core' ),
	'group'    => 'third_party',
] );

if ( class_exists( 'WooCommerce' ) ) {
	// Woocommerce.
	JupiterX_Customizer::add_panel( 'jupiterx_wc', [
		'priority' => 1,
		'type'     => 'nested',
		'title'    => __( 'WooCommerce', 'jupiterx-core' ),
		'group'    => 'woocommerce',
	] );
}

/**
 * Load all the popups.
 *
 * @since 1.0.0
 */
$popups = [
	'pro',
	'logo',
	'layout',
	'typography',
	'header',
	'title-bar',
	'sidebar',
	'footer',
	'blog-single',
	'blog-archive',
	'portfolio-single',
	'portfolio-archive',
	'page-single',
	'search',
	'404',
	'maintenance',
	'post-types',
	'comment',
	'go-to-top',
	'background',
];

$jx_wc_popups = [
	'product-list',
	'product-page',
	'checkout-cart',
	'product-archive',
	'cart-quick-view',
	'notice-messages',
];

if ( class_exists( 'WooCommerce' ) ) {
	$popups = array_merge( $popups, $jx_wc_popups );
}

foreach ( $popups as $popup ) {
	require_once dirname( __FILE__ ) . '/' . $popup . '/popup.php';
}

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {
	$site_identity_section = $wp_customize->get_section( 'title_tagline' );

	if ( $site_identity_section ) {
		$site_identity_section->group = 'general_settings';
	}

	$widgets_panel = $wp_customize->get_panel( 'title_tagline' );

	if ( $widgets_panel ) {
		$widgets_panel->group = 'general_settings';
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$jx_wc_sections = [
			'woocommerce_store_notice',
			'woocommerce_product_catalog',
			'woocommerce_product_images',
			'woocommerce_checkout',
		];

		foreach ( $jx_wc_sections as $wc_sections ) {
			$wp_customize->get_section( $wc_sections )->panel = null;
		}
	}

}, 20 );

function jupiterx_customizer_custom_templates_notice() {
	$notice = sprintf(
		'<span>%1$s <a class="jupiterx-alert-control-link" href="%2$s" target="_blank">%3$s<span class=" dashicons dashicons-external"></span></a> %4$s<span>',
		__( 'It’s recommended to use the new ', 'jupiterx-core' ),
		esc_url( admin_url( 'admin.php?page=jupiterx#/layout-builder' ) ),
		__( 'Layout Builder', 'jupiterx-core' ),
		__( 'feature.', 'jupiterx-core' )
	);

	return $notice;
}
