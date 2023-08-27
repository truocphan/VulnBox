<?php
/**
 * The Jupiter WooCommerce product list integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

add_filter( 'woocommerce_before_shop_loop_item', 'jupiterx_wc_loop_item_before', 0 );
/**
 * Prepend a markup in product item.
 *
 * @since 1.0.0
 */
function jupiterx_wc_loop_item_before() {
	global $product;

	$quick_view_class = jupiterx_wc_is_product_quick_view_active() ? 'jupiterx-product-has-quick-view' : '';

	// phpcs:ignore WordPress.Security
	echo wp_kses( '<div class="jupiterx-product-container ' . esc_attr( $quick_view_class ) . '" data-product-id="' . $product->get_id() . '">', [
		'div' => [
			'class' => [],
			'data-product-id' => [],
		],
	] );

	// Prepare sale badge.
	if ( get_theme_mod( 'jupiterx_product_list_custom_sale_badge', true ) ) {
		add_filter( 'woocommerce_sale_flash', 'jupiterx_wc_product_page_custom_sale_badge' );
	}
}

add_filter( 'woocommerce_after_shop_loop_item', 'jupiterx_wc_loop_item_after', 999 );
/**
 * Append a closing markup in product item.
 *
 * @since 1.0.0
 */
function jupiterx_wc_loop_item_after() {
	echo wp_kses( '</div>', [ 'div' => [] ] );
}

