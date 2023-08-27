<?php
/**
 * Add Jupiter assets.
 *
 * @package JupiterX\Framework\Assets
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_enqueue_jupiterx_default' );
/**
 * Enqueue default script and style when compiler is not active.
 *
 * @since 1.3.0
 *
 * @return void
 */
function jupiterx_enqueue_jupiterx_default() {

	if ( function_exists( 'jupiterx_compile_less_fragments' ) ) {
		return;
	}

	wp_enqueue_style(
		'jupiterx',
		JUPITERX_ASSETS_URL . 'dist/css/frontend' . JUPITERX_MIN_CSS . '.css',
		[],
		JUPITERX_VERSION
	);
}

jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_enqueue_jupiterx_components' );
/**
 * Enqueue Bootstrap components and Jupiter style.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_enqueue_jupiterx_components() {

	// Scripts.
	wp_enqueue_script(
		'jupiterx',
		JUPITERX_ASSETS_URL . 'dist/js/frontend' . JUPITERX_MIN_JS . '.js',
		[ 'jquery', 'underscore', 'jupiterx-utils' ],
		JUPITERX_VERSION,
		true
	);

	wp_localize_script( 'jupiterx', 'jupiterxOptions', [
		'smoothScroll' => jupiterx_get_option( 'smooth_scroll', 0 ),
		'videMedia' => jupiterx_get_option( 'enable_media_controls', 0 ),
		'quantityFieldSwitch' => jupiterx_get_option( 'quantity_field_switch', 'undefined' ),
	] );

	if ( ! function_exists( 'jupiterx_compile_less_fragments' ) ) {
		return;
	}

	$variables = [ 'variables' ];

	// ... .
	$bootstrap = jupiterx_get_bootstrap_assets();
	$theme     = jupiterx_get_theme_assets();
	$wc        = jupiterx_get_wc_assets();

	// ... .
	$styles = array_merge( $variables, $bootstrap['styles'], $theme['styles'], $wc['styles'] );

	array_walk( $styles, function( &$value, $key ) {
		$value = JUPITERX_PATH . 'assets/less/' . $value . '.less';
	} );

	// ... .
	jupiterx_compile_less_fragments(
		'jupiterx',
		array_unique( $styles ),
		apply_filters( 'jupiterx_enqueued_styles_args', [] )
	);
}

/**
 * Get Bootstrap components.
 *
 * @since 1.0.0
 *
 * @return array Styles and scripts array.
 */
function jupiterx_get_bootstrap_assets() {
	$assets = [];

	// phpcs:disable
	$assets['styles'] = [
		// 'bootstrap/mixins/breakpoints', // No need.
		// 'bootstrap/mixins/hover', // No need.
		'bootstrap/mixins/image',
		'bootstrap/mixins/badge',
		'bootstrap/mixins/resize',
		'bootstrap/mixins/screen-reader',
		'bootstrap/mixins/size',
		'bootstrap/mixins/reset-text',
		'bootstrap/mixins/text-emphasis',
		'bootstrap/mixins/text-hide',
		'bootstrap/mixins/text-truncate',
		'bootstrap/mixins/visibility',
		'bootstrap/mixins/alert',
		'bootstrap/mixins/buttons',
		'bootstrap/mixins/caret',
		'bootstrap/mixins/pagination',
		'bootstrap/mixins/lists',
		// 'bootstrap/mixins/list-group',
		'bootstrap/mixins/nav',
		'bootstrap/mixins/forms',
		// 'bootstrap/mixins/table-row',
		// 'bootstrap/mixins/background-variant',
		'bootstrap/mixins/border-radius',
		// 'bootstrap/mixins/box-shadow', // No need.
		'bootstrap/mixins/gradients',
		// 'bootstrap/mixins/transition', // No need.
		'bootstrap/mixins/clearfix',
		// 'bootstrap/mixins/grid-framework', // No need.
		// 'bootstrap/mixins/grid', // No need.
		'bootstrap/mixins/float',
		'bootstrap/root',
		'bootstrap/reboot',
		'bootstrap/type',
		'bootstrap/images',
		'bootstrap/code',
		'bootstrap/grid',
		'bootstrap/tables',
		'bootstrap/forms',
		'bootstrap/buttons',
		'bootstrap/transitions',
		'bootstrap/dropdown',
		// 'bootstrap/button-group',
		'bootstrap/input-group',
		'bootstrap/custom-forms',
		'bootstrap/nav',
		'bootstrap/navbar',
		'bootstrap/card',
		'bootstrap/breadcrumb',
		'bootstrap/pagination',
		'bootstrap/badge',
		// 'bootstrap/jumbotron',
		'bootstrap/alert',
		// 'bootstrap/progress',
		// 'bootstrap/media',
		// 'bootstrap/list-group',
		// 'bootstrap/close',
		// 'bootstrap/modal',
		// 'bootstrap/tooltip',
		// 'bootstrap/popover',
		// 'bootstrap/carousel',
		// 'bootstrap/utilities/align',
		// 'bootstrap/utilities/background',
		// 'bootstrap/utilities/borders',
		'bootstrap/utilities/clearfix',
		// 'bootstrap/utilities/display',
		// 'bootstrap/utilities/embed',
		// 'bootstrap/utilities/flex',
		// 'bootstrap/utilities/float',
		// 'bootstrap/utilities/position',
		// 'bootstrap/utilities/screenreaders',
		// 'bootstrap/utilities/shadows',
		// 'bootstrap/utilities/sizing',
		// 'bootstrap/utilities/spacing',
		// 'bootstrap/utilities/text',
		'bootstrap/utilities/visibility',
		'bootstrap/print',
	];
	// phpcs:enable

	return $assets;
}

