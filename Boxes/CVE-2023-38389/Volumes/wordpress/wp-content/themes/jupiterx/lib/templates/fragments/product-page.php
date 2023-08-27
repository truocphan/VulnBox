<?php
/**
 * The Jupiter WooCommerce product page integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

if ( ! is_product() ) {
	return;
};

jupiterx_add_filter( 'jupiterx_layout', 'c' );

/**
 * Get the current page template.
 *
 * @return string Page template.
 */
function jupiterx_wc_get_product_page_template() {
	return get_theme_mod( 'jupiterx_product_page_template', '1' );
}

/**
 * Get the gallery orientation.
 *
 * @return string Gallery orientation.
 */
function jupiterx_wc_get_product_page_gallery_orientation() {
	return get_theme_mod( 'jupiterx_product_page_image_gallery_orientation', 'horizontal' );
}

/**
 * Get WooCommerce product page settings.
 *
 * @since 1.0.0
 */
$elements = jupiterx_wc_get_product_page_elements();
$template = jupiterx_wc_get_product_page_template();

/**
 * Reorder WooCommerce product page meta.
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 12 );

/**
 * Modify WooCommerce product page sale badge location.
 *
 * @since 1.0.0
 */
if ( get_theme_mod( 'jupiterx_product_page_custom_sale_badge', true ) ) {
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
	add_action( 'jupiterx_product_page_badges', 'woocommerce_show_product_sale_flash', 10 );
	add_filter( 'woocommerce_sale_flash', 'jupiterx_wc_product_page_custom_sale_badge' );
}

add_filter( 'body_class', 'jupiterx_wc_modify_product_page_body_class' );
/**
 * Modify WooCommerce product page body class.
 *
 * @param array $classes The body classes.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_product_page_body_class( $classes ) {
	return array_merge( $classes, [ 'jupiterx-product-template-' . jupiterx_wc_get_product_page_template() ] );
}

add_action( 'woocommerce_single_product_summary', 'jupiterx_product_page_badges', 4 );
/**
 * Add WooCommerce product page badges markup.
 *
 * @since 1.0.0
 *
 * @return mixed The markup.
 */
function jupiterx_product_page_badges() {
	?>
		<div class="jupiterx-product-badges">
			<?php do_action( 'jupiterx_product_page_badges' ); ?>
		</div>
	<?php
}

if ( get_theme_mod( 'jupiterx_product_page_custom_out_of_stock_badge', true ) ) {
	add_action( 'jupiterx_product_page_badges', 'jupiterx_wc_show_product_out_of_stock_flash' );
}
/**
 * Modify WooCommerce product page sale badge location.
 *
 * @since 1.0.0
 */
function jupiterx_wc_show_product_out_of_stock_flash() {
	global $product;
	$elements = jupiterx_wc_get_product_page_elements();

	if ( ! in_array( 'out_of_stock_badge', $elements, true ) ) {
		return;
	}

	if ( ! $product->is_in_stock() || 'variable' === $product->get_type() ) {
		$style = ( 'variable' === $product->get_type() ) ? 'display:none;' : '';
		echo wp_kses( '<span class="jupiterx-out-of-stock" style="' . esc_attr( $style ) . '">' . esc_html__( 'Out of Stock', 'jupiterx' ) . '</span>', [
			'span' => [
				'class' => [],
				'style' => [],
			],
		] );
	}
}

/**
 * Modify WooCommerce product page short description.
 *
 * @since 1.0.0
 */
if ( ! in_array( 'short_description', $elements, true ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
}

/**
 * Modify WooCommerce product page meta.
 *
 * @since 1.23.0
 */
if (
	! in_array( 'sku', $elements, true )
	&& ! in_array( 'categories', $elements, true )
	&& ! in_array( 'tags', $elements, true )
) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 12 );
}

add_filter( 'woocommerce_product_tabs', 'jupiterx_wc_modify_product_page_tabs' );
/**
 * Modify WooCommerce product page tabs.
 *
 * @param array $tabs The tabs.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_product_page_tabs( $tabs ) {
	$elements = jupiterx_wc_get_product_page_elements();

	if ( ! in_array( 'description_tab', $elements, true ) ) {
		unset( $tabs['description'] );
	}

	if ( ! in_array( 'review_tab', $elements, true ) ) {
		unset( $tabs['reviews'] );
	}

	if ( ! in_array( 'additional_info_tab', $elements, true ) ) {
		unset( $tabs['additional_information'] );
	}

	return $tabs;
}

/**
 * Modify WooCommerce product page sale badge.
 *
 * @since 1.0.0
 */
if ( ! in_array( 'sale_badge', $elements, true ) ) {
	remove_action( 'jupiterx_product_page_badges', 'woocommerce_show_product_sale_flash' );
}

