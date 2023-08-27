<?php
/**
 * General admin functions.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since 1.6.0
 */

/**
 * Get content wrapper width.
 *
 * @since 1.6.0
 *
 * @return string Content width.
 */
function jupiterx_get_content_width() {
	global $post;

	$post_id = isset( $post->ID ) ? $post->ID : 0;

	$page_template = get_page_template_slug( $post_id );

	if ( 'full-width.php' === $page_template ) {
		return '100%';
	}

	// Metabox full width option.
	if ( 'true' === jupiterx_get_field_mod( 'jupiterx_content_full_width' ) ) {
		return '100%';
	}

	$container_width = jupiterx_get_container_width();

	if ( '%' === $container_width['unit'] ) {
		return '100%';
	}

	return jupiterx_get_sidebar_effected_content_width( $container_width['size'] );
}

/**
 * Calculate content width regarding to sidebars.
 *
 * @since 1.6.0
 *
 * @param string $container_width_size The numeric value of size of container width.
 *
 * @return string Content width.
 */
function jupiterx_get_sidebar_effected_content_width( $container_width_size = '' ) {

	if ( empty( $container_width_size ) ) {
		$container_width_size = jupiterx_get_container_width( 'size' );
	}

	// Sidebars.
	$global_layout  = get_theme_mod( 'jupiterx_sidebar_layout', 'c_sp' );
	$exception      = jupiterx_get_exception_mod( 'jupiterx_sidebar_exceptions' );
	$sidebar_layout = jupiterx_get_field( 'jupiterx_layout', 'global' );

	if ( isset( $exception['layout'] ) ) {
		$global_layout = $exception['layout'];
	}

	if ( 'global' === $sidebar_layout || 'default_fallback' === $sidebar_layout ) {
		$sidebar_layout = $global_layout;
	}

	$sidebar_affected_width = jupiterx_calculate_sidebar_affected_content_width();
	return $sidebar_affected_width[ $sidebar_layout ];
}

/**
 * Calculate width of content for all possible sidebar layouts.
 *
 * @since 1.6.0
 *
 * @return array Width of content.
 */
function jupiterx_calculate_sidebar_affected_content_width() {
	$width = [];

	$container_width_size = jupiterx_get_container_width( 'size' );

	// Sidebars.
	$global_layout  = get_theme_mod( 'jupiterx_sidebar_layout', 'c_sp' );
	$exception      = jupiterx_get_exception_mod( 'jupiterx_sidebar_exceptions' );
	$sidebar_layout = jupiterx_get_field( 'jupiterx_layout', 'global' );

	if ( isset( $exception['layout'] ) ) {
		$global_layout = $exception['layout'];
	}

	if ( 'global' === $sidebar_layout || 'default_fallback' === $sidebar_layout ) {
		$sidebar_layout = $global_layout;
	}

	$width['sp_ss_c'] = strval( round( $container_width_size * 0.5 ) - 10 ) . 'px';
	$width['c_sp_ss'] = $width['sp_ss_c'];
	$width['sp_c_ss'] = $width['sp_ss_c'];

	$width['c_sp'] = strval( round( $container_width_size * 0.75 ) - 10 ) . 'px';
	$width['sp_c'] = $width['c_sp'];

	$width['c'] = strval( round( $container_width_size ) - 10 ) . 'px';

	$width['global'] = $width[ $sidebar_layout ];

	return $width;
}

/**
 * Get site container width.
 *
 * @param string $ret Return size, unit or all as an array.
 *
 * @since 1.6.0
 *
 * @return array|string $container_width Size and unit of container width.
 */
function jupiterx_get_container_width( $ret = 'all' ) {
	// Container width.
	$layout_mode     = jupiterx_get_field_mod( 'jupiterx_site_width', 'global', 'full_width' );
	$container_width = jupiterx_get_field_mod(
		'jupiterx_site_container_main_width',
		'global',
		[
			'size' => 1140,
			'unit' => 'px',
		]
	);

	if ( 'boxed' === $layout_mode ) {
		$container_width = jupiterx_get_field_mod(
			'jupiterx_site_boxed_container_main_width',
			'global',
			[
				'size' => 1140,
				'unit' => 'px',
			]
		);
	}

	// Make sure values are set.
	$container_width = array_filter( $container_width );
	$container_width = wp_parse_args( $container_width, [
		'size' => 1140,
		'unit' => 'px',
	] );

	if ( is_array( $container_width ) && in_array( $ret, [ 'size', 'unit' ], true ) ) {
		return $container_width[ $ret ];
	}

	return $container_width;
}

add_action( 'page_attributes_meta_box_template', 'jupiterx_add_template_help_link' );
/**
 * Add template help link to WordPress Classic Editor.
 *
 * @since 1.8.0
 */
function jupiterx_add_template_help_link() {
	if ( jupiterx_is_help_links() ) {
		echo wp_kses( '<a class="jupiterx-template-help-link" target="_blank" href="' . esc_url( 'https://themes.artbees.net/docs/setting-page-template/' ) . '">' . esc_html__( 'Help', 'jupiterx' ) . '</a>', [
			'a' => [
				'class' => [],
				'target' => [],
				'href' => [],
			],
		] );
	}
}

add_action( 'vc_before_init', 'jupiterx_wpbakery_set_as_bundled' );
/**
 * Set WPBakery Page Builder as bundled plugin.
 *
 * @since 1.18.0
 */
function jupiterx_wpbakery_set_as_bundled() {
	vc_set_as_theme();
}

