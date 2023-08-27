<?php
/**
 * Echo the secondary sidebar structural markup. It also calls the secondary sidebar action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_open_markup_e(
	'jupiterx_sidebar_secondary',
	'aside',
	array(
		'class'     => 'jupiterx-sidebar jupiterx-tertiary ' . jupiterx_get_layout_class( 'sidebar_secondary' ), // Automatically escaped.
		'role'      => 'complementary',
		'itemscope' => 'itemscope',
		'itemtype'  => 'http://schema.org/WPSideBar',
	)
);

	/**
	 * Fires in the secondary sidebar.
	 *
	 * @since 1.0.0
	 */
	do_action( 'jupiterx_sidebar_secondary' );

jupiterx_close_markup_e( 'jupiterx_sidebar_secondary', 'aside' );
