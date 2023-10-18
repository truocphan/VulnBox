<?php 
namespace WprAddons\Admin\Notices;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprPluginNotice {
    public function __construct() {

        if ( current_user_can('administrator') ) {

            if ( !get_option('wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']) ) {
                add_action( 'admin_init', [$this, 'render_notice'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_plugin_update_dismiss_notice', [$this, 'wpr_plugin_update_dismiss_notice'] );
    }

    public function render_notice() {
        add_action( 'admin_notices', [$this, 'render_plugin_update_notice' ]);
    }
    
    public function wpr_plugin_update_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        add_option( 'wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version'], true );
    }

    public function render_plugin_update_notice() {
        global $current_screen;

        if ( is_admin() ) {
            if ( 'royal-addons_page_wpr-templates-kit' === $current_screen->id || 'update' === $current_screen->id ) {
                return;
            }

            echo '<div class="notice wpr-plugin-update-notice is-dismissible">
                        <div class="wpr-plugin-update-notice-logo">
                            <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3><span>New Features</span><br> Introducing New Widgets</h3>
                            <p>We are excited to announce that we have added new Widgets, Template Kits, and other Elementor<br> features to enhance your website building experience. Stay tuned for the weekly updates!</p>
                            <ul class="wpr-new-widgets-list">
                                <li><a target="_blank" href="https://royal-elementor-addons.com/advanced-sticky-header/?ref=rea-plugin-backend-update-notice">Advanced Sticky Header</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-form-builder-widget/?ref=rea-plugin-backend-update-notice">Form Builder</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-mega-menu-widget/?ref=rea-plugin-backend-update-notice">Mega Menu</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-offcanvas-menu-widget/?ref=rea-plugin-backend-update-notice">Off-Canvas Content</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-instagram-feed-widget/?ref=rea-plugin-backend-update-notice">Instagram Feed</a></li>
                            </ul>
                        </div>
                        <div class="image-wrap"><img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/new-theme-builder.png"></div>
                        <canvas id="wpr-notice-confetti"></canvas>
                </div>';
        }
    }
    
    public static function enqueue_scripts() {
        // Load Confetti
        wp_enqueue_script( 'wpr-confetti-js', WPR_ADDONS_URL .'assets/js/admin/lib/confetti/confetti.min.js', ['jquery'] );

        // Scripts & Styles
        echo "
        <style>
            .wpr-plugin-update-notice {
                position: relative;
                display: flex;
                align-items: center;
                margin-top: 20px;
                margin-bottom: 20px;
                padding: 30px;
                border: 0 !important;
                box-shadow: 0 0 5px rgb(0 0 0 / 0.1);

                padding-left: 40px;
            }

            .wpr-plugin-update-notice-logo {
                display: none;
                margin-right: 30px;
            }

            .wpr-plugin-update-notice-logo img {
                max-width: 100%;
            }

            .wpr-plugin-update-notice h3 {
                font-size: 36px;
                margin-top: 0;
                margin-bottom: 20px;
            }

            .wpr-plugin-update-notice h3 span {
              display: inline-block;
              margin-bottom: 15px;
              font-size: 12px;
              color: #fff;
              background-color: #f51f3d;
              padding: 2px 12px 4px;
              border-radius: 3px;
            }

            .wpr-plugin-update-notice p {
              margin-top: 10px;
              margin-bottom: 15px;
              font-size: 14px;
            }

            .wpr-plugin-update-notice ul {
                display: flex;
            }
            
            .wpr-plugin-update-notice ul a {
            display: block;
            text-decoration: none;
            }
            
            .wpr-plugin-update-notice ul li a:after {
            content: ' ';
            display: inline-block;
            position: relative;
            top: -2px;
            width: 5px;
            height: 5px;
            margin-left: 12px;
            margin-right: 12px;
            background-color: #e0e0e0;
            transform: rotate(45deg);
            }
            
            .wpr-plugin-update-notice ul li:last-child a:after {
            display: none;
            }
            .wpr-get-started-button.button-primary {
            background-color: #6A4BFF;
            }

            .wpr-get-started-button.button-primary:hover {
            background-color: #583ed7;
            }

            .wpr-get-started-button.button-secondary {
            border: 1px solid #6A4BFF;
            color: #6A4BFF;
            }

            .wpr-get-started-button.button-secondary:hover {
            background-color: #6A4BFF;
            border: 2px solid #6A4BFF;
            color: #fff;
            }

            .wpr-get-started-button {
                padding: 5px 25px !important;
            }

            .wpr-get-started-button .dashicons {
              font-size: 12px;
              line-height: 28px;
            }
            
            .wpr-plugin-update-notice .image-wrap {
              margin-left: auto;
            }

            .wpr-plugin-update-notice .image-wrap img {
              zoom: 0.45;
            }

            @media screen and (max-width: 1366px) {
                .wpr-plugin-update-notice h3 {
                    font-size: 32px;
                }

                .wpr-plugin-update-notice .image-wrap img {
                  zoom: 0.4;
                }
            }

            @media screen and (max-width: 1280px) {
                .wpr-plugin-update-notice .image-wrap img {
                  zoom: 0.35;
                }
            }

            #wpr-notice-confetti {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              pointer-events: none;
            }
        </style>";

        
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprPluginNotice();
}