<?php
/**
 * Add Jupiter Post Options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

add_action( 'init', 'jupiterx_post_types_meta_options', 15 );
/**
 * Register post types options.
 *
 * @since 1.14.0
 */
function jupiterx_post_types_meta_options() {
	$locations = [];

	$default_post_types = [
		'post',
		'portfolio',
		'page',
	];

	$custom_post_types = jupiterx_get_post_types();

	$jupiterx_post_types = apply_filters( 'jupiterx_post_options_post_types', array_merge( $default_post_types, $custom_post_types ) );

	foreach ( array_unique( $jupiterx_post_types ) as $jupiterx_post_type ) {
		$locations[] = [
			[
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $jupiterx_post_type,
			],
		];
	}

	// Post Options.
	acf_add_local_field_group( [
		'key'      => 'group_jupiterx_post',
		'title'    => __( 'Post Options', 'jupiterx' ),
		'location' => $locations,
	] );
}