if ( get_theme_mod( 'jupiterx_product_list_custom_out_of_stock_badge', true ) ) {
	add_action( 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_out_of_stock', 15 );
}
/**
 * Add out of stack badge to shop loop item.
 *
 * @since 1.0.0
 */
function jupiterx_wc_template_loop_out_of_stock() {
	global $product;

	if ( ! $product->is_in_stock() ) {
		echo wp_kses( '<span class="jupiterx-out-of-stock">' . esc_html__( 'Out of Stock', 'jupiterx' ) . '</span>', [
			'span' => [
				'class' => [],
			],
		] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Add categories to shop loop item.
 *
 * @since 1.0.0
 */
function jupiterx_wc_template_loop_item_category() {
	global $product;

	$terms = get_the_terms( $product->get_id(), 'product_cat' );

	if ( ! is_array( $terms ) ) {
		return;
	}

	$categories = [];

	foreach ( $terms as $term ) {
		$categories[] = $term->name;
	}

	echo wp_kses( '<span class="posted_in">' . join( ', ', $categories ) . '</span>', [
		'span' => [
			'class' => [],
		],
	] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'woocommerce_shop_loop_item_title', 'jupiterx_wc_template_loop_product_title', 10 );
/**
 * Add product title with custom functionality.
 *
 * @since 1.0.0
 */
function jupiterx_wc_template_loop_product_title() {
	$title_tag = get_theme_mod( 'jupiterx_product_list_title_tag', 'h2' );

	echo wp_kses(
		sprintf(
			'<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>',
			esc_attr( $title_tag ),
			get_the_title() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		),
		[
			'h1' => [ 'class' => [] ],
			'h2' => [ 'class' => [] ],
			'h3' => [ 'class' => [] ],
			'h4' => [ 'class' => [] ],
			'h5' => [ 'class' => [] ],
			'h6' => [ 'class' => [] ],
			'div' => [ 'class' => [] ],
			'span' => [ 'class' => [] ],
			'p' => [ 'class' => [] ],
		]
	);
}

/**
 * Grouped actions for product title.
 *
 * @since 1.0.0
 */
function jupiterx_wc_template_loop_product_title_group() {
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked jupiterx_wc_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	do_action( 'woocommerce_after_shop_loop_item_title' );
}

add_filter( 'woocommerce_loop_add_to_cart_args', 'jupiterx_wc_loop_add_to_cart_args', 10 );
/**
 * Add arguments to add to cart button.
 *
 * @since 1.0.0
 *
 * @param array $args Button arguments.
 *
 * @return array
 */
function jupiterx_wc_loop_add_to_cart_args( $args ) {
	$args['class'] .= ' jupiterx-icon-shopping-cart-6';

	return $args;
}

/**
 * Insert Quick View Modal.
 *
 * @since 1.11.0
 *
 * @return void
 */
function jupiterx_wc_loop_item_after_quick_view() {
	global $product, $woocommerce_loop;

	// Preserve wc loop args.
	$woocommerce_loop_copy = $woocommerce_loop;

	$quick_view_content = get_theme_mod('jupiterx_product_list_quick_view_content', [
		'description',
		'meta_information',
		'social_icons',
		'reviews',
	] );

	$quick_view_title_tag = get_theme_mod( 'jupiterx_product_list_quick_view_title_tag', 'h1' );
	?>
	<div
		id="jupiterx-product-quick-view-modal-<?php echo esc_attr( $product->get_id() ); ?>"
		class="jupiterx-product-quick-view-modal single-product">
		<div class="woocommerce">
			<div id="product-<?php the_ID(); ?>" class="product">
				<div class="jupiterx-product-quick-view-modal-image">
					<?php echo wp_kses_post( $product->get_image( 'full' ) ); ?>
				</div>
				<div class="jupiterx-product-quick-view-modal-content">
					<div class="summary entry-summary">
						<?php
							echo wp_kses(
								sprintf(
									'<%1$s class="product_title entry-title">%2$s</%1$s>',
									esc_attr( $quick_view_title_tag ),
									get_the_title() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								),
								[
									'h1' => [ 'class' => [] ],
									'h2' => [ 'class' => [] ],
									'h3' => [ 'class' => [] ],
									'h4' => [ 'class' => [] ],
									'h5' => [ 'class' => [] ],
									'h6' => [ 'class' => [] ],
									'div' => [ 'class' => [] ],
									'span' => [ 'class' => [] ],
									'p' => [ 'class' => [] ],
								]
							);

							woocommerce_template_single_price();

							if ( in_array( 'meta_information', $quick_view_content, true ) ) {
								woocommerce_template_single_meta();
							}

							if ( in_array( 'description', $quick_view_content, true ) ) {
								woocommerce_template_single_excerpt();
							}

							woocommerce_template_single_add_to_cart();

							if ( in_array( 'social_icons', $quick_view_content, true ) ) {
								jupiterx_load_fragment_file( 'post-shortcodes' );

								jupiterx_wc_product_page_social_share();
							}
						?>
					</div>
					<?php
						if ( in_array( 'reviews', $quick_view_content, true ) ) {
							global $withcomments;

							// phpcs:ignore WordPress.WP
							$withcomments = 1;

							comments_template();
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	// Restore wc loop args.
	wc_setup_loop( $woocommerce_loop_copy );
}

/**
 * Show Quick View Button.
 *
 * @since 1.11.0
 *
 * @return void
 */
function jupiterx_wc_loop_item_after_quick_view_btn() {
	global $product;

	$elements = get_theme_mod( 'jupiterx_product_list_elements', [ 'add_to_cart' ] );

	if ( in_array( 'add_to_cart', $elements, true ) ) {
		return;
	}

	?>
	<div class="jupiterx-product-quick-view-btn-wrap">
		<button class="button jupiterx-icon-eye-regular jupiterx-product-quick-view-btn"><?php esc_html_e( 'Quick view', 'jupiterx' ); ?></button>
	</div>
	<?php
}

/**
 * Show add_to_cart quick view button.
 *
 * @since 1.11.0
 *
 * @param string $button Add to cart markup.
 *
 * @return string
 */
function jupiterx_wc_after_add_to_cart_quick_view_btn( $button ) {
	ob_start();
	?>
	<div class="jupiterx-product-quick-view-btn-wrap">
		<?php echo wp_kses_post( $button ); // phpcs:ignore WordPress.Security ?>
		<button class="button jupiterx-icon-eye-regular jupiterx-product-quick-view-btn"><?php esc_html_e( 'Quick view', 'jupiterx' ); ?></button>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Show thumnbail quick view button.
 *
 * @since 1.11.0
 *
 * @return void
 */
function jupiterx_wc_thumbnail_quick_view_btn() {
	?>
	<button class="jupiterx-icon-eye-regular jupiterx-product-quick-view-btn"></button>
	<?php
}

/**
 * Replace default WooCommerce product image in shop loop.
 *
 * It adds extra markup to let object fit polyfill work on IE.
 *
 * @since 1.3.0
 */
function jupiterx_wc_loop_product_thumbnail() {
	global $product;

	$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );

	jupiterx_open_markup_e( 'jupiterx_wc_loop_product_image', 'div', 'class=jupiterx-wc-loop-product-image' );

		if ( $product ) {
			echo wp_kses_post( $product->get_image( $image_size ) );
		}

	jupiterx_close_markup_e( 'jupiterx_wc_loop_product_image', 'div' );
}

/**
 * Enable or disable loop pagination.
 *
 * @since 1.0.0
 */
function jupiterx_wc_loop_pagination_enabled() {

	if ( in_array( get_theme_mod( 'jupiterx_product_list_pagination', 'pagination' ), [ 'none', 'loadmore' ], true ) ) {
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	}

	if ( 'loadmore' === get_theme_mod( 'jupiterx_product_list_pagination' ) && function_exists( 'jupiterx_add_load_more' ) ) {
		add_action( 'woocommerce_after_shop_loop', 'jupiterx_add_load_more', 30 );
		add_action( 'wp_enqueue_scripts', 'jupiterx_wc_load_more' );
	}
}

/**
 * Sort elements.
 *
 * @since 1.4.0
 */
function jupiterx_wc_loop_sort_elements() {
	$elements = get_theme_mod( 'jupiterx_product_list_sort_elements' );

	$actions = [
		'category'      => 'jupiterx_wc_template_loop_item_category',
		'name'          => 'jupiterx_wc_template_loop_product_title_group',
		'rating'        => 'woocommerce_template_loop_rating',
		'regular_price' => 'woocommerce_template_loop_price',
	];

	if ( empty( $elements ) ) {
		$elements = array_keys( $actions );
	}

	$priority = 25;

	foreach ( $elements as $element ) {
		add_action( 'woocommerce_before_shop_loop_item', $actions[ $element ], $priority );
		$priority = $priority + 5;
	}
}

/**
 * Remove default loop content product actions.
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

/**
 * Apply actions for loop content products.
 *
 * @since 1.0.0
 */
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 15 );
add_action( 'woocommerce_before_shop_loop_item', 'jupiterx_wc_loop_product_thumbnail', 20 );

/**
 * Enable or disable page elements.
 *
 * @since 1.0.0
 */
jupiterx_wc_loop_pagination_enabled();
jupiterx_wc_loop_sort_elements();
jupiterx_wc_product_quick_view();
