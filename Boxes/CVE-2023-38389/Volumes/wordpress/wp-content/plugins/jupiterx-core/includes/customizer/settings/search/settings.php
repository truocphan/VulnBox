<?php
/**
 * Add Jupiter settings for Pages > Search > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_search';

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_search_label_1',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Display Section', 'jupiterx-core' ),
] );

$post_types = [
	'post'      => __( 'Post', 'jupiterx-core' ),
	'portfolio' => __( 'Portfolio', 'jupiterx-core' ),
	'page'      => __( 'Page', 'jupiterx-core' ),
	'product'   => __( 'Product', 'jupiterx-core' ),
];

$custom_post_types = [];

if ( function_exists( 'jupiterx_get_post_types' ) ) {
	$custom_post_types = jupiterx_get_post_types( 'labels', [
		'exclude_from_search' => false,
	] );
}

$post_types = array_merge( $post_types, $custom_post_types );

// Display content.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_search_post_types',
	'section'  => $section,
	'box'      => 'settings',
	'default'  => array_keys( $post_types ),
	'choices'  => $post_types,
] );

// Posts per page.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-text',
	'settings'    => 'jupiterx_search_posts_per_page',
	'section'     => $section,
	'box'         => 'settings',
	'label'       => __( 'Posts Per Page', 'jupiterx-core' ),
	'default'     => 5,
	'input_type'  => 'number',
	'input_attrs' => [
		'min' => 5,
	],
] );
