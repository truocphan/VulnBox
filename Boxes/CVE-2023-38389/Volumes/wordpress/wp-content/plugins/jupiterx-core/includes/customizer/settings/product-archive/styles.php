<?php
/**
 * Add Jupiter Styles for Product > Product Archive > Styles tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_product_archive';

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_list_archive_label_1',
	'section'  => $section,
	'box'      => 'style_title',
	'label'    => __( 'Title', 'jupiterx' ),
] );

// Archive Title Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_product_list_archive_title_typography',
	'section'    => $section,
	'box'        => 'style_title',
	'responsive' => true,
	'css_var'    => 'product-list-archive-title-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
	'output'     => [
		[
			'element' => '.woocommerce .woocommerce-products-header__title.page-title',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_product_list_archive_divider_1',
	'section'  => $section,
	'box'      => 'style_title',
] );

// Archive Title Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_product_list_archive_title_spacing',
	'section'   => $section,
	'box'       => 'style_title',
	'css_var'   => 'product-list-archive-title-spacing',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.woocommerce .woocommerce-products-header__title.page-title',
		],
	],
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_list_archive_label_2',
	'section'  => $section,
	'box'      => 'style_description',
	'label'    => __( 'Description', 'jupiterx' ),
] );

// Archive description Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_product_list_archive_desc_typography',
	'section'    => $section,
	'box'        => 'style_description',
	'responsive' => true,
	'css_var'    => 'product-list-archive-desc-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
	'output'     => [
		[
			'element' => '.woocommerce .woocommerce-products-header .term-description',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_product_list_archive_divider_3',
	'section'  => $section,
	'box'      => 'style_description',
] );

// Archive description Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_product_list_archive_desc_spacing',
	'section'   => $section,
	'box'       => 'style_description',
	'css_var'   => 'product-list-archive-desc-spacing',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.woocommerce .woocommerce-products-header .term-description',
		],
	],
] );

