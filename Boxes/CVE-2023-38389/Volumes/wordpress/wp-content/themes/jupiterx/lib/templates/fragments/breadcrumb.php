<?php
/**
 * Echo breadcrumb fragment.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_main_header_content', 'jupiterx_breadcrumb', 15 );
/**
 * Echo the breadcrumb.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_breadcrumb() {
	if ( is_home() || is_front_page() ) {
		return;
	}

	/**
	 * Add support for Breadcrumb NavXT plugin.
	 *
	 * @since 1.0.0
	 */
	if ( function_exists( 'bcn_display' ) ) {
		jupiterx_open_markup_e( 'jupiterx_breadcrumb', 'div', [
			'class'  => 'breadcrumb',
			'typeof' => 'BreadcrumbList',
			'vocab'  => 'https://schema.org/',
		] );

			bcn_display();

		jupiterx_close_markup_e( 'jupiterx_breadcrumb', 'div' );

		return;
	}

	wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query -- Ensure the main query has been reset to the original main query.

	global $post;

	$post_type                 = get_post_type();
	$breadcrumbs               = array();
	$breadcrumbs[ home_url() ] = __( 'Home', 'jupiterx' );

	// Custom post type.
	if ( ! in_array( $post_type, array( 'page', 'attachment', 'post' ), true ) && ! is_404() && ! is_author() ) {

		$post_type_object       = get_post_type_object( $post_type );
		$post_type_archive_link = get_post_type_archive_link( $post_type );

		if ( $post_type_object && $post_type_archive_link ) {
			$breadcrumbs[ $post_type_archive_link ] = $post_type_object->labels->name;
		}
	}

	// Single posts.
	if ( is_single() && 'post' === $post_type ) {

		$categories = get_the_category( $post->ID );

		foreach ( array_slice( $categories, 0, 2 ) as $category ) {
			$breadcrumbs[ get_category_link( $category->term_id ) ] = $category->name;
		}

		if ( count( $categories ) > 2 ) {
			$breadcrumbs['#'] = '...';
		}

		$breadcrumbs[] = get_the_title();
	} elseif ( is_singular() && ! is_home() && ! is_front_page() ) { // Pages/custom post type.
		$current_page = array( $post );

		// Get the parent pages of the current page if they exist.
		if ( isset( $current_page[0]->post_parent ) ) {
			while ( $current_page[0]->post_parent ) {
				array_unshift( $current_page, get_post( $current_page[0]->post_parent ) );
			}
		}

		// Add returned pages to breadcrumbs.
		foreach ( $current_page as $page ) {
			$breadcrumbs[ get_page_link( $page->ID ) ] = $page->post_title;
		}
	} elseif ( is_category() ) { // Categories.
		$breadcrumbs[] = single_cat_title( '', false );
	} elseif ( is_tax() ) { // Taxonomies.
		$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

		$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

		foreach ( $ancestors as $ancestor ) {

			$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

			$breadcrumbs[ get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) ] = $ancestor->name;
		}

		$breadcrumbs[] = $current_term->name;
	} elseif ( is_search() ) { // Searches.
		$breadcrumbs[] = __( 'Search results for:', 'jupiterx' ) . ' ' . get_search_query();
	} elseif ( is_author() ) { // Author archives.
		$author        = get_queried_object();
		$breadcrumbs[] = __( 'Author Archives:', 'jupiterx' ) . ' ' . $author->display_name;
	} elseif ( is_tag() ) {// Tag archives.
		$breadcrumbs[] = __( 'Tag Archives:', 'jupiterx' ) . ' ' . single_tag_title( '', false );
	} elseif ( is_date() ) { // Date archives.
		$breadcrumbs[] = __( 'Archives:', 'jupiterx' ) . ' ' . get_the_time( 'F Y' );
	} elseif ( is_404() ) { // 404.
		$breadcrumbs[] = __( '404', 'jupiterx' );
	}

	/**
	 * Filter the breadcrumb.
	 *
	 * @since 1.0.0
	 *
	 * @param array $breadcrumbs Breadcrumb items.
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	$breadcrumbs = apply_filters( 'jupiterx_breadcrumb', $breadcrumbs );

	// Open breadcrumb.
	jupiterx_open_markup_e( 'jupiterx_breadcrumb', 'ol', [
		'class' => 'breadcrumb',
		'itemscope' => 'itemscope',
		'itemtype' => 'http://schema.org/BreadcrumbList',
	] );

	$i = 0;

	foreach ( $breadcrumbs as $breadcrumb_url => $breadcrumb ) {

		// Breadcrumb items.
		if ( count( $breadcrumbs ) - 1 !== $i ) {
			jupiterx_open_markup_e( 'jupiterx_breadcrumb_item', 'li', [
				'class' => 'breadcrumb-item',
				'itemprop' => 'itemListElement',
				'itemtype' => 'http://schema.org/ListItem',
				'itemscope' => 'itemscope',
			] );

				jupiterx_open_markup_e( 'jupiterx_breadcrumb_item_link', 'a', [
					'href' => esc_url( $breadcrumb_url ),
					'itemprop' => 'item',
				] ); // Automatically escaped.

					// Used for mobile devices.
					jupiterx_open_markup_e( 'jupiterx_breadcrumb_item_link_inner', 'span', [ 'itemprop' => 'name' ] );

						jupiterx_output_e( 'jupiterx_breadcrumb_item_text', $breadcrumb );

					jupiterx_close_markup_e( 'jupiterx_breadcrumb_item_link_inner', 'span' );

				jupiterx_close_markup_e( 'jupiterx_breadcrumb_item_link', 'a' );

				jupiterx_selfclose_markup_e( 'jupiterx_breadcrumb_item_position_meta', 'meta', [
					'itemprop' => 'position',
					'content' => $i + 1,
				] );

			jupiterx_close_markup_e( 'jupiterx_breadcrumb_item', 'li' );
		} else { // Active.
			jupiterx_open_markup_e( 'jupiterx_breadcrumb_item[_active]', 'li', [
				'class' => 'breadcrumb-item active',
				'aria-current' => 'page',
				'itemprop' => 'itemListElement',
				'itemtype' => 'http://schema.org/ListItem',
				'itemscope' => 'itemscope',
			] );

				jupiterx_open_markup_e( 'jupiterx_breadcrumb_item_text_wrap', 'span', [ 'itemprop' => 'name' ] );

					jupiterx_output_e( 'jupiterx_breadcrumb_item[_active]_text', $breadcrumb );

				jupiterx_close_markup_e( 'jupiterx_breadcrumb_item_text_wrap', 'span' );

				jupiterx_selfclose_markup_e( 'jupiterx_breadcrumb_item_position_meta', 'meta', [
					'itemprop' => 'position',
					'content' => $i + 1,
				] );

			jupiterx_close_markup_e( 'jupiterx_breadcrumb_item[_active]', 'li' );
		}

		$i++;
	}

	// Close breadcrumb.
	jupiterx_close_markup_e( 'jupiterx_breadcrumb', 'ol' );
}
