<?php
/**
 * Sets up the Jupiter menus.
 *
 * @package JupiterX\Framework\Render
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'after_setup_theme', 'jupiterx_do_register_nav_menus' );
/**
 * Register nav menus.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_do_register_nav_menus() {
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary Menu', 'jupiterx' ),
		'subfooter' => esc_html__( 'Sub Footer Menu', 'jupiterx' ),
	) );
}

// Filter.
jupiterx_add_smart_action( 'wp_nav_menu_args', 'jupiterx_modify_menu_args' );
/**
 * Modify wp_nav_menu arguments.
 *
 * This function converts the wp_nav_menu to Bootstrap format. It uses the Jupiter custom walker and also makes
 * use of the Jupiter HTML API.
 *
 * @since 1.0.0
 *
 * @param array $args The wp_nav_menu arguments.
 *
 * @return array The modified wp_nav_menu arguments.
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_modify_menu_args( $args ) {
	// Get type.
	$type = jupiterx_get( 'jupiterx_type', $args );

	// Check if the menu is in a widget area and set the type accordingly if it is defined.
	$widget_area_type = jupiterx_get_widget_area( 'jupiterx_type' );

	if ( $widget_area_type ) {
		$type = 'stack' === $widget_area_type ? '' : $widget_area_type;
	}

	// Stop if it isn't a Jupiter menu.
	if ( ! $type ) {
		return $args;
	}

	// Default item wrap attributes.
	$attr = array(
		'id'    => '%1$s',
		'class' => array( jupiterx_get( 'menu_class', $args ) ),
	);

	// Add UIkit offcanvas item wrap attributes.
	if ( 'offcanvas' === $type ) {
		$attr['class'][]     = 'uk-nav uk-nav-parent-icon uk-nav-offcanvas';
		$attr['data-uk-nav'] = '{multiple:true}';
	}

	// Implode to avoid empty spaces.
	$attr['class'] = implode( ' ', array_filter( $attr['class'] ) );

	// Set to null if empty to avoid outputing an empty HTML class attribute.
	if ( ! $attr['class'] ) {
		$attr['class'] = null;
	}

	$location = jupiterx_get( 'theme_location', $args );

	$location_subfilter = $location ? "[_{$location}]" : null;

	// Force Jupiter menu arguments.
	$force = array(
		'jupiterx_type' => $type,
		'items_wrap' => jupiterx_open_markup( "jupiterx_menu[_{$type}]{$location_subfilter}", 'ul', $attr, $args ) . '%3$s' . jupiterx_close_markup( "jupiterx_menu[_{$type}]{$location_subfilter}", 'ul', $args ),
	);

	// Allow walker overwrite.
	if ( ! jupiterx_get( 'walker', $args ) ) {
		$args['walker'] = new _JupiterX_Walker_Nav_Menu();
	}

	// Adapt level to walker depth.
	$level = jupiterx_get( 'jupiterx_start_level', $args );

	$force['jupiterx_start_level'] = $level ? $level - 1 : 0;

	return array_merge( $args, $force );
}
