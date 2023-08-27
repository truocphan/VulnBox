<?php
/**
 * The Jupiter Footer API controls what and how Jupiter footer is displayed.
 *
 * @package JupiterX\Framework\API\Footer
 *
 * @since   1.0.0
 */

/**
 * Get the footer layout.
 *
 * @since 1.0.0
 *
 * @return boolean
 */
function jupiterx_is_footer_enabled() {
	// Get setting.
	$enabled = jupiterx_get_field_mod( 'jupiterx_footer_widget_area', 'global', false );

	// Return status.
	return (bool) $enabled;
}

/**
 * Get the footer container class.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_footer_container_class() {
	$class = 'container';

	if ( get_theme_mod( 'jupiterx_footer_widgets_full_width' ) ) {
		$class = 'container-fluid';
	}

	return $class;
}

/**
 * Get the footer layout columns.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_footer_layout_columns() {
	return get_theme_mod( 'jupiterx_footer_widgets_layout', 'footer_layout_01' );
}

/**
 * Get the footer layout columns patern.
 *
 * @since 1.0.0
 *
 * @param string $id The column pattern id.
 * @return int
 */
function jupiterx_get_footer_columns_pattern( $id = '' ) {
	/**
	 * The layout columns pattern.
	 *
	 * Each new column is separated by comma and its value indicates as the element's class name.
	 */
	$columns_pattern = [
		'footer_layout_01' => 'col-md-12',
		'footer_layout_02' => 'col-md-6,col-md-6',
		'footer_layout_03' => 'col-md-4,col-md-4,col-md-4',
		'footer_layout_04' => 'col-md-3,col-md-3,col-md-3,col-md-3',
		'footer_layout_05' => 'col,col,col,col,col',
		'footer_layout_06' => 'col-md-2,col-md-2,col-md-2,col-md-2,col-md-2,col-md-2',
		'footer_layout_07' => 'col-md-6,col-md-3,col-md-3',
		'footer_layout_08' => 'col-md-4,col,col,col',
		'footer_layout_09' => 'col-md-6,col-md-2,col-md-2,col-md-2',
		'footer_layout_10' => 'col-md-4,col-md-2,col-md-2,col-md-2,col-md-2',
		'footer_layout_11' => 'col-md-3,col-md-3,col-md-6',
		'footer_layout_12' => 'col,col,col,col-md-4',
		'footer_layout_13' => 'col-md-2,col-md-2,col-md-2,col-md-6',
		'footer_layout_14' => 'col-md-2,col-md-2,col-md-2,col-md-2,col-md-4',
		'footer_layout_15' => 'col-md-9,col-md-3',
		'footer_layout_16' => 'col-md-3,col-md-9',
		'footer_layout_17' => 'col-md-3,col-md-6,col-md-3',
	];

	if ( ! isset( $columns_pattern[ $id ] ) ) {
		return;
	}

	return explode( ',', $columns_pattern[ $id ] );
}

/**
 * Get the footer maximum columns.
 *
 * @since 1.0.0
 *
 * @return int
 */
function jupiterx_get_footer_max_columns() {
	return count( jupiterx_get_footer_columns_pattern( 'footer_layout_06' ) );
}

/**
 * Get the sub footer status.
 *
 * @since 1.0.0
 *
 * @return boolean
 */
function jupiterx_is_footer_sub_enabled() {
	// Get setting.
	$enabled = jupiterx_get_field_mod( 'jupiterx_footer_sub', 'global', true );

	// Return status.
	return (bool) $enabled;
}

/**
 * Get the sub footer layout class.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_footer_sub_container_class() {
	$class = 'container';

	if ( get_theme_mod( 'jupiterx_footer_sub_full_width' ) ) {
		$class = 'container-fluid';
	}

	return $class;
}

/**
 * Echo the sub footer copyright.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_sub_copyright() {
	jupiterx_open_markup_e( 'jupiterx_subfooter_copyright', 'div', [ 'class' => 'jupiterx-subfooter-copyright' ] );

		$default_copyright = sprintf(
			// translators: Footer credits. Date followed by the name of the website.
			__( '&#x000A9; %1$s - %2$s. All rights reserved.', 'jupiterx' ),
			date_i18n( __( 'Y', 'jupiterx' ) ),
			get_bloginfo( 'name' )
		);

		jupiterx_output_e( 'jupiterx_subfooter_credit_text', $default_copyright );

	jupiterx_close_markup_e( 'jupiterx_subfooter_copyright', 'div' );
}

/**
 * Echo the sub footer menu.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_sub_menu() {
	jupiterx_output_e( 'jupiterx_footer_sub_wp_menu', wp_nav_menu( [
		'theme_location'  => has_nav_menu( 'subfooter' ) ? 'subfooter' : '',
		'container_class' => 'jupiterx-subfooter-menu-container',
		'menu_id'         => 'jupiterx-subfooter-menu',
		'menu_class'      => 'jupiterx-subfooter-menu',
		'depth'           => 1,
		'fallback_cb'     => 'jupiterx_no_menu_notice',
		'echo'            => false,
	] ) );
}
