<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

if ( !class_exists( 'HTMega_Elementor_Addons_Assests' ) ) {

    class HTMega_Elementor_Addons_Assests{

        /**
         * [$_instance]
         * @var null
         */
        private static $_instance = null;

        /**
         * [instance] Initializes a singleton instance
         * @return [HTMega_Elementor_Addons_Assests]
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * [__construct] Class construcotr
         */
        public function __construct(){

            // Register Scripts
            add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

            // Elementor Editor Style
            add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        }

        /**
         * All available styles
         *
         * @return array
         */
        public function get_styles() {

            $style_list = [

                'htbbootstrap' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htbbootstrap.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-widgets' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-widgets.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-animation' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/animation.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-weather' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-weather.css',
                    'version' => HTMEGA_VERSION
                ],
                'regular-weather-icon' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/weather-icons.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'slick' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/slick.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'magnific-popup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/magnific-popup.css',
                    'version' => HTMEGA_VERSION
                ],
                'ytplayer' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/jquery.mb.YTPlayer.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'swiper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/swiper.min.css',
                    'version' => '8.4.5'
                ],
                'compare-image' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/compare-image.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'justify-gallery' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/justify-gallery.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'datatables' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/datatables.min.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'magnifier' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/magnifier.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'animated-heading' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/animated-text.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'htmega-keyframes' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-keyframes.css',
                    'version' => HTMEGA_VERSION
                ],

                'htmega-admin' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/css/htmega_admin.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-selectric' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/css/selectric.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-temlibray-style' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/css/tmp-style.css',
                    'version' => HTMEGA_VERSION
                ]
            ];

            return apply_filters( 'htmega_style_list', $style_list );

        }

        /**
         * All available scripts
         *
         * @return array
         */
        public function get_scripts(){

            $google_map_api_key = htmega_get_option( 'google_map_api_key','htmega_general_tabs' );

            $script_list = [

                'htbbootstrap' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/htbbootstrap.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-popper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/popper.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-widgets-scripts' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/htmega-widgets-active.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'slick' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/slick.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'magnific-popup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.magnific-popup.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'beerslider' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-beerslider-min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'ytplayer' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mb.YTPlayer.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'mapmarker' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/mapmarker.jquery.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-easing' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.easing.1.3.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-mousewheel' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mousewheel.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'vaccordion' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.vaccordion.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'easy-pie-chart' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-easy-pie-chart.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-countdown' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-countdown.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-newsticker' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-newsticker-min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-goodshare' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/goodshare.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-notify' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/notify.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'counterup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.counterup.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'isotope' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/isotope.pkgd.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'swiper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/swiper.min.js',
                    'version' => '8.4.5',
                    'deps'    => [ 'jquery' ]
                ],
                'justified-gallery' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/justified-gallery.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'datatables' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/datatables.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'magnifier' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/magnifier.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'animated-heading' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/animated-heading.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'waypoints' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/waypoints.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'google-map-api' => [
                    'src'     => 'http://maps.googleapis.com/maps/api/js?sensor=false',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],

                'htmega-admin' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/admin.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-modernizr' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/modernizr.custom.63321.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-selectric' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/jquery.selectric.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-ScrollMagic' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/ScrollMagic.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'babel-min' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/babel.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-templates' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/template_library_manager.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-install-manager' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/install_manager.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-templates', 'wp-util', 'updates' ]
                ],

            ];

            if( !empty( $google_map_api_key ) ){
                $script_list['google-map-api'] = [
                    'src'     => 'https://maps.googleapis.com/maps/api/js?key='.$google_map_api_key,
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ];
            }

            if ( is_plugin_active('woocommerce/woocommerce.php') && htmega_get_option( 'wcaddtocart', 'htmega_thirdparty_element_tabs', 'on' ) === 'on' && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
                $script_list['htmega-single-product-ajax-cart'] = [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ];
            }

            return apply_filters( 'htmega_script_list', $script_list );

        }

        /**
         * Register scripts and styles
         *
         * @return void
         */
        public function register_assets() {
            $scripts = $this->get_scripts();
            $styles  = $this->get_styles();

            $localize_data_frontend = [];

            if( is_plugin_active('elementor-pro/elementor-pro.php') ){
                $localize_data_frontend['elementorpro'] = true;
            }else{
                wp_deregister_script( 'swiper' );
                $localize_data_frontend['elementorpro'] = false;
            }

            
            // Register Scripts
            foreach ( $scripts as $handle => $script ) {
                $deps = ( isset( $script['deps'] ) ? $script['deps'] : false );
                wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
            }

            // Register Styles
            foreach ( $styles as $handle => $style ) {
                $deps = ( isset( $style['deps'] ) ? $style['deps'] : false );
                wp_register_style( $handle, $style['src'], $deps, $style['version'] );
            }

            // Localize Scripts for frontend
            wp_localize_script( 'htmega-widgets-scripts', 'HTMEGAF', $localize_data_frontend );

            // Localize Scripts for template manager
            $current_user  = wp_get_current_user();
            $localize_data = [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'adminURL'         => admin_url(),
                'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
                'version'          => HTMEGA_VERSION,
                'pluginURL'        => plugin_dir_url( __FILE__ ),
                'alldata'          => !empty( HTMega_Addons_Elementor::$template_info['templates'] ) ? HTMega_Addons_Elementor::$template_info['templates'] : array(),
                'prolink'          => isset( HTMega_Addons_Elementor::$template_info['pro_link'] ) ? HTMega_Addons_Elementor::$template_info['pro_link'] : '#',

                'prolabel'         => esc_html__( 'Pro', 'htmega-addons' ),
                'loadingimg'       => HTMEGA_ADDONS_PL_URL . 'admin/assets/images/loading.gif',
                'message'          =>[
                    'packagedesc'=> esc_html__( 'in this package', 'htmega-addons' ),
                    'allload'    => esc_html__( 'All Items have been Loaded', 'htmega-addons' ),
                    'notfound'   => esc_html__( 'Nothing Found', 'htmega-addons' ),
                ],
                'buttontxt'      =>[
                    'tmplibrary' => esc_html__( 'Import to Library', 'htmega-addons' ),
                    'tmppage'    => esc_html__( 'Import to Page', 'htmega-addons' ),
                    'import'     => esc_html__( 'Import', 'htmega-addons' ),
                    'buynow'     => esc_html__( 'Buy Now', 'htmega-addons' ),
                    'preview'    => esc_html__( 'Preview', 'htmega-addons' ),
                    'installing' => esc_html__( 'Installing..', 'htmega-addons' ),
                    'activating' => esc_html__( 'Activating..', 'htmega-addons' ),
                    'active'     => esc_html__( 'Active', 'htmega-addons' ),
                ],
                'user'           => [
                    'email' => $current_user->user_email,
                ],
            ];
            wp_localize_script( 'htmega-templates', 'HTTM', $localize_data );
            
        }


        /**
         * [editor_scripts]
         * @return [void] Load Editor Scripts
         */
        public function editor_scripts() {
            wp_enqueue_style('htmega-element-editor', HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-elementor-editor.css',['elementor-editor'], HTMEGA_VERSION );
            wp_enqueue_script("htmega-widgets-editor", HTMEGA_ADDONS_PL_URL ."/assets/js/htmega-widgets-editor.js", array("jquery"),HTMEGA_VERSION,true);
        }

        /**
         * [enqueue_scripts]
         * @return [void] Frontend Scripts
         */
        public function enqueue_scripts(){

            // CSS
            wp_enqueue_style( 'htbbootstrap' );
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'htmega-animation' );
            wp_enqueue_style( 'htmega-keyframes' );

            // JS
            wp_enqueue_script( 'htmega-popper' );
            wp_enqueue_script( 'htbbootstrap' );
            wp_enqueue_script( 'waypoints' ); 

        }

    }

    HTMega_Elementor_Addons_Assests::instance();

}