/**
 * Get theme components.
 *
 * @since 1.0.0
 *
 * @return array Styles and scripts array.
 */
function jupiterx_get_theme_assets() {
	$assets = [];

	$assets['styles'] = [
		'theme/mixins/vendor-prefixes',
		'theme/mixins/align',
		'theme/mixins/background',
		'theme/mixins/border',
		'theme/mixins/body-border',
		'theme/mixins/sizes',
		'theme/mixins/spacing',
		'theme/mixins/typography',
		'theme/mixins/visibility',
		'theme/animations',
		'theme/site',
		'theme/header',
		'theme/main',
		'theme/post',
		'theme/post-single',
		'theme/portfolio-single',
		'theme/page-single',
		'theme/elements',
		'theme/archive',
		'theme/widgets',
		'theme/sidebar',
		'theme/search',
		'theme/comments',
		'theme/social-share',
		'theme/icons',
		'theme/footer',
		'theme/style',
		'theme/blocks',
		'theme/post-types',
	];

	return $assets;
}

/**
 * Get WooCommerce components.
 *
 * @since 1.0.0
 *
 * @return array Styles and scripts array.
 */
function jupiterx_get_wc_assets() {
	$assets = [
		'styles' => [],
	];

	if ( ! class_exists( 'woocommerce' ) ) {
		return $assets;
	}

	$template = [ get_theme_mod( 'jupiterx_product_page_template' ) ];

	$assets['styles'] = [
		'lib/featherlight',
		'woocommerce/common',
		'woocommerce/buttons',
		'woocommerce/fields',
		'woocommerce/badges',
		'woocommerce/rating',
		'woocommerce/pagination',
		'woocommerce/product-list',
		'woocommerce/product-page',
		'woocommerce/variations',
		'woocommerce/quantity',
		'woocommerce/widgets',
		'woocommerce/checkout-cart',
		'woocommerce/cart-quick-view',
		'woocommerce/tabs',
		'woocommerce/order',
		'woocommerce/steps',
		'woocommerce/reviews',
		'woocommerce/notice',
	];

	if ( array_intersect( $template, [ 3, 4, 7, 8 ] ) ) {
		$assets['styles'][] = 'woocommerce/product-page-3-4-7-8';
	}

	if ( array_intersect( $template, [ 9, 10 ] ) ) {
		$assets['styles'][] = 'woocommerce/product-page-9-10';
	}

	return $assets;
}

jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_enqueue_assets', 5 );
/**
 * Enqueue Jupiter assets.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_enqueue_assets() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Utils.
	wp_register_script( 'jupiterx-utils', JUPITERX_ASSETS_URL . 'dist/js/utils' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION ); // @codingStandardsIgnoreLine
}

jupiterx_add_filter( 'jupiterx_compiler_less_variables', 'jupiterx_less_defaults', 5 );
/**
 * LESS default variables.
 *
 * @since 1.3.0
 *
 * @param array $vars Current variables.
 *
 * @return array Combined variables.
 */
function jupiterx_less_defaults( $vars ) {
	$defaults = [];

	if ( ! jupiterx_is_pro() ) {
		// phpcs:disable
		$defaults = [
			'post-single-navigation-margin-top'                      => '3rem',
			'post-single-author-box-align-tablet'                    => 'center',
			'post-single-author-box-align-mobile'                    => 'center',
			'post-single-author-box'                                 => '3rem',
			'post-single-related-posts-container'                    => '3rem',
			'portfolio-single-navigation-margin-top'                 => '3rem',
			'portfolio-single-social-share-align-tablet'             => 'center',
			'portfolio-single-social-share-align-mobile'             => 'center',
			'portfolio-single-social-share-link-padding-top'         => '0.4rem',
			'portfolio-single-social-share-link-padding-right'       => '0.75rem',
			'portfolio-single-social-share-link-padding-bottom'      => '0.4rem',
			'portfolio-single-social-share-link-padding-left'        => '0.75rem',
			'portfolio-single-social-share-margin-top'               => '1.5rem',
			'portfolio-single-related-posts-container-margin-bottom' => '3rem',
			'checkout-cart-steps-number-color'                       => '#fff',
			'checkout-cart-steps-number-bg-color'                    => '#adb5bd',
			'checkout-cart-steps-title-color'                        => '#adb5bd',
			'checkout-cart-steps-title-font-size'                    => '1.25rem',
			'checkout-cart-steps-padding-right'                      => '1.5rem',
			'checkout-cart-steps-padding-left'                       => '1.5rem',
			'checkout-cart-steps-container-padding-top'              => '1.5rem',
			'checkout-cart-steps-number-bg-color-active'             => '#007bff',
			'checkout-cart-steps-title-color-active'                 => '#212529',
			'checkout-cart-back-button-margin-right'                 => '0.75rem',
			'checkout-cart-back-button-margin-bottom-mobile'         => '0.75rem',
			'product-list-image-border-width'                        => '0px',
			'product-list-image-border-radius'                       => '4px',
			'product-list-image-margin-right'                        => 'auto',
			'product-list-image-margin-bottom'                       => '1rem',
			'product-list-image-margin-left'                         => 'auto',
			'product-list-sale-price-text-decoration'                => 'none',
			'product-list-rating-margin-bottom'                      => '0.4rem',
			'product-list-category-color'                            => '#212526',
			'product-list-add-cart-button-icon'                      => true,
			'product-list-add-cart-button-margin-bottom'             => '0.2rem',
			'product-list-sale-badge-border-width'                   => '0px',
			'product-list-outstock-badge-border-width'               => '0px',
			'product-list-outstock-badge-border-radius'              => '4px',
			'product-list-item-container-border-width'               => '0px',
			'product-list-pagination-align'                          => 'center',
			'product-list-pagination-align-tablet'                   => 'center',
			'product-list-pagination-align-mobile'                   => 'center',
			'post-single-social-share-align-tablet'                  => 'center',
			'post-single-social-share-align-mobile'                  => 'center',
			'post-single-social-share-link-padding-top'              => '0.4rem',
			'post-single-social-share-link-padding-right'            => '0.75rem',
			'post-single-social-share-link-padding-bottom'           => '0.4rem',
			'post-single-social-share-link-padding-left'             => '0.75rem',
			'post-single-social-share-margin-top'                    => '1.5rem',
			'product-page-image-main-border-width'                   => '0px',
			'product-page-regular-price-text-decoration'             => 'none',
			'product-page-sale-price-color'                          => '#212529',
			'product-page-sale-price-text-decoration'                => 'none',
			'product-page-quantity-input-padding-top'                => '0.5rem',
			'product-page-quantity-input-padding-right'              => '0.75rem',
			'product-page-quantity-input-padding-bottom'             => '0.5rem',
			'product-page-quantity-input-padding-left'               => '0.75rem',
			'product-page-add-cart-button-icon'                      => true,
			'product-page-social-share-link-font-size'               => '1rem',
			'product-page-social-share-link-padding-top'             => '0.5em',
			'product-page-social-share-link-padding-right'           => '0.5em',
			'product-page-social-share-link-padding-bottom'          => '0.5em',
			'product-page-social-share-link-padding-left'            => '0.5em',
			'product-page-tabs-title-background-color'               => '#fff',
			'product-page-tabs-title-background-color-active'        => '#fff',
			'product-page-tabs-box-border-width'                     => '1px',
			'product-page-tabs-box-border-color'                     => '#d3ced2',
			'product-page-tabs-margin-bottom'                        => '5rem',
			'product-page-sale-badge-border-width'                   => '0px',
			'product-page-sale-badge-border-radius'                  => '4px',
			'product-page-sale-badge-margin-bottom'                  => '1.5rem',
			'product-page-outstock-badge-border-width'               => '0px',
			'product-page-outstock-badge-border-radius'              => '4px',
			'product-page-outstock-badge-margin-bottom'              => '1.5rem',
		];
		// phpcs:enable
	}

	return array_merge( $vars, $defaults );
}

