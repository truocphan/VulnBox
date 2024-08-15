<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Shortcodes Class
 * Handles shortcodes functionality of plugin
 *
 * @package WooCommerce - Social Login
 * @since 1.1.0
 */
class WOO_Slg_Shortcodes {
	
	var $model,$render;
	
	function __construct(){
		
		// Define global variable
		global $woo_slg_render,$woo_slg_model;
		
		$this->render = $woo_slg_render;
		$this->model = $woo_slg_model;
	}
	
	/**
	 * Show All Social Login Buttons
	 * 
	 * Handles to show all social login buttons on the viewing page
	 * whereever user put shortcode
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function woo_slg_social_login( $atts, $content ) {
		
		// Define global variable
		global $woo_slg_options, $post;

		// Extract as variable
		extract( shortcode_atts( array(	
			'title'				=>	'',
	    	'redirect_url'		=>	'',
	    	'showonpage'		=>	false,
	    	'expand_collapse'	=>	'',
	    	'networks'			=>  '',
		), $atts ) );
		
		$showbuttons = true;
		$is_login_with_email = true;
				
		// if show only on inners pages is set and current page is not inner page 
		if( !empty($showonpage) &&  ! is_singular() ) { $showbuttons = false; }
		
		//check show social buttons or not
		if( $showbuttons ) {
			
			$center_class = '';

			//check user is logged in to site or not and any single social login button is enable or not
			if( !is_user_logged_in() && woo_slg_check_social_enable() || (isset( $_GET['context']) && $_GET['context'] == 'edit') ) {

				if( !empty($networks) && $networks !== 'null' ) {

					$available_networks = woo_slg_social_networks();

					if( !is_array( $networks ) ){
						$networks = explode( ',', $networks );
					}
					
					if( !in_array( 'email', $networks ) ){
						$is_login_with_email = false;
					}

					$enable_login_with_email = ( !empty($woo_slg_options['woo_slg_enable_email'])) ? $woo_slg_options['woo_slg_enable_email'] : '' ;

					if( $enable_login_with_email == 'yes' && $is_login_with_email ) {
						$center_class = ' woo-slg-center-align';
					}

					foreach( $networks as $key => $network ) {
						if( !empty( $network ) ) {
							$network = strtolower($network);
							$networks[$key] = $network;
						}
						if( !array_key_exists($network,  $available_networks) ) {
							unset( $networks[$key]);
						}
						if( $network == 'email') {
							unset( $networks[$key] );
						}
					}
				} else {
					$networks = array();
				}

				// login heading from setting page
				$login_heading = isset( $woo_slg_options['woo_slg_login_heading'] ) ? $woo_slg_options['woo_slg_login_heading'] : '';

				//  check title first from shortcode
				$login_heading = !empty( $title ) ? $title : $login_heading;

				// Old twitter method does not reture the email,
				// Redirection is account/checkout page will not work there
				$tw_redirect_url = !empty( $woo_slg_options['woo_slg_redirect_url'] ) ? $woo_slg_options['woo_slg_redirect_url'] : woo_vou_get_current_page_url();

				//session create for redirect url
				if( !isset( $_GET['wooslgnetwork'] ) ) {
					\WSL\PersistentStorage\WOOSLGPersistent::set('woo_slg_stcd_redirect_url', $tw_redirect_url);
				}

				$redirect_url = ! empty($redirect_url) ? $redirect_url : woo_slg_get_redirection_url();
				
				//session create for access token & secrets	
				\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_stcd_redirect_url', $redirect_url );

				// get html for all social login buttons
				ob_start();

				$expand_collapse_class	= '';
				$expand_collapse_enable = false;
				if( trim( $expand_collapse ) != '' ) {
					$expand_collapse_class	= $expand_collapse == "collapse" ? ' woo-slg-hide' : '';
					$expand_collapse_enable = true;
				}

				if( $expand_collapse_enable ) {

					echo '<p class="woo-slg-info">'. esc_html__($login_heading, 'wooslg'). 
							' <a href="javascript:void(0);" class="woo-slg-show-social-login">'.
							esc_html__( 'Click here to login', 'wooslg' ).
							'</a>
						  </p>';
					
					$expand_collapse_class	.= ' woo-slg-social-container-checkout';
				}

				echo '<fieldset class="woo-slg-social-container'. esc_attr($expand_collapse_class) .esc_attr($center_class).'">';
				if( $is_login_with_email ) {
					//do action to add login with email section
					do_action( 'woo_slg_wrapper_login_with_email', $redirect_url );
				}

				if( !empty($login_heading) && $expand_collapse_enable == false ) {
					echo '<span><legend>' . esc_html( $login_heading ) . '</legend></span>';
				}
				
				$this->render->woo_slg_social_login_inner_buttons( $redirect_url, $networks );
				if( $is_login_with_email ) {
					//do action to add login with email section
					do_action( 'woo_slg_wrapper_login_with_email_bottom', $redirect_url );
				}

				echo '</fieldset><!--#woo_slg_social_login-->';
				
				$content .= ob_get_clean();
			}
		}
		return $content;
	}
	
	/**
	 * Adding Hooks
	 * Adding hooks for calling shortcodes.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function add_hooks() {
		
		//add shortcode to show all social login buttons
		add_shortcode( 'woo_social_login', array($this, 'woo_slg_social_login') );
	}
}