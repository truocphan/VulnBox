<?php
/**
 * The Jupiter Layout API controls what and how Jupiter main section elements are displayed.
 *
 * Layouts are:
 *      - "c" - content only
 *      - "c_sp" - content + sidebar primary
 *      - "sp_c" - sidebar primary + content
 *      - "c_ss" - content + sidebar secondary
 *      - "ss_c" - sidebar secondary + content
 *      - "c_sp_ss" - content + sidebar primary + sidebar secondary
 *      - "sp_ss_c" - sidebar primary + sidebar secondary + content
 *      - "sp_c_ss" - sidebar primary + content + sidebar secondary
 *
 * @package JupiterX\Framework\API\Layout
 *
 * @since   1.0.0
 */

/**
 * Get the default layout ID.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_default_layout() {
	$default_layout = jupiterx_has_widget_area( 'sidebar_primary' ) ? 'c_sp' : 'c';

	/**
	 * Filter the default layout ID.
	 *
	 * The default layout ID is set to "c_sp" (content + sidebar primary). If the sidebar primary is unregistered, then it is set to "c" (content only).
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The default layout ID.
	 */
	return apply_filters( 'jupiterx_default_layout', $default_layout );
}

/**
 * Get the current web page's layout ID.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_layout() {
	$post_type = get_post_type();

	if ( is_singular() ) {
		$layout = jupiterx_get_field( 'jupiterx_layout' );

		if ( 'global' === $layout && is_singular( $post_type ) ) {
			$layout = get_theme_mod( "jupiterx_{$post_type}_single_layout" );
		}
	} elseif ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( 0 !== $posts_page ) {
			$layout = jupiterx_get_field( 'jupiterx_layout', false, $posts_page );
		}
	}

	// This should be after is_archive check.
	if ( is_category() || is_tag() || is_tax() ) {
		$layout = jupiterx_get_field( 'jupiterx_layout', false, get_queried_object() );
	}

	// When the layout is not found or is set to "default_fallback", use the theme's default layout.
	if ( ! isset( $layout ) || ! $layout || 'default_fallback' === $layout ) {
		$layout = get_theme_mod( 'jupiterx_layout', jupiterx_get_default_layout() );
	}

	/**
	 * Filter the web page's layout ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The layout ID.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	return apply_filters( 'jupiterx_layout', $layout );
}

/**
 * Get the current web page's layout class.
 *
 * This function generates the layout class(es) based on the current layout.
 *
 * @since 1.0.0
 *
 * @param string $id The searched layout section ID.
 *
 * @return bool Layout class, false if no layout class found.
 */
function jupiterx_get_layout_class( $id ) {
	/**
	 * Filter the arguments used to define the layout grid.
	 *
	 * The content number of columns are automatically calculated based on the grid, sidebar primary and
	 * sidebar secondary columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args              {
	 *                                 An array of arguments.
	 *
	 * @type int    $grid              Total number of columns the grid contains. Default 4.
	 * @type int    $sidebar_primary   The number of columns the sidebar primary takes. Default 1.
	 * @type int    $sidebar_secondary The number of columns the sidebar secondary takes. Default 1.
	 * @type string $breakpoint        The UIkit grid breakpoint which may be set to 'small', 'medium' or 'large'. Default 'medium'.
	 * }
	 */
	$args = apply_filters( 'jupiterx_layout_grid_settings', array(
		'grid'              => 12,
		'sidebar_primary'   => 3,
		'sidebar_secondary' => 3,
		'breakpoint'        => 'lg',
	) );

	/**
	 * Filter the layout class.
	 *
	 * The dynamic portion of the hook name refers to the searched layout section ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The layout class.
	 */
	return apply_filters( "jupiterx_layout_class_{$id}", jupiterx_get( $id, _jupiterx_get_layout_classes( $args ) ) );
}

/**
 * Get the layout's class attribute values.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @param array $args Grid configuration.
 *
 * @return array
 */
