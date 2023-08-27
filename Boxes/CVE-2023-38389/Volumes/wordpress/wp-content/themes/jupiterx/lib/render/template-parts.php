<?php
/**
 * Loads the Jupiter template parts.
 *
 * The template parts contain the structural markup and hooks to which the fragments are attached.
 *
 * @package JupiterX\Framework\Render
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_load_document', 'jupiterx_header_template', 5 );
/**
 * Echo header template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_header_template() {
	get_header();
}

jupiterx_add_smart_action( 'jupiterx_site_prepend_markup', 'jupiterx_header_partial_template' );
/**
 * Echo header partial template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_header_partial_template() {

	if ( ! jupiterx_get_field( 'jupiterx_header', true ) ) {
		return;
	}

	// Allow overwrite.
	if ( '' !== locate_template( 'header-partial.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'header-partial.php';
}

jupiterx_add_smart_action( 'jupiterx_main_prepend_markup', 'jupiterx_main_header_partial_template' );
/**
 * Echo main header partial template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_main_header_partial_template() {

	// Allow overwrite.
	if ( '' !== locate_template( 'main-header-partial.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'main-header-partial.php';
}

jupiterx_add_smart_action( 'jupiterx_load_document', 'jupiterx_content_template' );
/**
 * Echo main content template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_content_template() {

	// Allow overwrite.
	if ( '' !== locate_template( 'content.php', true ) ) {
		return;
	}

	require_once JUPITERX_STRUCTURE_PATH . 'content.php';
}

jupiterx_add_smart_action( 'jupiterx_main_append_markup', 'jupiterx_main_footer_partial_template' );
/**
 * Echo main footer partial template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_main_footer_partial_template() {

	// Allow overwrite.
	if ( '' !== locate_template( 'main-footer-partial.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'main-footer-partial.php';
}

jupiterx_add_smart_action( 'jupiterx_content', 'jupiterx_loop_template' );
/**
 * Echo loop template part.
 *
 * @since 1.0.0
 *
 * @param string $id Optional. The loop ID is used to filter the loop WP_Query arguments.
 *
 * @return void
 */
function jupiterx_loop_template( $id = false ) {

	// Set default loop id.
	if ( ! $id ) {
		$id = 'main';
	}

	// Only run new query if a filter is set.
	$_has_filter = jupiterx_has_filters( "jupiterx_loop_query_args[_{$id}]" );

	if ( $_has_filter ) {
		global $wp_query;

		/**
		 * Filter the jupiter loop query. This can be used for custom queries.
		 *
		 * @since 1.0.0
		 */
		$args     = jupiterx_apply_filters( "jupiterx_loop_query_args[_{$id}]", false );
		$wp_query = new WP_Query( $args ); // @codingStandardsIgnoreLine
	}

	// Allow overwrite. Require the default loop.php if no overwrite is found.
	if ( '' === locate_template( 'loop.php', true, false ) ) {
		require JUPITERX_STRUCTURE_PATH . 'loop.php';
	}

	// Only reset the query if a filter is set.
	if ( $_has_filter ) {
		wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query -- Ensure the main query has been reset to the original main query.
	}
}

