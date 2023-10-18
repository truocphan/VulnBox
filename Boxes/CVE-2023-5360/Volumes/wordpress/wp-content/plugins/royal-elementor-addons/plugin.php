<?php
namespace WprAddons;

use Elementor\Utils;
use Elementor\Controls_Manager;
use WprAddons\Includes\Controls\WprAjaxSelect2\WPR_Control_Ajax_Select2;
use WprAddons\Includes\Controls\WPR_Control_Animations;
use WprAddons\Includes\Controls\WPR_Control_Animations_Alt;
use WprAddons\Includes\Controls\WPR_Control_Button_Animations;
use WprAddons\Includes\Controls\WPR_Control_Arrow_Icons;
use WprAddons\Classes\Utilities;
use Elementor\Core\App\App;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {	exit; } // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	/**
	 * @var array
	 */
	private $_localize_settings = [];

	/**
	 * @return string
	 */
	public function get_version() {
		return WPR_ADDONS_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin huh?', 'wpr-addons' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin huh?', 'wpr-addons' ), '1.0' );
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		// Modules Manager
		require WPR_ADDONS_PATH . 'includes/modules-manager.php';

		// Custom Controls
		require WPR_ADDONS_PATH . 'includes/controls/wpr-ajax-select2/wpr-control-ajax-select2.php';
		require WPR_ADDONS_PATH . 'includes/controls/wpr-ajax-select2/wpr-control-ajax-select2-api.php';
		require WPR_ADDONS_PATH . 'includes/controls/wpr-control-animations.php';
		require WPR_ADDONS_PATH . 'includes/controls/wpr-control-icons.php';

		// Templates Library
		require WPR_ADDONS_PATH . 'admin/includes/wpr-templates-library.php';

		// Post Likes
		require WPR_ADDONS_PATH . 'classes/modules/wpr-post-likes.php';

		// Ajax Search
		require WPR_ADDONS_PATH . 'classes/modules/wpr-ajax-search.php';

		// Instagram
		require WPR_ADDONS_PATH . 'classes/modules/wpr-load-more-instagram-posts.php';

		// Twitter
		require WPR_ADDONS_PATH . 'classes/modules/wpr-load-more-tweets.php';

		// Particles
		if ( 'on' === get_option('wpr-particles', 'on') ) {//TODO: make this check automatic(loop through) for all extensions
			require WPR_ADDONS_PATH . 'extensions/wpr-particles.php';
		}

		// Parallax
		if ( 'on' === get_option('wpr-parallax-background', 'on') || 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
			require WPR_ADDONS_PATH . 'extensions/wpr-parallax.php';
		}

		// Sticky Header
		if ( 'on' === get_option('wpr-sticky-section', 'on') ) {
			require WPR_ADDONS_PATH . 'extensions/wpr-sticky-section.php';
		}

		// Custom CSS
		if ( 'on' === get_option('wpr-custom-css', 'on') ) {
			require WPR_ADDONS_PATH . 'extensions/wpr-custom-css.php';
		}

		// Mega Menu
		require WPR_ADDONS_PATH . 'admin/mega-menu.php';


		// Form Builder
		// TODO:: ommit if form builder turned off if possible
		require WPR_ADDONS_PATH . 'classes/modules/wpr-form-handlers.php';

		// Admin Files
		if ( is_admin() ) {
			// Pro Features Notice
			require WPR_ADDONS_PATH . 'admin/notices/pro-features-notice.php';

			// Plugin Update Notice
			require WPR_ADDONS_PATH . 'admin/notices/plugin-update-notice.php';
			
			// Plugin Sale Notice
			require WPR_ADDONS_PATH . 'admin/notices/plugin-sale-notice.php';
			
			// Rating Notice 
			require WPR_ADDONS_PATH . 'admin/notices/rating-notice.php';

			// Plugin Options
			require WPR_ADDONS_PATH . 'admin/plugin-options.php';

			// Templates Kit
			require WPR_ADDONS_PATH . 'admin/templates-kit.php';

			// Theme Builder
			require WPR_ADDONS_PATH . 'admin/theme-builder.php';

			// Premade Blocks
			require WPR_ADDONS_PATH . 'admin/premade-blocks.php';

			// Theme Builder
			require WPR_ADDONS_PATH . 'admin/popups.php';

			// Secondary Image
			require WPR_ADDONS_PATH . 'admin/metabox/wpr-secondary-image.php';

			// Hide Theme Notice
			// TODO: Remove this and fix with Transients
			add_action( 'admin_enqueue_scripts', [ $this, 'hide_theme_notice' ] );
		}

		if ( class_exists('WooCommerce') ) {
			 if ( 'on' === get_option('wpr_override_woo_templates', 'on') ) {
				 require WPR_ADDONS_PATH . 'includes/woocommerce/woocommerce-config.php';
			 }

			// Add Remove From Wishlist
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-add-remove-from-wishlist.php';

			// Add Remove From Compare
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-add-remove-from-compare.php';

			// Update Mini Wishlist
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-update-mini-wishlist.php';

			// Compare Popup Action
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-compare-popup-action.php';

			// Add Remove From Compare
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-update-mini-compare.php';

			// Count Wishlist Items
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-count-wishlist-compare-items.php';
			require WPR_ADDONS_PATH . 'classes/woocommerce/wpr-check-product-in-wc.php';
		}
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = WPR_ADDONS_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function get_localize_settings() {
		return $this->_localize_settings;
	}

	public function add_localize_settings( $setting_key, $setting_value = null ) {
		if ( is_array( $setting_key ) ) {
			$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

			return;
		}

		if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
			$this->_localize_settings[ $setting_key ] = $setting_value;

			return;
		}

		$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
	}

	public function script_suffix() {
		// $dir = is_rtl() ? '-rtl' : '';
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	public function register_ajax_hooks() {
        add_action( 'wp_ajax_mailchimp_subscribe', [new Utilities, 'ajax_mailchimp_subscribe'] );
        add_action( 'wp_ajax_nopriv_mailchimp_subscribe', [new Utilities, 'ajax_mailchimp_subscribe'] );
	}

	public function mega_menu_ajax_loading() {
		$elementor = \Elementor\Plugin::instance();
		$mega_id = get_post_meta( $_GET['item_id'], 'wpr-mega-menu-item', true);
		$content = $elementor->frontend->get_builder_content_for_display($mega_id);

		wp_send_json( $content );
	}

	public function register_megamenu_route() {
		add_action( 'rest_api_init', function() {
			register_rest_route(
				'wpraddons/v1',
				'/wprmegamenu/',
				[
					'methods' => 'GET',
					'callback' =>  [$this, 'mega_menu_ajax_loading'],
					'permission_callback' => '__return_true'
				]
			);
		} );
	}

    public function wpr_get_page_content($request) {
        // $page_id = $_POST['wpr_compare_page_id'];  
        $page_id = $request->get_param('id');
        
        // Check if the page was created with Elementor
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            // $content = \Elementor\Plugin::$instance->frontend->get_builder_content($page_id);
            // return new WP_REST_Response(array('content' => $content), 200);
			
            $post_id = $request->get_param( 'id' );
            $post = get_post( $post_id );

            if ( ! $post ) {
                return new WP_Error( 'invalid_post_id', __( 'Invalid post ID' ), array( 'status' => 404 ) );
            }

            $response = new WP_REST_Response( $post->post_content, 200 );

            if ( class_exists( '\Elementor\Plugin' ) ) {
                $elementor = \Elementor\Plugin::$instance->frontend->get_builder_content( $post_id, true );
                if ( ! empty( $elementor ) ) {
                    $response->set_data( $elementor );
                }
            }

            return $response;
        } else {
            $page = get_post($page_id);  
            if ($page) {
                $content = apply_filters('the_content', $page->post_content);
                return new WP_REST_Response(array('content' => $content), 200);
            } else {
                return new WP_Error('page_not_found', 'Page not found', array('status' => 404));
            }
        }
        wp_die();
    }

    public function register_compare_custom_routes() {
		add_action( 'rest_api_init', function() {
			register_rest_route('wpraddons/v1', '/page-content/(?P<id>\d+)', array(
				'methods' => 'GET',
				'callback' => [$this, 'wpr_get_page_content'],
				'permission_callback' => '__return_true'
			));
		});
    }

	public function register_custom_controls() {

		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register( new WPR_Control_Ajax_Select2() );
		$controls_manager->register( new WPR_Control_Animations() );
		$controls_manager->register( new WPR_Control_Animations_Alt() );
		$controls_manager->register( new WPR_Control_Button_Animations() );
		$controls_manager->register( new WPR_Control_Arrow_Icons() );

	}

	public function register_elementor_document_type( $documents_manager ) {
		// Theme Builder
		require WPR_ADDONS_PATH . 'modules/theme-builder/wpr-theme-builder.php';
		$documents_manager->register_document_type( 'wpr-theme-builder', 'Wpr_Theme_Builder' );

		// Popups
		require WPR_ADDONS_PATH . 'modules/popup/wpr-popup.php';

        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
        	require WPR_ADDONS_PRO_PATH . 'modules/popup-pro/wpr-popup-pro.php';
        	$documents_manager->register_document_type( 'wpr-popups', 'Wpr_Popup_Pro' );
        } else {
        	$documents_manager->register_document_type( 'wpr-popups', 'Wpr_Popup' );
        }
	}

	public function enqueue_styles() {

		wp_register_style(
			'wpr-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/wpr-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_register_style(
			'wpr-link-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/wpr-link-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_register_style(
			'wpr-loading-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/loading-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_register_style(
			'wpr-button-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/button-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		// GOGA - enqueue instead of register (because of animations)
		wp_enqueue_style(
			'wpr-text-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/text-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_register_style(
			'wpr-lightgallery-css',
			WPR_ADDONS_URL . 'assets/css/lib/lightgallery/lightgallery'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		// Posts Timeline
		wp_register_style( 
			'wpr-aos-css', 
			WPR_ADDONS_URL  . 'assets/css/lib/aos/aos.min.css',
			[]
		);

		wp_register_style(
			'wpr-flipster-css',
			WPR_ADDONS_URL . 'assets/css/lib/flipster/jquery.flipster.min.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			'wpr-addons-css',
			WPR_ADDONS_URL . 'assets/css/frontend'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

        // Load FontAwesome - TODO: Check if necessary (maybe elementor is already loading this)
        wp_enqueue_style(
			'font-awesome-5-all',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all'. $this->script_suffix() .'.css',
			false,
			Plugin::instance()->get_version()
		);

        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style(
				'wpr-addons-library-frontend-css',
				WPR_ADDONS_URL . 'assets/css/admin/library-frontend'. $this->script_suffix() .'.css',
				[],
				Plugin::instance()->get_version()
			);
		}
	}

	public function enqueue_editor_styles() {

		wp_enqueue_style(
			'wpr-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/wpr-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			'wpr-animations-link-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/wpr-link-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			'wpr-loading-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/loading-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			'wpr-button-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/button-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			'wpr-text-animations-css',
			WPR_ADDONS_URL . 'assets/css/lib/animations/text-animations'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);
		
		wp_enqueue_style( 
			'wpr-aos-css', 
			WPR_ADDONS_URL  . 'assets/css/lib/aos/aos.min.css',
			[]
		);

		wp_enqueue_style(
			'wpr-flipster-css',
			WPR_ADDONS_URL . 'assets/css/lib/flipster/jquery.flipster.min.css',
			[],
			Plugin::instance()->get_version()
		);
	}

	public function hide_theme_notice() {
		wp_enqueue_style( 'hide-theme-notice', WPR_ADDONS_URL .'assets/css/admin/wporg-theme-notice.css', [] );

		wp_enqueue_script(
			'wpr-plugin-notice-js',
			WPR_ADDONS_URL . 'assets/js/admin/plugin-update-notice.js',
			[
				'jquery'
			]
		);

		wp_localize_script(
			'wpr-plugin-notice-js',
			'WprPluginNotice',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'wpr-plugin-notice-js' ),
			]
		);
	}

	public function enqueue_scripts() {

		wp_enqueue_script(
			'wpr-addons-js',
			WPR_ADDONS_URL . 'assets/js/frontend'. $this->script_suffix() .'.js',
			[
				'jquery',
				'elementor-frontend'
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			'wpr-modal-popups-js',
			WPR_ADDONS_URL . 'assets/js/modal-popups'. $this->script_suffix() .'.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_localize_script(
			'wpr-addons-js',
			'WprConfig', // This is used in the js file to group all of your scripts together
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'resturl' => get_rest_url() . 'wpraddons/v1',
				'nonce' => wp_create_nonce( 'wpr-addons-js' ),
				'addedToCartText' => esc_html__('was added to cart', 'wpr-addons'),
				'viewCart' => esc_html__('View Cart', 'wpr-addons'),
				'comparePageID' => get_option('wpr_compare_page'),
				'comparePageURL' => get_permalink(get_option('wpr_compare_page')),
				'wishlistPageID' => get_option('wpr_wishlist_page'),
				'wishlistPageURL' => get_permalink(get_option('wpr_wishlist_page')),
				'chooseQuantityText' => esc_html__('Please select the required number of items.', 'wpr-addons'),
				'site_key' => get_option('wpr_recaptcha_v3_site_key'),
				'is_admin' => current_user_can('manage_options'),
				'input_empty' => esc_html__('Please fill out this field', 'wpr-addons'),
				'select_empty' => esc_html__('Nothing selected', 'wpr-addons'),
				'file_empty' => esc_html__('Please upload a file', 'wpr-addons'),
				'recaptcha_error' => esc_html__('Recaptcha Error', 'wpr-addons')
			]
		);
	}

	public function register_scripts() {

		wp_register_script(
			'wpr-infinite-scroll',
			WPR_ADDONS_URL . 'assets/js/lib/infinite-scroll/infinite-scroll.min.js',
			[
				'jquery',
			],
			'3.0.5',
			true
		);

		wp_register_script(
			'wpr-isotope',
			WPR_ADDONS_URL . 'assets/js/lib/isotope/isotope'. $this->script_suffix() .'.js',
			[
				'jquery',
			],
			'3.0.8',
			true
		);

		wp_register_script(
			'wpr-slick',
			WPR_ADDONS_URL . 'assets/js/lib/slick/slick'. $this->script_suffix() .'.js',
			[
				'jquery',
			],
			'1.8.1',
			true
		);

		wp_register_script(
			'wpr-lightgallery',
			WPR_ADDONS_URL . 'assets/js/lib/lightgallery/lightgallery'. $this->script_suffix() .'.js',
			[
				'jquery',
			],
			'1.6.12',
			true
		);

		wp_register_script(
			'wpr-marquee',
			WPR_ADDONS_URL . 'assets/js/lib/marquee/marquee'. $this->script_suffix() .'.js',
			[
				'jquery',
			],
			'1.0',
			true
		);

		wp_register_script(
			'wpr-google-maps',
			'https://maps.googleapis.com/maps/api/js?key='. esc_attr(get_option('wpr_google_map_api_key')),
			[],
			'',
			true
		);

		wp_register_script(
			'wpr-google-maps-clusters',
			WPR_ADDONS_URL . 'assets/js/lib/gmap/markerclusterer'. $this->script_suffix() .'.js',
			[],
			'1.0.3',
			true
		);

		wp_register_script(
			'jquery-event-move',
			WPR_ADDONS_URL . 'assets/js/lib/jquery-event-move/jquery.event.move'. $this->script_suffix() .'.js',
			[],
			'2.0',
			true
		);

		wp_register_script(
			'wpr-lottie-animations',
			WPR_ADDONS_URL . 'assets/js/lib/lottie/lottie.min.js',
			[],
			'5.8.0',
			true
		);

		wp_register_script( 
			'wpr-table-to-excel-js',
			 WPR_ADDONS_URL  . 'assets/js/lib/tableToExcel/tableToExcel.js',
			 [],
			 null, 
			 true 
		);
		
		wp_register_script(
			'wpr-aos-js',
			 WPR_ADDONS_URL  . 'assets/js/lib/aos/aos.min.js',
			 [], 
			 null, 
			 true
		);
		
		wp_register_script(
			'wpr-charts',
			WPR_ADDONS_URL . 'assets/js/lib/charts/charts.min.js',
			[],
			'3.7.0',
			true
		);

		wp_register_script(
			'wpr-flipster',
			WPR_ADDONS_URL . 'assets/js/lib/flipster/jquery.flipster.min.js',
			[],
			'2.0',
			true
		);

		wp_register_script( 
			'wpr-perfect-scroll-js',
			 WPR_ADDONS_URL .'assets/js/lib/perfect-scrollbar/perfect-scrollbar.min.js', 
			 [ 'jquery' ], 
			 '0.4.9' 
		);
	}

	public function enqueue_panel_scripts() {

		wp_enqueue_script(
			'wpr-addons-editor-js',
			WPR_ADDONS_URL . 'assets/js/admin/editor'. $this->script_suffix() .'.js',
			[ 'jquery', 'wp-i18n' ],
			Plugin::instance()->get_version(),
			true
		);

		wp_localize_script(
			'wpr-addons-editor-js',
			'registered_modules',
			Utilities::get_registered_modules()
		);
	}

	public function enqueue_inner_panel_scripts() {

		wp_enqueue_script(
			'wpr-macy-js',
			WPR_ADDONS_URL . 'assets/js/lib/macy/macy.js',
			[],
			'3.0.6',
			true
		);

		wp_enqueue_script(
			'wpr-addons-library-frontend-js',
			WPR_ADDONS_URL . 'assets/js/admin/library-frontend'. $this->script_suffix() .'.js',
			[ 'jquery', 'wpr-macy-js' ],
			Plugin::instance()->get_version(),
			true
		);

		wp_localize_script(
			'wpr-addons-library-frontend-js',
			'white_label',
			[
				'logo_url' => !empty(get_option('wpr_wl_plugin_logo')) ? esc_url(wp_get_attachment_image_src(get_option('wpr_wl_plugin_logo'), 'full')[0]) : WPR_ADDONS_ASSETS_URL .'img/logo-40x40.png'
			]
		);

		wp_localize_script(
			'wpr-addons-library-frontend-js',
			'WprLibFrontLoc',
			[
				'nonce' => wp_create_nonce('wpr-addons-library-frontend-js')
			]
		);

		wp_enqueue_script(
			'wpr-addons-library-editor-js',
			WPR_ADDONS_URL . 'assets/js/admin/library-editor'. $this->script_suffix() .'.js',
			[ 'jquery' ],
			Plugin::instance()->get_version(),
			true
		);
	}

	public function enqueue_panel_styles() {
		wp_enqueue_style(
			'wpr-addons-library-editor-css',
			WPR_ADDONS_URL . 'assets/css/admin/editor'. $this->script_suffix() .'.css',
			[],
			Plugin::instance()->get_version()
		);

		$custom_css = "
			.wpr-pro-notice {
				background: #404349 !important;
				border-color: #323232 !important;
			}
			.elementor-control select option[value*=pro-] {
				background: #60646e !important;
			}
			.elementor-panel .wpr-icon:after {
				box-shadow: 0 0 2px 2px #6985ee !important;
			}
			.wpr-pro-notice > span {
				color: #fff !important;
				font-weight: bold;
			}
		";
		
		$ui_theme = isset(get_user_meta(get_current_user_id(), 'elementor_preferences')[0]['ui_theme']) ? get_user_meta(get_current_user_id(), 'elementor_preferences')[0]['ui_theme'] : '';
		
		if ( $ui_theme && $ui_theme === 'dark' ) {
			wp_add_inline_style( 'elementor-editor-dark-mode', $custom_css );
		}
	}

	// Lightbox Styles
	public function lightbox_styles() {
	    echo '<style id="wpr_lightbox_styles">
				.lg-backdrop {
					background-color: '. esc_html(get_option('wpr_lb_bg_color','rgba(0,0,0,0.6)')) .' !important;
				}
				.lg-toolbar,
				.lg-dropdown {
					background-color: '. esc_html(get_option('wpr_lb_toolbar_color','rgba(0,0,0,0.8)')) .' !important;
				}
				.lg-dropdown:after {
					border-bottom-color: '. esc_html(get_option('wpr_lb_toolbar_color','rgba(0,0,0,0.8)')) .' !important;
				}
				.lg-sub-html {
					background-color: '. esc_html(get_option('wpr_lb_caption_color','rgba(0,0,0,0.8)')) .' !important;
				}
				.lg-thumb-outer,
				.lg-progress-bar {
					background-color: '. esc_html(get_option('wpr_lb_gallery_color','#444444')) .' !important;
				}
				.lg-progress {
					background-color: '. esc_html(get_option('wpr_lb_pb_color','#a90707')) .' !important;
				}
				.lg-icon {
					color: '. esc_html(get_option('wpr_lb_ui_color','#efefef')) .' !important;
					font-size: '. esc_html(get_option('wpr_lb_icon_size',20)) .'px !important;
				}
				.lg-icon.lg-toogle-thumb {
					font-size: '. esc_html((get_option('wpr_lb_icon_size',20) + 4)) .'px !important;
				}
				.lg-icon:hover,
				.lg-dropdown-text:hover {
					color: '. esc_html(get_option('wpr_lb_ui_hr_color','#ffffff')) .' !important;
				}
				.lg-sub-html,
				.lg-dropdown-text {
					color: '. esc_html(get_option('wpr_lb_text_color','#efefef')) .' !important;
					font-size: '. esc_html(get_option('wpr_lb_text_size',14)) .'px !important;
				}
				#lg-counter {
					color: '. esc_html(get_option('wpr_lb_text_color','#efefef')) .' !important;
					font-size: '. esc_html(get_option('wpr_lb_text_size',14)) .'px !important;
				}
				.lg-prev,
				.lg-next {
					font-size: '. esc_html(get_option('wpr_lb_arrow_size',35)) .'px !important;
				}

				/* Defaults */
				.lg-icon {
				background-color: transparent !important;
				}

				#lg-counter {
				opacity: 0.9;
				}

				.lg-thumb-outer {
				padding: 0 10px;
				}

				.lg-thumb-item {
				border-radius: 0 !important;
				border: none !important;
				opacity: 0.5;
				}

				.lg-thumb-item.active {
					opacity: 1;
				}
	         </style>';
	}

	public function elementor_init() {
		$this->_modules_manager = new Manager();
	}

	public function register_widget_categories() {
		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'wpr-widgets',
			[
				'title' => Utilities::get_plugin_name(true),
				'icon' => 'font',
			]
		);

		// Add Woo element category in panel
		if ( Utilities::is_theme_builder_template() ) {
			\Elementor\Plugin::instance()->elements_manager->add_category(
				'wpr-theme-builder-widgets',
				[
					'title' => sprintf(esc_html__( '%s Theme Builder', 'wpr-addons' ), Utilities::get_plugin_name()),
					'icon' => 'font',
				]
			);
		}
		
		// Add Woocommerce Builder category in panel
		if ( Utilities::is_theme_builder_template() ) {
			\Elementor\Plugin::instance()->elements_manager->add_category(
				'wpr-woocommerce-builder-widgets',
				[
					'title' => sprintf(esc_html__( '%s Woocommerce Builder', 'wpr-addons' ), Utilities::get_plugin_name()),
					'icon' => 'font',
				]
			);
		}
		
		// Add Premium Widtgets category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'wpr-premium-widgets',
			[
				'title' => sprintf(esc_html__( '%s Premium Widgets', 'wpr-addons' ), Utilities::get_plugin_name()),
				'icon' => 'font',
			]
		);
	}

    public function promote_premium_widgets($config) {

		if ( is_plugin_active('elementor-pro/elementor-pro.php') ) {
			$config['promotionWidgets'] = [];
		}

		$category = Utilities::is_theme_builder_template() ? 'wpr-woocommerce-builder-widgets' : 'wpr-premium-widgets';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$promotion_widgets = [
				[
					'name' => 'wpr-woo-category-grid',
					'title' => __('Woo Category Grid', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-gallery-grid',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-my-account',
					'title' => __('My Account', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-my-account',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-product-filters',
					'title' => __('Product Filters', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-filter',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-product-breadcrumbs',
					'title' => __('Product Breadcrumbs', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-product-breadcrumbs',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-breadcrumbs',
					'title' => __('Post Breadcrumbs', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-product-breadcrumbs',
					'categories' => '["'. $category .'"]',
				],
			];
			
			$config['promotionWidgets'] = array_merge( $config['promotionWidgets'], $promotion_widgets );
		}
		
		if ( !wpr_fs()->is_plan( 'expert' ) ) {
			$expert_widgets = [
				[
					'name' => 'wpr-category-grid',
					'title' => __('Category Grid', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-gallery-grid',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-wishlist-button',
					'title' => __('Wishlist Button', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-heart',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-mini-wishlist',
					'title' => __('Mini Wishlist', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-heart',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-wishlist',
					'title' => __('Wishlist Table', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-heart',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-compare-button',
					'title' => __('Compare Button', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-exchange', // GOGA - new icon needed for compare
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-mini-compare',
					'title' => __('Mini Compare', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-exchange',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-compare',
					'title' => __('Compare Table', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-exchange',
					'categories' => '["'. $category .'"]',
				],
				[
					'name' => 'wpr-custom-field',
					'title' => __('Custom Field', 'wpr-addons'),
					'icon' => 'wpr-icon eicon-database',
					'categories' => '["'. $category .'"]',
				],
			];

			$config['promotionWidgets'] = array_merge( $config['promotionWidgets'], $expert_widgets );
		}

        return $config;
    }

	protected function add_actions() {
		// Register Widgets
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		// Register Categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );

		// Register Ajax Hooks
		$this->register_ajax_hooks();

		// Register Mega Menu Route
		$this->register_megamenu_route();

		// $this->register_compare_custom_routes();

		// Register Custom Controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_custom_controls' ] );

		// Register Document Type
		add_action( 'elementor/documents/register', [ $this, 'register_elementor_document_type' ] );

		// Frontend CSS
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 998 );

		// Editor CSS
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_styles' ], 998 );

		// Register Scripts
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_scripts' ], 998 );

		// Frontend JS
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_scripts' ], 998 );

		// Editor CSS/JS
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_panel_styles' ], 988 );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_inner_panel_scripts' ], 988 );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_panel_scripts' ], 988 );

		// Lightbox Styles
		add_action( 'wp_head', [ $this, 'lightbox_styles' ], 988 );

		// Promote Premium Widgets
        add_filter('elementor/editor/localize_settings', [$this, 'promote_premium_widgets']);

		add_filter( 'pre_get_posts', [$this, 'wpr_custom_posts_per_page'] );
	}

	public function wpr_custom_posts_per_page( $query ) {
		if ( isset($query->query['post_type']) ) {
			if (is_admin() ) {
				if (\Elementor\Plugin::$instance->editor && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
					return;
				}
			}
			$query_post_type = $query->query['post_type'];
			if ( is_string($query_post_type) && is_post_type_archive($query_post_type) && get_option('wpr_cpt_ppp_'. $query_post_type ) ) {
				$query->set( 'posts_per_page', get_option('wpr_cpt_ppp_'. $query_post_type ) );
			}
		}
	
		return $query;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
		
		$this->_includes();
		$this->add_actions();
	}
	
}

if ( ! defined( 'WPR_ADDONS_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