function _jupiterx_get_layout_classes( array $args ) {
	$grid       = jupiterx_get( 'grid', $args );
	$c          = $grid; // $c stands for "content".
	$sp         = jupiterx_get( 'sidebar_primary', $args );
	$ss         = jupiterx_get( 'sidebar_secondary', $args );
	$breakpoint = jupiterx_get( 'breakpoint', $args, 'lg' );
	$prefix     = 'col-' . $breakpoint;

	$classes = array(
		'content' => "{$prefix}-{$c}",
	);

	if ( ! jupiterx_has_widget_area( 'sidebar_primary' ) ) {
		return $classes;
	}

	$layout        = jupiterx_get_layout();
	$has_secondary = jupiterx_has_widget_area( 'sidebar_secondary' );
	$c             = $has_secondary && strlen( trim( $layout ) ) > 4 ? $grid - ( $sp + $ss ) : $grid - $sp;

	switch ( $layout ) {

		case 'c_sp':
		case 'c_sp_ss':
			$classes['content']         = "{$prefix}-{$c}";
			$classes['sidebar_primary'] = "{$prefix}-{$sp}";

			if ( $has_secondary && 'c_sp_ss' === $layout ) {
				$classes['sidebar_secondary'] = "{$prefix}-{$ss}";
			}
			break;

		case 'sp_c':
		case 'sp_c_ss':
			$classes['content']         = "{$prefix}-{$c}";
			$classes['sidebar_primary'] = "{$prefix}-{$sp} order-{$breakpoint}-first";

			if ( $has_secondary && 'sp_c_ss' === $layout ) {
				$classes['sidebar_secondary'] = "{$prefix}-{$ss} order-{$breakpoint}-last";
			}
			break;

		case 'c_ss':
			// If we don't have a secondary sidebar, bail out.
			if ( ! $has_secondary ) {
				return $classes;
			}

			$classes['content']           = "{$prefix}-{$c}";
			$classes['sidebar_secondary'] = "{$prefix}-{$ss}";
			break;

		case 'ss_c':
			// If we don't have a secondary sidebar, bail out.
			if ( ! $has_secondary ) {
				return $classes;
			}

			$classes['content']           = "{$prefix}-{$c} order-{$breakpoint}-last";
			$classes['sidebar_secondary'] = "{$prefix}-{$ss}";
			break;

		case 'sp_ss_c':
			$push_content               = $has_secondary ? $sp + $ss : $sp;
			$classes['content']         = "{$prefix}-{$c} order-{$breakpoint}-last";
			$classes['sidebar_primary'] = "{$prefix}-{$sp}";

			if ( $has_secondary ) {
				$classes['sidebar_secondary'] = "{$prefix}-{$ss}";
			}

			break;
	}

	return $classes;
}

/**
 * Generate layout elements used by Jupiter 'imageradio' option type.
 *
 * Added layout should contain a unique ID as the array key and a URL path to its related image
 * as the array value.
 *
 * @since 1.0.0
 *
 * @param bool $add_default Optional. Whether the 'default_fallback' element is added or not.
 *
 * @return array Layouts ready for Jupiter 'imageradio' option type.
 */
function jupiterx_get_layouts_for_options( $add_default = false ) {
	$base    = JUPITERX_ADMIN_ASSETS_URL . 'images/layouts/';
	$layouts = array(
		'c' => $base . 'c.png',
	);

	// Add sidebar primary layouts if the primary widget area is registered.
	$has_primary = jupiterx_has_widget_area( 'sidebar_primary' );

	if ( $has_primary ) {
		$layouts['c_sp'] = $base . 'cs.png';
		$layouts['sp_c'] = $base . 'sc.png';
	}

	// Add sidebar secondary layouts if the primary and secondary widget area are registered.
	if ( $has_primary && jupiterx_has_widget_area( 'sidebar_secondary' ) ) {
		$layouts['c_sp_ss'] = $base . 'css.png';
		$layouts['sp_ss_c'] = $base . 'ssc.png';
		$layouts['sp_c_ss'] = $base . 'scs.png';
	}

	/**
	 * Filter the layouts.
	 *
	 * - $c stands for content.
	 * - $sp stands for sidebar primary.
	 * - $ss stands for 'sidebar secondary.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args An array of layouts.
	 */
	$layouts = apply_filters( 'jupiterx_layouts', $layouts );

	if ( ! $add_default ) {
		return $layouts;
	}

	$layouts = array_merge( array(
		'default_fallback' => sprintf(
			// translators: The (%s) placeholder is for the "Modify" hyperlink.
			__( 'Use Default Layout (%s)', 'jupiterx' ),
			'<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=jupiterx_layout' ) ) . '">' . _x( 'Modify', 'Default layout', 'jupiterx' ) . '</a>'
		),
	), $layouts );

	return $layouts;
}
