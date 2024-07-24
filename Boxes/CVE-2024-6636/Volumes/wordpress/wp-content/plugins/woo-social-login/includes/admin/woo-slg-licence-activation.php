<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class Woo_Slg_license {

	/**
	 * Create license menu
	 *
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */	
	public function woo_slg_plugin_activation_form() {		
		if ( ! woo_slg_is_license_activated() ) {
			add_menu_page(
				esc_html__( 'WooCommerce Social Login', 'wooslg' ),
				esc_html__( 'WooCommerce Social Login', 'wooslg' ),
				'manage_options',
				'woo-social-login',
				array( $this, 'woo_slg_page_callback' ),
				WOO_SLG_IMG_URL . '/wpweb-menu-icon-white.png',
				99
			);
			// We don't need to have a link for the parent in the submenu, so this overwrites it
			// However, this will leave an empty link item that's still visible due to padding
			add_submenu_page(
				'woo-social-login',
				'',
				'',
				'manage_options',
				'woo-social-login',
				array( $this, 'woo_slg_page_callback' ),
			);

			// This gets rid of the submenu item that overwrites the parent
			// This effectively removes the parent link in the submenu
			
			remove_submenu_page( 'woo-social-login', 'woo-social-login' );
		}
		add_submenu_page( 'woo-social-login', 'License', 'License','manage_options','woo-slg-license', array($this, 'woo_slg_page_callback'));
	}

	/**
	 * Include license form 
	 *
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */	
	public function woo_slg_page_callback() {
		require WOO_SLG_ADMIN . '/forms/woo-slg-licence-form.php';
	}

	/**
	 * Handle the license activation & deactivation
	 *
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */	
	public function woo_slg_activate_license_callback() {
		$license_key    = $_POST['license_key'];
		$email          = $_POST['email'];
		$license_action = $_POST['license_action'];

		// If License Active
		if ( $license_action == 'Activate License' ) {
			$data = $this->woo_slg_render_activation_settings( $license_key, $email, $license_action );
			if ( isset( $data['status'] ) && true == $data['status'] ) {				
				update_option( 'woo_slg_activation_code', $license_key, false );
				update_option( 'woo_slg_email_address', $email, false );
				$final_activation_code =  base64_encode( $license_key. '%' . $email );
				update_option( 'woo_slg_activated', $final_activation_code, false );
				delete_option( 'woo_slg_verification_fail' );
			}
			wp_send_json( $data );
		}

		// If License Deactivate
		if ( $license_action == 'Deactivate License' ) {
			$license_key = get_option( 'woo_slg_activation_code' );
			$data = $this->woo_slg_render_activation_settings( $license_key, $email, $license_action );
			if ( true == $data['status'] ) {
				delete_option( 'woo_slg_activated' );
				delete_option( 'woo_slg_activation_code' );
				delete_option( 'woo_slg_email_address' );
				delete_option( 'woo_slg_verification_fail' );
			}
			wp_send_json( $data );
		}
	}


	public function woo_slg_verify_license() {

        $license_key = get_option( 'woo_slg_activation_code' );
        $email 		 = get_option( 'woo_slg_email_address' );
        $data 		 = $this->woo_slg_render_activation_settings( $license_key, $email, 'Verify License' ); 
        if( isset($data['status']) && $data['status'] != 1 ){
            delete_option( 'woo_slg_activated' ); 
            update_option( 'woo_slg_verification_fail', $data['msg'], false );
        }

    }

	/**
	 * Handle the license api call
	 *
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */	
	public function woo_slg_render_activation_settings( $license_key, $email, $license_action ) {

		$activation_code = $license_key;
		$email_address   = $email;
		$url             = WOO_SLG_LICENSE_VALIDATOR;
		$curl            = curl_init();
		$fields          = array(
			'email'           => $email_address,
			'site_url'        => get_site_url(),
			'activation_code' => $activation_code,
			'activation'      => $license_action,
			'version'		  => WOO_SLG_VERSION,
			'item_id'     	  => 8495883,
		);
		$fields_string   = http_build_query( $fields );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HEADER, false );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields_string );
		$data = json_decode( curl_exec( $curl ), true );
		return( $data );

	}

	/**
	 * Handle the license script & style
	 *
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */	
	public function woo_slg_enqueue_license_script( $hook ) {
		if( 'woocommerce-social-login_page_woo-slg-license' === $hook || 'toplevel_page_woo-slg-settings' === $hook ) {		
			wp_enqueue_script( 'woo-slg-admin-license-script', WOO_SLG_URL . 'includes/js/woo-slg-admin-license.js', array( 'jquery' ), WOO_SLG_VERSION );
			wp_enqueue_script( 'sweetalert-script', WOO_SLG_URL . 'includes/js/sweetalert2.all.min.js', array( 'jquery' ), WOO_SLG_VERSION );
			wp_enqueue_style( 'woo-slg-admin-license', WOO_SLG_URL . 'includes/css/woo-slg-admin-license.css', array(), WOO_SLG_VERSION );
		}
	}

	/**
	 *  Display license notice.
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_show_license_notice() {
		
		if( ! woo_slg_is_license_activated() ) { 			
			if( function_exists('get_current_screen') ) {
				//get the current screen
				$screen = get_current_screen();
				$woo_slg_verification_fail = get_option( 'woo_slg_verification_fail' );
				if( $screen->id !== 'woocommerce-social-login_page_woo-slg-license' ) { ?>
					<div class="notice notice-error is-dismissible">
						<p><?php 
						$license_page_url = add_query_arg(array('page'=> 'woo-slg-license'), admin_url( 'admin.php' ) );
						printf( esc_html__( '%sWooCommerce Social Login%s: Please %sactivate%s your license in order to use the plugin.', 'wooslg' ), '<b>', '</b>', '<a href="' . $license_page_url . '">', '</a>' ); ?></p>
					</div>
					<?php

					if( !empty( $woo_slg_verification_fail ) ){ ?>
						<div class="notice notice-error is-dismissible">
							<p><?php echo $woo_slg_verification_fail; ?></p>
						</div>
					<?php }

				}
			}
		}
	}

	/**
	 *  Redirect to license page from setting link
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_activate_license_setting_redirect() {
		global $pagenow;
		if( ! woo_slg_is_license_activated() ) { 
			if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'woo-social-settings' ) {
				$license_page_url = add_query_arg(array('page'=> 'woo-slg-license'), admin_url( 'admin.php' ) );
				wp_safe_redirect($license_page_url);
				exit;
			}
		}
	}

	public function woo_slg_plugin_update_action( $upgrader_object, $options ) {
	    if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
	        $updated_plugins = $options['plugins'];
	        if ( in_array('woo-social-login/woo-social-login.php', $updated_plugins ) ) {
				if( woo_slg_is_license_activated() ){
		            $woo_slg_license = new Woo_Slg_license();
					$woo_slg_license->woo_slg_verify_license();
				}
	        }
	    }
	}

	/**
     * Adding Hooks
     * 
     * @package WooCommerce - Social Login
     * @since 1.0.0
     */
	public function add_hooks() {
		// Action for admin notice
		add_action( 'admin_notices', array( $this, 'woo_slg_show_license_notice' ) );

		// Action for admin menu
		add_action( 'admin_menu', array( $this, 'woo_slg_plugin_activation_form' ) );

		// Action for license enqueue script
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_slg_enqueue_license_script' ) );
		
		// ACtion for activate license callback
		add_action( 'wp_ajax_woo_slg_activate_license', array( $this, 'woo_slg_activate_license_callback' ) );
		add_action( 'admin_page_access_denied', array( $this, 'woo_slg_activate_license_setting_redirect' ) );

		add_action('upgrader_process_complete', array( $this, 'woo_slg_plugin_update_action' ), 10, 2);
	}
}