/**
 * Modify WooCommerce product page rating.
 *
 * @since 1.0.0
 */
if ( ! in_array( 'rating', $elements, true ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
}

/**
 * Modify WooCommerce product page image lightbox.
 *
 * @since 1.0.0
 */
if ( get_theme_mod( 'jupiterx_product_page_image_lightbox', true ) ) {
	add_theme_support( 'wc-product-gallery-lightbox' );
}

/**
 * Modify WooCommerce product page image zoom.
 *
 * @since 1.0.0
 */
if ( ! get_theme_mod( 'jupiterx_product_page_image_zoom', true ) ) {
	remove_theme_support( 'wc-product-gallery-zoom' );
}

/**
 * Modify WooCommerce product page stretch to full width.
 *
 * @since 1.0.0
 */

if ( get_theme_mod( 'jupiterx_product_page_full_width', false ) ) {
	if ( in_array( $template, [ '1', '3', '5', '7', '9' ], true ) ) {
		jupiterx_replace_attribute( 'jupiterx_fixed_wrap[_main_content]', 'class', 'container', 'container-fluid' );
	}
}

/**
 * Modify WooCommerce product page related products.
 *
 * @since 1.0.0
 */
if (
	! get_theme_mod(
		'jupiterx_product_page_enable_related_products',
		is_numeric( get_theme_mod( 'jupiterx_product_page_related_products', 4 ) )
	) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

/**
 * Modify WooCommerce product page upsells products.
 *
 * @since 1.0.0
 */
if ( ! get_theme_mod( 'jupiterx_product_page_upsells_products', true ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
}

/**
 * Enable WooCommerce product page sticky info.
 *
 * @since 1.0.0
 */
if ( get_theme_mod( 'jupiterx_product_page_sticky_product_info', false ) && in_array( $template, [ '9', '10' ], true ) ) {
	jupiterx_add_attribute( 'jupiterx_body', 'class', 'jupiterx-product-sticky-info' );
}

/**
 * Add WooCommerce product page accordions.
 *
 * @since 1.0.0
 */
function jupiterx_wc_add_product_page_accordions() {
	wc_get_template( 'single-product/accordions.php' );
}

/**
 * Replace WooCommerce product page tabs with accordions for template 3,4.
 *
 * @since 1.0.0
 */
if ( in_array( $template, [ '3', '4' ], true ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'jupiterx_wc_add_product_page_accordions', 10 );

	remove_action( 'woocommerce_single_product_summary', 'jupiterx_product_page_badges', 4 );
	add_action( 'woocommerce_before_single_product_summary', 'jupiterx_product_page_badges', 30 );
}

/**
 * Replace WooCommerce product page tabs with accordions for template 5,6,9,10.
 *
 * @since 1.0.0
 */
if ( in_array( $template, [ '5', '6', '9', '10' ], true ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_single_product_summary', 'jupiterx_wc_add_product_page_accordions', 40 );
}

/**
 * Modify WooCommerce product page container for template 10.
 *
 * @since 1.0.0
 */
if ( in_array( $template, [ '10' ], true ) ) {
	jupiterx_replace_attribute( 'jupiterx_fixed_wrap[_main_content]', 'class', 'container', 'container-fluid' );
}

/**
 * Wrap WooCommerce product page info for template 4,8.
 *
 * @since 1.0.0
 */
if ( in_array( $template, [ '4', '8' ], true ) ) {
	/**
	 * Modify WooCommerce product page container for template 4,8.
	 *
	 * @since 1.0.0
	 */
	jupiterx_replace_attribute( 'jupiterx_fixed_wrap[_main_content]', 'class', 'container', '' );

	add_action( 'woocommerce_before_single_product_summary', 'jupiterx_wc_open_product_info_container', 25 );
	/**
	 * Add WooCommerce product page opening wrap tag.
	 *
	 * @since 1.0.0
	 */
	function jupiterx_wc_open_product_info_container() {
		jupiterx_open_markup_e( 'jupiterx_wc_product_info_wrap', 'div', 'class=container' );
	}

	add_action( 'woocommerce_after_single_product_summary', 'jupiterx_wc_close_product_info_container', 30 );
	/**
	 * Add WooCommerce product page closing wrap tag.
	 *
	 * @since 1.0.0
	 */
	function jupiterx_wc_close_product_info_container() {
		jupiterx_open_markup_e( 'jupiterx_wc_product_info_wrap', 'div' );
	}
}

add_filter( 'woocommerce_output_related_products_args', 'jupiterx_wc_get_related_product_counts' );
/**
 * Define WooCommerce product page related products for all template.
 *
 * @param array $args The loop arguments.
 *
 * @since 1.11.0
 */
function jupiterx_wc_get_related_product_counts( $args ) {
	// For backward compatibility.
	$related_products_count = get_theme_mod( 'jupiterx_product_page_related_products', 4 );

	if ( ! get_theme_mod( 'jupiterx_product_page_enable_related_products', is_numeric( $related_products_count ) ) ) {
		$args['posts_per_page'] = 0;

		return $args;
	}

	$default_columns_count = is_numeric( $related_products_count ) ? strval( $related_products_count ) : 4;
	$columns               = get_theme_mod( 'jupiterx_product_page_related_grid_columns', $default_columns_count );
	$rows                  = get_theme_mod( 'jupiterx_product_page_related_grid_rows', 1 );

	$args['posts_per_page'] = $columns * $rows;
	$args['columns']        = $columns;

	return $args;
}

/**
 * Define WooCommerce product page related/upsells products for template 9,10.
 *
 * @param array $args The loop arguments.
 *
 * @since 1.0.0
 */
function jupiterx_wc_get_related_upsells_product_counts( $args ) {
	$count = 3;

	if ( 'array' === gettype( $args ) ) {
		$args['posts_per_page'] = $count;
		return $args;
	}

	return $count;
}

/**
 * Modify WooCommerce product page related/upsells products for template 9,10.
 *
 * @since 1.0.0
 */
if ( in_array( $template, [ '9', '10' ], true ) ) {
	add_filter( 'woocommerce_upsells_columns', 'jupiterx_wc_get_related_upsells_product_counts' );
	add_filter( 'woocommerce_upsells_total', 'jupiterx_wc_get_related_upsells_product_counts' );

	add_filter( 'woocommerce_related_products_columns', 'jupiterx_wc_get_related_upsells_product_counts' );
	add_filter( 'woocommerce_output_related_products_args', 'jupiterx_wc_get_related_upsells_product_counts' );
}

add_filter( 'woocommerce_single_product_image_gallery_classes', 'jupiterx_wc_single_product_gallery_classes' );
/**
 * Filter single product gallery class.
 *
 * @param array $classes Gallery class.
 */
function jupiterx_wc_single_product_gallery_classes( $classes ) {
	$page_template = jupiterx_wc_get_product_page_template();

	if ( ! in_array( $page_template, [ '9', '10' ], true ) ) {
		$classes[] = 'jupiterx-product-gallery-' . jupiterx_wc_get_product_page_gallery_orientation();
	}

	if ( in_array( $page_template, [ '9', '10' ], true ) ) {
		$classes[] = 'jupiterx-product-gallery-static';
	}

	return $classes;
}

add_filter( 'woocommerce_single_product_carousel_options', 'jupiterx_wc_single_product_carousel_options' );
/**
 * Filter WooCommerce flexslider carousel options.
 *
 * @param array $options Flexslider options.
 */
function jupiterx_wc_single_product_carousel_options( $options ) {
	if ( in_array( jupiterx_wc_get_product_page_template(), [ '9', '10' ], true ) || 'none' === jupiterx_wc_get_product_page_gallery_orientation() ) {
		$options['controlNav'] = false;
	}

	$options['directionNav'] = true;
	$options['prevText']     = '<svg fill="#333333" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="7.2px" height="12px" viewBox="0 0 7.2 12" style="enable-background:new 0 0 7.2 12;" xml:space="preserve"><path class="st0" d="M2.4,6l4.5-4.3c0.4-0.4,0.4-1,0-1.4c-0.4-0.4-1-0.4-1.4,0l-5.2,5C0.1,5.5,0,5.7,0,6s0.1,0.5,0.3,0.7l5.2,5	C5.7,11.9,6,12,6.2,12c0.3,0,0.5-0.1,0.7-0.3c0.4-0.4,0.4-1,0-1.4L2.4,6z"/></svg>';
	$options['nextText']     = '<svg fill="#333333" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="7.2px" height="12px" viewBox="0 0 7.2 12" style="enable-background:new 0 0 7.2 12;" xml:space="preserve"><path class="st0" d="M4.8,6l-4.5,4.3c-0.4,0.4-0.4,1,0,1.4c0.4,0.4,1,0.4,1.4,0l5.2-5C7.1,6.5,7.2,6.3,7.2,6S7.1,5.5,6.9,5.3l-5.2-5C1.5,0.1,1.2,0,1,0C0.7,0,0.5,0.1,0.3,0.3c-0.4,0.4-0.4,1,0,1.4L4.8,6z"/></svg>';

	return $options;
}

/**
 * Add social share in Woocommerce product page.
 *
 * @since 1.0.0
 */
add_action( 'woocommerce_share', 'jupiterx_wc_product_page_social_share' );

/**
 * Remove description tab heading.
 *
 * @since 1.0.0
 */
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );

/**
 * Remove additional information tab heading.
 *
 * @since 1.0.0
 */
add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );
