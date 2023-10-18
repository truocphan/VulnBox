<?php 
namespace WprAddons\Admin\Notices;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprPluginSaleNotice {
    public function __construct() {
        // delete_option('wpr_plugin_sale_dismiss_notice'); // uncomment for testing
        
        $this->past_date = strtotime( '-2 days' );
        $this->past_date_rml = strtotime( '-15 days' );
        $this->install_date = get_option('royal_elementor_addons_activation_time_for_sale');
        $this->remind_me_later = get_option('wpr_sale_remind_me_later');
        // add_action( 'admin_init', [$this, 'render_notice'] ); // uncomment for testing

        if ( current_user_can('administrator') ) {
            if ( !get_option('wpr_plugin_sale_dismiss_notice') ) {
                if ( !wpr_fs()->can_use_premium_code() && !wpr_fs()->is_plan( 'expert' ) ) {
                    add_action( 'admin_init', [$this, 'render_notice'] );
                }
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_plugin_sale_dismiss_notice', [$this, 'wpr_plugin_sale_dismiss_notice'] );
        add_action( 'wp_ajax_wpr_sale_remind_me_later', [$this, 'wpr_sale_remind_me_later'] );
    }

    public function render_notice() {
        // add_action( 'admin_notices', [$this, 'render_plugin_sale_notice' ]); // uncomment for testing
        if ( $this->past_date >= $this->install_date ) {
            if ( get_option('wpr_sale_remind_me_later') && !($this->past_date_rml >= $this->remind_me_later) ) {
                return;
            }
            add_action( 'admin_notices', [$this, 'render_plugin_sale_notice' ]);
        }
    }
    
    public function wpr_plugin_sale_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}
        
        add_option( 'wpr_plugin_sale_dismiss_notice', true );
    }
    
    public function wpr_sale_remind_me_later() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'wpr_sale_remind_me_later', absint(intval(strtotime('now'))) );
    }

    public function render_plugin_sale_notice() {
        if ( is_admin() ) {
            echo '<div class="notice wpr-plugin-sale-notice is-dismissible">
                        <div>
                            <h3><span>Flash Sale</span><br> Royal Elementor Addons Pro</h3>
                            <ul>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    70+ Designer Made Templates Kit
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    90+ Advanced Elementor Widgets
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Dynamic Website Builder, Custom Post Types and Cutom Fields support
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Advanced Theme Builder
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Advanced WooCommerce Builder
                                </li>
                                 <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Advanced Form Builder
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Mega Menu Builder
                                </li>
                                <li>
                                    <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/check-mark.png">
                                    Ajax Live Search and much more...
                                </li>
                            </ul>
                            <p>
                                Hurry up! Upgrade within the <strong>next 24 hours</strong> and get a 
                                <strong>20% Discount</strong>.<br><br>
                                Use Promo Code: &nbsp;&nbsp;&nbsp;<strong style="border: 1px dashed #C3C4C7;padding: 2px 10px;">REAFLASH20</strong>
                            </p>
                            <br>
                            <div>
                                <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-salebanner-upgrade-pro#purchasepro" target="_blank" class="wpr-upgrade-to-pro-button button button-secondary">Upgrade to Pro <span class="dashicons dashicons-arrow-right-alt"></span></a>
                                <a target="#" target="_blank" class="wpr-upgrade-to-pro-button button button-secondary wpr-remind-later">Remind Me Later</a>
                            </div>
                        </div>
                        <div class="image-wrap"><img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/sale-banner-20.png"></div>
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
            .wpr-plugin-sale-notice {
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

            .wpr-plugin-sale-notice-logo {
                display: none;
                margin-right: 30px;
            }

            .wpr-plugin-sale-notice-logo img {
                max-width: 100%;
            }

            .wpr-plugin-sale-notice h3 {
                font-size: 36px;
                margin-top: 0;
                margin-bottom: 35px;
            }

            .wpr-plugin-sale-notice h3 span {
              display: inline-block;
              margin-bottom: 15px;
              font-size: 12px;
              color: #fff;
              background-color: #f51f3d;
              padding: 2px 12px 4px;
              border-radius: 3px;
            }

            .wpr-plugin-sale-notice ul li {
                font-size: 14px;
            }

            .wpr-plugin-sale-notice ul img {
                display: inline-block;
                width: 11px;
                margin-right: 2px;
            }

            .wpr-plugin-sale-notice p {
              margin-top: 10px;
              margin-bottom: 15px;
              font-size: 14px;
            }
            
            .wpr-plugin-sale-notice .wpr-upgrade-to-pro-button {
                border: 2px solid #e4e4e4;
                color: #9f9c9c;
                background-color: #fff;
                padding: 5px 25px;
                font-weight: bold;
                letter-spacing: 0.3px;
            }
            
            .wpr-plugin-sale-notice .wpr-upgrade-to-pro-button:hover {
              border: 2px solid #6a4bff4f;
              color: #6A4BFF;
              background: #f6f7f7;
            }
            
            .wpr-plugin-sale-notice .wpr-upgrade-to-pro-button:first-child {
              border: 2px solid #6A4BFF;
              color: #ffffff;
              background-color: #6A4BFF;
              text-transform: uppercase;
            }

            .wpr-plugin-sale-notice .wpr-upgrade-to-pro-button .dashicons {
              font-size: 14px;
              line-height: 30px;
            }
            
            .wpr-plugin-sale-notice .image-wrap {
              margin-left: auto;
            }

            #wpr-notice-confetti {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              pointer-events: none;
            }

            @media screen and (max-width: 1400px) {
                .wpr-plugin-sale-notice .image-wrap img {
                  zoom: 0.9;
                }
            }

            @media screen and (max-width: 1366px) {
                .wpr-plugin-sale-notice .image-wrap img {
                  zoom: 0.7;
                }
            }
        </style>";

        
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprPluginSaleNotice();
}