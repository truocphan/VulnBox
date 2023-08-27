<?php
/**
 * Echo menu fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_navbar_collapse_append_markup', 'jupiterx_navbar_nav' );
/**
 * Echo primary menu.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_navbar_nav() {
	$behavior = get_theme_mod( 'jupiterx_header_behavior' );
	$position = get_theme_mod( 'jupiterx_header_position' );

	$menu_class = 'jupiterx-nav-primary navbar-nav';

	if ( 'fixed' === $behavior && 'bottom' === $position ) {
		$menu_class .= ' dropup';
	}

	/**
	 * Filter the primary menu arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Nav menu arguments.
	 */
	$args = apply_filters(
		'jupiterx_primary_menu_args',
		[
			'theme_location'  => has_nav_menu( 'primary' ) ? 'primary' : '',
			'depth'           => 3,
			'menu_class'      => $menu_class,
			'fallback_cb'     => 'jupiterx_no_menu_notice',
			'echo'            => false,
			'jupiterx_type'    => 'navbar',
		]
	);

	// Navigation.
	jupiterx_output_e( 'jupiterx_primary_menu', wp_nav_menu( $args ) );

}

jupiterx_add_smart_action( 'jupiterx_navbar_collapse_append_markup', 'jupiterx_navbar_search', 15 );
/**
 * Echo primary menu offcanvas button.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_navbar_search() {
	jupiterx_output_e( 'jupiterx_navbar_search_form', get_search_form( false ) );
}

/**
 * Echo no menu notice.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_no_menu_notice() {

	if ( ! jupiterx_is_preview() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_no_menu_notice', 'div', [ 'class' => 'alert alert-warning mb-0 mr-2 ml-auto' ] );

		jupiterx_output_e( 'jupiterx_no_menu_notice_text', esc_html__( 'Whoops, your site does not have a menu!', 'jupiterx' ) );

	jupiterx_close_markup_e( 'jupiterx_no_menu_notice', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_site_navbar_before_markup', 'jupiterx_ubermenu_compatibility' );
/**
 * Modify header markup when Ubermenu is active.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_ubermenu_compatibility() {
	if ( ! function_exists( 'ubermenu' ) ) {
		return;
	}

	jupiterx_remove_markup( 'jupiterx_navbar_collapse' );
}
