<?php
use WprAddons\Classes\Utilities;

class WPR_WooCommerce_Config {

	public function __construct() {
		add_action('wp_ajax_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		add_action('wp_ajax_nopriv_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		
		if ( 'on' == get_option('wpr_enable_woo_flexslider_navigation', 'on') ) {
			add_filter('woocommerce_single_product_carousel_options', [$this, 'wpr_update_woo_flexslider_options']);
		}

		if ( 'on' !== get_option('wpr_enable_product_image_zoom', 'on') ) {
			add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );
		}

		if ( 'on' !== get_option('wpr_remove_wc_default_lightbox', 'on') ) {
			add_action( 'wp',[$this, 'wpr_remove_wc_lightbox'], 99 );
			// add_filter( 'body_class', [$this, 'wpr_remove_elementor_lightbox'] ); //: TODO condition
		}

		// Change number of products that are displayed per page (shop page)
		add_filter( 'loop_shop_per_page', [$this, 'shop_products_per_page'], 20 );

		// Rewrite WC Default Templates
		add_filter( 'wc_get_template', [ $this, 'rewrite_default_wc_templates' ], 10, 3 );


		// Mini-cart template
		if ( 'on' === get_option('wpr_override_woo_mini_cart', 'on') ) {
			add_filter( 'woocommerce_add_to_cart_fragments', [$this, 'wc_refresh_mini_cart_count']);
		}

		// Fix Theme Builder issues
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );

		if ( !is_admin() ) {
			add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		}

		if ( is_admin() ) {
			$template = isset($_GET['post']) ? sanitize_text_field(wp_unslash($_GET['post'])) : '';

			if ( $template_type = Utilities::get_wpr_template_type($template) ) {
				add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
			}
		}
		
		add_action( 'init', [$this, 'add_wishlist_endpoint'] );
		
		if ( 'on' == get_option('wpr_add_wishlist_to_my_account', 'on') ) {
			add_filter( 'woocommerce_account_menu_items', [$this, 'add_wishlist_to_my_account'] );
		}

		add_action( 'woocommerce_after_register_post_type', [$this, 'remove_deleted_products_from_compare_and_wishlist'] );
		// GOGA - max & min issue
		// add_filter( 'woocommerce_quantity_input_args', [$this, 'wpr_custom_quantity_step'], 10, 2 );
	}

	function add_wishlist_endpoint() {
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	function wpr_custom_quantity_step( $args, $product ) {
		$args['input_value'] = 8; // Start from this value
		$args['max_value'] = 80;  // Maximum value
		$args['min_value'] = 8;   // Minimum value
		$args['step'] = 8;        // Increment/decrement by this value
		return $args;
	}
		
	function add_wishlist_to_my_account( $items ) {
		$items['wishlist'] = __( 'Wishlist', 'wpr-addons' );
		return $items;
	}

	function remove_deleted_products_from_compare_and_wishlist() {
		$compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
		if ( ! $compare ) {
			$compare = array();
		}
		foreach ( $compare as $key => $value ) {
			$product = wc_get_product( $value );
			if ( ! $product || 'trash' === get_post_status( $value ) ) {
				$compare = array_diff( $compare, array( $value ) );
			}
		}
		update_user_meta( get_current_user_id(), 'wpr_compare', $compare );

		
		$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		if ( ! $wishlist ) {
			$wishlist = array();
		}
		foreach ( $wishlist as $key => $value ) {
			$product = wc_get_product( $value );
			if ( ! $product || 'trash' === get_post_status( $value ) ) {
				$wishlist = array_diff( $wishlist, array( $value ) );
			}
		}
		update_user_meta( get_current_user_id(), 'wpr_wishlist', $wishlist );
	}

	function wpr_remove_elementor_lightbox( $classes ) {
		$classes[] = 'wpr-no-lightbox';
	
		// Return classes
		return $classes;
	}

	function wpr_remove_wc_lightbox() {	 	 
	   remove_theme_support( 'wc-product-gallery-lightbox' );	 	 
	}

	function wc_refresh_mini_cart_count($fragments) {
		ob_start();
		$items_count = WC()->cart->get_cart_contents_count();
		?>
		<span class="wpr-mini-cart-icon-count <?php echo $items_count ? '' : 'wpr-mini-cart-icon-count-hidden'; ?>"><?php echo $items_count ? $items_count : '0'; ?></span>
		<?php
		$fragments['.wpr-mini-cart-icon-count'] = ob_get_clean();

		ob_start();
		$sub_total = WC()->cart->get_cart_subtotal();
		?>
				<span class="wpr-mini-cart-btn-price">
					<?php
							echo $sub_total; 
					?>
				</span>
		<?php
		$fragments['.wpr-mini-cart-btn-price'] = ob_get_clean();

		return $fragments;
	}

	public function add_cart_single_product_ajax() {
		add_action( 'wp_loaded', [ 'WC_Form_Handler', 'add_to_cart_action' ], 20 );
	
		if ( is_callable( [ 'WC_AJAX', 'get_refreshed_fragments' ] ) ) {
			WC_AJAX::get_refreshed_fragments();
		}
	
		die();
	}
	
	public function wpr_update_woo_flexslider_options( $options ) {
		$options['directionNav'] = true;
		return $options;
	}
	
	public function shop_products_per_page( $cols ) {
	  return get_option('wpr_woo_shop_ppp', 9);
	}

	public function rewrite_default_wc_templates( $located, $template_name ) {
		// GOGA: needs separate conditions
		// Cart template
		if ( $template_name === 'cart/cart.php' && 'on' === get_option('wpr_override_woo_cart', 'on') ) {
			$located = WPR_ADDONS_PATH .'includes/woocommerce/templates/cart/cart.php';
		}

		// Mini-cart template
		if ( $template_name === 'cart/mini-cart.php' && 'on' === get_option('wpr_override_woo_mini_cart', 'on') ) {
			$located = WPR_ADDONS_PATH .'includes/woocommerce/templates/cart/mini-cart.php';
		}

		if ( 'on' === get_option('wpr_override_woo_mini_cart', 'on') ) {

			if ( $template_name === 'notices/success.php' ) {
				$located = WPR_ADDONS_PATH .'includes/woocommerce/templates/notices/success.php';
			}
	
			if ( $template_name === 'notices/error.php' ) {
				$located = WPR_ADDONS_PATH .'includes/woocommerce/templates/notices/error.php';
			}
			
			if ( $template_name === 'notices/notice.php' ) {
				$located = WPR_ADDONS_PATH .'includes/woocommerce/templates/notices/notice.php';
			}

		}

		// if ( $template_name === 'cart/cart-empty.php' ) {

		// }

		// if ( $template_name === 'checkout/form-checkout.php' ) {

		// }

		return $located;
	}
	
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}
}

new WPR_WooCommerce_Config();