<?php
/**
 * Modify the search page.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

if ( ! is_search() ) {
	return;
}

/**
 * Check if we have results in our search. Including products.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function jupiterx_search_has_result() {
	$args = [
		'post_type' => jupiterx_get_field_mod( 'jupiterx_search_post_types', '', [ 'post', 'page', 'portfolio', 'product' ] ),
		'posts_per_page' => -1,
		's' => get_search_query( false ),
	];

	$posts = get_posts( $args );

	if ( ! $posts ) {
		return false;
	}

	return true;
}

jupiterx_add_smart_action( 'jupiterx_head', 'jupiterx_modify_search_page' );
/**
 * Remove no posts found template and fix body classes.
 *
 * @since   1.0.0
 *
 * @return void
 */
function jupiterx_modify_search_page() {
	if ( ! jupiterx_search_has_result() ) {
		return;
	}

	jupiterx_remove_action( 'jupiterx_no_post' );
	jupiterx_replace_attribute( 'jupiterx_body', 'class', 'search-no-results', 'search-results' );
}

jupiterx_add_smart_action( 'jupiterx_content_before_markup', 'jupiterx_search_page_search' );
/**
 * Echo search page search section.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_search_page_search() {
	if ( ! jupiterx_search_has_result() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_new_search', 'article', [ 'class' => 'jupiterx-new-search' ] );

		jupiterx_open_markup_e( 'jupiterx_new_search_header', 'header' );

			jupiterx_open_markup_e( 'jupiterx_new_search_title', 'h5', array( 'class' => 'jupiterx-title' ) );

				jupiterx_output_e( 'jupiterx_new_search_title_text', __( 'New search', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_new_search_title', 'h5' );

		jupiterx_close_markup_e( 'jupiterx_new_search_header', 'header' );

		jupiterx_open_markup_e( 'jupiterx_new_search_content', 'div' );

			jupiterx_open_markup_e( 'jupiterx_new_search_subtitle', 'p' );

				jupiterx_output_e( 'jupiterx_new_search_subtitle_text', __( 'If you are not happy with the below results, you may try another search.', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_new_search_subtitle', 'p' );

			jupiterx_output_e( 'jupiterx_new_search_form', get_search_form( false ) );

		jupiterx_close_markup_e( 'jupiterx_new_search_content', 'div' );

	jupiterx_close_markup_e( 'jupiterx_new_search', 'article' );
}

jupiterx_add_smart_action( 'jupiterx_content_before_markup', 'jupiterx_search_page_secondary_title' );
/**
 * Echo search page secondary title section.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_search_page_secondary_title() {
	if ( ! jupiterx_search_has_result() ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_search_secondary_title', 'h3', [ 'class' => 'jupiterx-search-secondary-title' ] );

		printf( '%1$s%2$s', jupiterx_output( 'jupiterx_search_secondary_title_text', esc_html__( 'Search results for: ', 'jupiterx' ) ), get_search_query() ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

	jupiterx_close_markup_e( 'jupiterx_search_secondary_title', 'h3' );

	jupiterx_open_markup_e( 'jupiterx_search_secondary_subtitle', 'p', [ 'class' => 'jupiterx-search-secondary-subtitle' ] );

		// translators: Number of found search posts.
		printf( jupiterx_output( 'jupiterx_search_secondary_title_text', __( 'We have found some results with the word you searched.', 'jupiterx' ) ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

	jupiterx_close_markup_e( 'jupiterx_search_secondary_subtitle', 'p' );
}

add_action( 'jupiterx_main_grid_before_markup', 'jupiterx_search_post_loop' );
/**
 * Echo search page loop.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_search_post_loop() {
	if ( ! jupiterx_search_has_result() ) {

		jupiterx_add_filter( 'jupiterx_layout', function() {
			return 'c';
		} );

		return;
	}

	jupiterx_remove_action( 'jupiterx_post_tags' );
	jupiterx_remove_markup( 'jupiterx_post_header' );

	jupiterx_add_attribute( 'jupiterx_post', 'class', 'row' );
	jupiterx_add_attribute( 'jupiterx_post_image', 'class', 'col-md-4' );
	jupiterx_add_attribute( 'jupiterx_post_body', 'class', 'col' );
	jupiterx_add_attribute( 'jupiterx_post_title', 'class', 'jupiterx-search-post-title' );

	jupiterx_replace_action_hook( 'jupiterx_post_image', 'jupiterx_post_prepend_markup' );
	jupiterx_replace_action_hook( 'jupiterx_post_title', 'jupiterx_post_body_prepend_markup' );
	jupiterx_replace_action_hook( 'jupiterx_post_meta', 'jupiterx_post_body_prepend_markup' );
}

add_action( 'jupiterx_content_prepend_markup', 'jupiterx_search_product_loop' );
/**
 * Echo search page loop.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_search_product_loop() {
	if ( ! in_array( 'product', jupiterx_get_field_mod( 'jupiterx_search_post_types', '', [ 'product' ] ), true ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$args = [
		'post_type'      => 'product',
		'posts_per_page' => -1,
		's'              => get_search_query( false ),
	];

	$wp_query = new WP_Query( $args );

	if ( empty( $wp_query->found_posts ) ) {
		return;
	}

	global $woocommerce_loop;

	$woocommerce_loop['columns'] = 4;

	if ( $wp_query->have_posts() ) {

		jupiterx_open_markup_e( 'jupiterx_search_woocommerce', 'div', 'class=woocommerce' );

			jupiterx_open_markup_e( 'jupiterx_search_products', 'ul', 'class=products columns-4' );

				while ( $wp_query->have_posts() ) { // phpcs:ignore

					$wp_query->the_post();

					wc_get_template_part( 'content', 'product' );

				} // phpcs:ignore

				wp_reset_postdata();

			jupiterx_close_markup_e( 'jupiterx_search_products', 'ul', 'class=products' );

		jupiterx_close_markup_e( 'jupiterx_search_woocommerce', 'div' );

	}

	wp_reset_postdata();
}
