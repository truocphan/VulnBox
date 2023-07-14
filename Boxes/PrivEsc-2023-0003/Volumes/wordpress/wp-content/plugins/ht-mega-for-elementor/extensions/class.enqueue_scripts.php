<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /**
 * Assets Manager
 */
 class HTMegaExtensions_Scripts{

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {

        // Register Scripts
        add_action( 'init', [ $this, 'register_scripts' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

    }

    /**
    * Register Scripts
    */

    public function register_scripts(){

        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            wp_register_script(
                'goodshare',
                HTMEGA_ADDONS_PL_URL . 'assets/extensions/ht-builder/js/goodshare.min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
        }

    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {

        // HT Builder
        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            // CSS
            wp_enqueue_style(
                'htbuilder-main',
                HTMEGA_ADDONS_PL_URL . 'assets/extensions/ht-builder/css/htbuilder.css',
                NULL,
                HTMEGA_VERSION
            );

            // JS
            wp_enqueue_script( 'masonry' );
            wp_enqueue_script(
                'htbuilder-main',
                HTMEGA_ADDONS_PL_URL . 'assets/extensions/ht-builder/js/htbuilder.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
            
        }

        // WC Sales Notification
        if( htmega_get_option( 'salenotification', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            wp_enqueue_style(
                'wcsales-main',
                HTMEGA_ADDONS_PL_URL . 'assets/extensions/wc-sales-notification/css/wc_notification.css',
                NULL,
                HTMEGA_VERSION
            );
        }


    }



}

HTMegaExtensions_Scripts::instance();