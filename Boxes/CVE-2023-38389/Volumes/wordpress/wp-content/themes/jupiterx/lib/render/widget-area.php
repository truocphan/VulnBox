<?php
/**
 * Registers the Jupiter default widget areas.
 *
 * @package JupiterX\Framework\Render
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'widgets_init', 'jupiterx_do_register_widget_areas', 5 );
/**
 * Register Jupiter's default widget areas.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_do_register_widget_areas() {
	// Keep primary sidebar first for default widget asignment.
	jupiterx_register_widget_area( [
		'name' => esc_html__( 'Sidebar Primary', 'jupiterx' ),
		'id'   => 'sidebar_primary',
	] );

	jupiterx_register_widget_area( [
		'name' => esc_html__( 'Sidebar Secondary', 'jupiterx' ),
		'id'   => 'sidebar_secondary',
	] );

	if ( current_theme_supports( 'offcanvas-menu' ) ) {
		jupiterx_register_widget_area( [
			'name'       => esc_html__( 'Off-Canvas Menu', 'jupiterx' ),
			'id'         => 'offcanvas_menu',
			'jupiterx_type' => 'offcanvas',
		] );
	}

	$columns_count = jupiterx_get_footer_max_columns();

	for ( $i = 1; $i <= $columns_count; $i++ ) {
		jupiterx_register_widget_area( [
			// Translators: Number of widget area in footer.
			'name' => sprintf( esc_html__( 'Footer %d', 'jupiterx' ), $i ),
			'id'   => 'footer_widgets_column_' . $i,
		] );
	}

	if ( ! jupiterx_is_callable( 'JupiterX_Core' ) ) {
		return;
	}

	$custom_sidebars = jupiterx_get_option( 'custom_sidebars' );

	if ( empty( $custom_sidebars ) ) {
		$custom_sidebars = [];
	}

	foreach ( $custom_sidebars as $index => $custom_sidebar ) {
		jupiterx_register_widget_area( [
			'name' => $custom_sidebars[ $index ]['name'],
			'id'   => 'jupiterx_custom_sidebar_' . ( $index + 1 ),
		] );
	}
}

/**
 * Call register sidebar.
 *
 * Because the WordPress.org checker doesn't understand that we are using register_sidebar properly,
 * we have to add this useless call which only has to be declared once.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 */
add_action( 'widgets_init', 'jupiterx_register_widget_area' );