add_action( 'admin_init', 'jupiterx_editor_styles', 10 );
/**
 * Load editor styles.
 *
 * @since 1.5.0
 *
 * @return void
 */
function jupiterx_editor_styles() {

	if ( ! function_exists( 'jupiterx_compile_less_fragments' ) ) {
		return;
	}

	$compiler = jupiterx_compile_less_fragments(
		'jupiterx-editor',
		[
			JUPITERX_PATH . 'assets/less/theme/mixins/vendor-prefixes.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/align.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/background.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/border.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/body-border.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/sizes.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/spacing.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/typography.less',
			JUPITERX_PATH . 'assets/less/theme/mixins/visibility.less',
			JUPITERX_PATH . 'assets/less/variables.less',
			JUPITERX_PATH . 'assets/less/theme/style-editor.less',
		],
		[
			'enqueue' => false,
		]
	);

	if ( empty( $compiler ) ) {
		return;
	}

	add_editor_style( $compiler->get_url() );
}

add_action( 'admin_enqueue_scripts', 'jupiterx_editor_scripts' );
/**
 * Enqueue and localize editor scripts.
 *
 * @since 1.6.0
 *
 * @param string $hook Page hook.
 *
 * @return void
 */
function jupiterx_editor_scripts( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'edit.php', 'post-new.php' ], true ) ) {
		return;
	}

	wp_enqueue_script( 'jupiterx-editor', JUPITERX_ASSETS_URL . 'dist/js/gutenberg-editor' . JUPITERX_MIN_JS . '.js', JUPITERX_VERSION, true ); // phpcs:ignore

	$content_width_data['main'] = jupiterx_get_content_width();
	$content_width_data        += jupiterx_calculate_sidebar_affected_content_width(); // Width of content based on different sidebar layout.
	// Localize width data.
	wp_localize_script( 'jupiterx-editor', 'jupiterx_gutenberg_width', $content_width_data );
}

add_filter( 'jupiterx_compiler_less_variables', 'jupiterx_post_types_less_variables' );
/**
 * Additional variables for post types.
 *
 * @since 1.10.0
 *
 * @param array $vars Less variables.
 *
 * @return array Merged variables.
 */
function jupiterx_post_types_less_variables( $vars ) {
	$post_types = jupiterx_get_post_types();

	if ( empty( $post_types ) ) {
		return $vars;
	}

	$add_vars = [];

	foreach ( $post_types as $index => $post_type ) {
		$key              = "post-type-{$index}";
		$add_vars[ $key ] = $post_type;
	}

	$add_vars['post-types-length'] = count( $post_types );

	return array_merge(
		$add_vars,
		$vars
	);
}
