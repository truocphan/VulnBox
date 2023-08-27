<?php
/**
 * This file is responsible for Woocommerce Paginagion/Load More
 *
 * @package JupiterX_Core\Woocommerce
 *
 * @since 1.9.0
 */

/**
 * Add Load More Button.
 *
 * @since 1.9.0
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 *
 * @return void
 */
function jupiterx_add_load_more() {
	if ( class_exists( 'WC_Query' ) && method_exists( 'WC_Query', 'product_query' ) && function_exists( 'wc_get_loop_prop' ) ) {
		$total    = wc_get_loop_prop( 'total' );
		$per_page = wc_get_loop_prop( 'per_page' );
		$paged    = wc_get_loop_prop( 'current_page' );
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $per_page * $paged );
	} else {
		global $wp_query;

		$paged    = max( 1, $wp_query->get( 'paged' ) );
		$per_page = $wp_query->get( 'posts_per_page' );
		$total    = $wp_query->found_posts;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );
	}

	if ( $paged && $last < $total ) {
		echo '<div class="jupiterx-wc-loadmore-wrapper"><a class="button btn-info jupiterx-wc-load-more">' . esc_html__( 'Load More', 'jupiterx-core' ) . '</a></div>';
	}
}


/**
 * Add Load More Button and enqueue required script.
 *
 * @since 1.9.0
 *
 * @return void
 */
function jupiterx_wc_load_more() {

	global $wp_query;
	wp_register_script( 'jupiterx-wc-loadmore', plugins_url( 'wc-load-more.js', __FILE__ ), [ 'jquery' ], JUPITERX_VERSION, true );
	wp_localize_script( 'jupiterx-wc-loadmore', 'jupiterx_wc_loadmore_params', [
		'ajaxurl'              => admin_url( 'admin-ajax.php' ),
		'action'               => 'jupiterx_loadmore_ajax_handler',
		'posts'                => wp_json_encode( $wp_query->query_vars ),
		'current_page'         => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		'security'             => wp_create_nonce( 'jupiterx_core_woo_load_more' ),
		'max_page'             => $wp_query->max_num_pages,
		'i18n'                 => [
			'btn_text'         => __( 'Load More', 'jupiterx-core' ),
			'btn_text_loading' => __( 'Loading', 'jupiterx-core' ),
		],
	] );

	wp_enqueue_script( 'jupiterx-wc-loadmore' );
}

/**
 * Add ajax handler for the Load More Button.
 *
 * @since 1.9.0
 *
 * @return void
 */
function jupiterx_wc_loadmore_ajax_handler() {
	$query               = sanitize_text_field( $_POST['query'] ); // phpcs:ignore WordPress.Security
	$page                = sanitize_text_field( $_POST['page'] ); // phpcs:ignore WordPress.Security
	$args                = json_decode( stripslashes( $query ), true );
	$order_by            = filter_input( INPUT_POST, 'orderby', FILTER_SANITIZE_STRING );
	$args['paged']       = intval( $page ) + 1; // phpcs:ignore WordPress.Security
	$args['post_status'] = 'publish';

	if ( ! check_ajax_referer( 'jupiterx_core_woo_load_more', 'security' ) ) {
		wp_send_json_error( [ 'message' => __( 'Nonce can\'t be verified', 'jupiterx-core' ) ] );
		wp_die();
	}

	if ( $order_by && in_array( $order_by, [ 'price', 'price-desc', 'rating', 'popularity' ], true ) ) {
		$args['orderby'] = [
			'meta_value_num' => 'desc',
			'ID' => 'desc',
		];
	}

	// phpcs:disable
	switch ( $order_by ) {
		case 'price':
			$args['orderby']['meta_value_num'] = 'asc';
			$args['meta_key']                  = '_price';
			break;
		case 'price-desc':
			$args['meta_key'] = '_price';
			break;
		case 'rating':
			$args['meta_key'] = '_wc_average_rating';
			break;
		case 'popularity':
			$args['meta_key'] = 'total_sales';
			break;
	}
	// phpcs:enable

	query_posts( $args ); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts

	wc_setup_loop();

	$data = [
		'products'     => [],
		'result_count' => '',
	];

	$total    = wc_get_loop_prop( 'total' );
	$per_page = wc_get_loop_prop( 'per_page' );
	$current  = wc_get_loop_prop( 'current_page' );
	$last     = min( $total, $per_page * $current );

	/* translators: 1: first result 2: last result 3: total results */
	$data['result_count'] = sprintf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), 1, $last, $total );

	while ( have_posts() ) :
		the_post();

		ob_start();

		wc_get_template_part( 'content', 'product' );

		$data['products'][] = ob_get_clean();

	endwhile;

	woocommerce_reset_loop();

	wp_send_json_success( $data );
}

add_action( 'wp_ajax_jupiterx_loadmore_ajax_handler', 'jupiterx_wc_loadmore_ajax_handler' );
add_action( 'wp_ajax_nopriv_jupiterx_loadmore_ajax_handler', 'jupiterx_wc_loadmore_ajax_handler' );