jupiterx_add_smart_action( 'jupiterx_post_prepend_markup', 'jupiterx_post_header_template' );
/**
 * Echo post header template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_header_template() {
	jupiterx_open_markup_e( 'jupiterx_post_header', 'header', 'class=jupiterx-post-header' );

		/**
		 * Fires in the post header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_post_header' );

	jupiterx_close_markup_e( 'jupiterx_post_header', 'header' );
}

jupiterx_add_smart_action( 'jupiterx_post_after_markup', 'jupiterx_comments_template', 40 );
/**
 * Echo comments template part.
 *
 * The comments template part only loads if comments are active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comments_template() {
	global $post;

	$shortcircuit_conditions = array(
		jupiterx_get( 'ID', $post ) && ! ( comments_open() || get_comments_number() ),
		! post_type_supports( jupiterx_get( 'post_type', $post ), 'comments' ),
	);

	if ( in_array( true, $shortcircuit_conditions, true ) ) {
		return;
	}

	comments_template();
}

jupiterx_add_smart_action( 'jupiterx_comment', 'jupiterx_comment_template' );
/**
 * Echo comment template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_template() {

	// Allow overwrite.
	if ( '' !== locate_template( 'comment.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'comment.php';
}

jupiterx_add_smart_action( 'jupiterx_widget_area', 'jupiterx_widget_area_template' );
/**
 * Echo widget area template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_area_template() {

	// Allow overwrite.
	if ( '' !== locate_template( 'widget-area.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'widget-area.php';
}

jupiterx_add_smart_action( 'jupiterx_primary_after_markup', 'jupiterx_sidebar_primary_template' );
/**
 * Echo primary sidebar template part.
 *
 * The primary sidebar template part only loads if the layout set includes it. This prevents unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_sidebar_primary_template() {

	if ( false === stripos( jupiterx_get_layout(), 'sp' ) || ! jupiterx_has_widget_area( 'sidebar_primary' ) ) {
		return;
	}

	get_sidebar( 'primary' );
}

jupiterx_add_smart_action( 'jupiterx_primary_after_markup', 'jupiterx_sidebar_secondary_template' );
/**
 * Echo secondary sidebar template part.
 *
 * The secondary sidebar template part only loads if the layout set includes it. This prevents unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_sidebar_secondary_template() {

	if ( false === stripos( jupiterx_get_layout(), 'ss' ) || ! jupiterx_has_widget_area( 'sidebar_secondary' ) ) {
		return;
	}

	get_sidebar( 'secondary' );
}

jupiterx_add_smart_action( 'jupiterx_site_append_markup', 'jupiterx_footer_partial_template' );
/**
 * Echo footer partial template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_partial_template() {

	if ( ! jupiterx_get_field( 'jupiterx_footer', true ) ) {
		return;
	}

	// Allow overwrite.
	if ( '' !== locate_template( 'footer-partial.php', true, false ) ) {
		return;
	}

	require JUPITERX_STRUCTURE_PATH . 'footer-partial.php';
}

jupiterx_add_smart_action( 'jupiterx_load_document', 'jupiterx_footer_template' );
/**
 * Echo footer template part.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_footer_template() {
	get_footer();
}

jupiterx_add_smart_action( 'template_redirect', 'jupiterx_full_width_template' );
/**
 * Prepare Full Width template.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_full_width_template() {
	$full_width_templates = [ 'elementor_header_footer', 'full-width.php' ];

	// Theme builder page template.
	$theme_builder_page_template = '';

	if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		$theme_builder_page_template = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->template_include( '' );
	}

	$is_template_header_footer = strpos( $theme_builder_page_template, 'header-footer.php' );
	$is_template_header_footer = apply_filters( 'jupiterx_full_width_prioritize_jupiterx_templates', $is_template_header_footer );

	if ( ( ! is_singular() || ! in_array( get_page_template_slug(), $full_width_templates, true ) ) && ! $is_template_header_footer ) {
		return;
	}

	jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
	jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
}

add_action( 'template_redirect', 'jupiterx_elementor_template_types' );
/**
 * Prepare layout for Elementor template types.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_elementor_template_types() {
	global $post;

	// Template type.
	$template_type = 'page';

	if ( ! empty( $post->ID ) ) {
		$template_type = get_post_meta( $post->ID, '_elementor_template_type', true );
	}

	if ( 'elementor_library' !== get_post_type() || ! in_array( $template_type, [ 'section', 'header', 'footer', 'archive', 'single' ], true ) ) {
		return;
	}

	if ( jupiterx_get( 'jupiterx-layout-builder-preview' ) ) {
		add_filter( 'show_admin_bar', '__return_false' );

		add_action( 'wp_head', function() {
			?>
				<style>
					html {
						margin: 0 !important;
						pointer-events: none !important;
					}

					body {
						overflow-x: hidden !important;
					}
				</style>
			<?php
		} );

		$header_footer = [ 'footer', 'header' ];

		if ( in_array( $template_type, $header_footer, true ) ) {
			jupiterx_remove_action( 'jupiterx_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
			jupiterx_remove_action( 'jupiterx_footer_partial_template' );

			return;
		}

		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

		return;
	}

	if ( class_exists( 'ElementorPro\Plugin' ) ) {
		// Remove header tag in editor/preview.
		if ( 'header' === $template_type ) {
			jupiterx_remove_markup( 'jupiterx_header' );
			return;
		}

		// Remove footer tag in editor/preview.
		if ( 'footer' === $template_type ) {
			jupiterx_remove_markup( 'jupiterx_footer' );
			return;
		}

		// Archive & single type.
		if ( in_array( $template_type, [ 'archive', 'single' ], true ) ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
			return;
		}
	}

	jupiterx_add_filter( 'jupiterx_layout', 'c' );

	if ( ! in_array( $template_type, [ 'archive', 'single' ], true ) ) {
		jupiterx_remove_action( 'jupiterx_loop_template' );
	}

	setup_postdata( get_the_ID() );

	// Section type.
	if ( 'section' === $template_type ) {
		jupiterx_remove_action( 'jupiterx_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
		jupiterx_remove_action( 'jupiterx_footer_partial_template' );
		jupiterx_add_smart_action( 'jupiterx_content', 'jupiterx_post_content' );
	}

	// Header type.
	if ( 'header' === $template_type ) {
		jupiterx_remove_action( 'jupiterx_header_partial_template' );
		jupiterx_add_smart_action( 'jupiterx_main_before_markup', 'jupiterx_post_content' );
	}

	// Footer type.
	if ( 'footer' === $template_type ) {
		jupiterx_remove_action( 'jupiterx_footer_partial_template' );
		jupiterx_add_smart_action( 'jupiterx_main_after_markup', 'jupiterx_post_content' );
	}

	// Archive & single type.
	if ( in_array( $template_type, [ 'archive', 'single' ], true ) ) {
		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
	}

	if ( ! in_array( $template_type, [ 'header', 'footer' ], true ) ) {
		return;
	}

	// Dummy content.
	jupiterx_add_smart_action( 'jupiterx_content', function() {
		$content = '<div class="alert alert-warning" role="alert">' . __( 'The content of this page is intended to provide a realistic way for building header and footer sections for preview purposes.', 'jupiterx' ) . '</div>';

		$content .= __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Viverra justo nec ultrices dui sapien. Massa massa ultricies mi quis hendrerit. Ipsum faucibus vitae aliquet nec ullamcorper sit. Blandit massa enim nec dui nunc mattis enim ut tellus. Velit ut tortor pretium viverra suspendisse potenti. Sem nulla pharetra diam sit amet nisl suscipit adipiscing. Egestas maecenas pharetra convallis posuere morbi. Id eu nisl nunc mi. Neque volutpat ac tincidunt vitae semper. Odio pellentesque diam volutpat commodo sed egestas egestas. Sed viverra ipsum nunc aliquet bibendum enim facilisis gravida neque. Odio eu feugiat pretium nibh ipsum consequat nisl vel pretium.', 'jupiterx' );

		$content .= __( 'Turpis egestas integer eget aliquet nibh praesent tristique magna sit. Feugiat in fermentum posuere urna nec. Eu non diam phasellus vestibulum lorem sed risus ultricies tristique. Vestibulum sed arcu non odio euismod lacinia. Ac odio tempor orci dapibus ultrices in iaculis nunc sed. Massa tincidunt dui ut ornare lectus sit amet est placerat. Consequat id porta nibh venenatis cras sed. Cursus sit amet dictum sit. Turpis egestas pretium aenean pharetra magna ac. Ipsum suspendisse ultrices gravida dictum fusce ut placerat.', 'jupiterx' );

		$content .= __( 'Eget felis eget nunc lobortis mattis aliquam. Etiam dignissim diam quis enim lobortis. Eu feugiat pretium nibh ipsum consequat nisl vel. Ultricies mi eget mauris pharetra et ultrices neque ornare aenean. Enim lobortis scelerisque fermentum dui. Et leo duis ut diam quam nulla porttitor massa. Libero nunc consequat interdum varius sit amet mattis vulputate enim. Facilisis leo vel fringilla est ullamcorper eget nulla facilisi etiam. Vitae turpis massa sed elementum tempus egestas sed sed risus. Id neque aliquam vestibulum morbi blandit cursus risus at. Feugiat nibh sed pulvinar proin gravida hendrerit. Enim neque volutpat ac tincidunt vitae semper quis lectus. In ante metus dictum at tempor commodo ullamcorpe.', 'jupiterx' );

		echo wpautop( $content ); // phpcs:ignore
	} );
}

/**
 * Set the content width based on the Jupiter default layout.
 *
 * This is mainly added to align to WordPress.org requirements.
 *
 * @since 1.0.0
 *
 * @ignore
 * @access private
 */
if ( ! isset( $content_width ) ) {
	$content_width = 800; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Valid use case.
}

/**
 * Load page content in page template.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_fullwidth_template_content() {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
}
