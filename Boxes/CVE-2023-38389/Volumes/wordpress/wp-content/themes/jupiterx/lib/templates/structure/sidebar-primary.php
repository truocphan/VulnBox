<?php
/**
 * Echo the primary sidebar structural markup. It also calls the primary sidebar action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_open_markup_e(
	'jupiterx_sidebar_primary',
	'aside',
	array(
		'class'     => 'jupiterx-sidebar jupiterx-secondary ' . jupiterx_get_layout_class( 'sidebar_primary' ), // Automatically escaped.
		'role'      => 'complementary',
		'itemscope' => 'itemscope',
		'itemtype'  => 'http://schema.org/WPSideBar',
	)
);

	/**
	 * Fires in the primary sidebar.
	 *
	 * @since 1.0.0
	 */
	do_action( 'jupiterx_sidebar_primary' );

jupiterx_close_markup_e( 'jupiterx_sidebar_primary', 'aside' );
