<?php
namespace WprAddons\Modules\FacebookFeed\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Facebook_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-facebook-feed';
	}

	public function get_title() {
		return esc_html__( 'Facebook Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-facebook';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'facebook feed' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ------------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );


		$this->add_control(
			'instagram_login',
			[
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<form onsubmit="connectInstagramInit(this);" action="javascript:void(0); class="wpr-facebook-login elementor-button" data-type="reviews"><input type="submit" value="Log in with Facebook" style="background-color: #3b5998; color: #fff;"></form>',
				'label_block' => true,
            ]
		);
    }

    protected function render() {
		$settings = $this->get_settings_for_display();

		// // Start Posts
		// // Set the access token
		// $access_token = "YOUR_ACCESS_TOKEN";
		
		// // Make a GET request to the Facebook Graph API using Wordpress HTTP API
		// $response = wp_remote_get( "https://graph.facebook.com/v7.0/PAGE_ID/posts?access_token=$access_token" );
		
		// // Check for errors
		// if ( is_wp_error( $response ) ) {
		// 	echo 'Error: ' . $response->get_error_message();
		// 	exit;
		// }
		
		// // Decode the response
		// $response = json_decode( wp_remote_retrieve_body( $response ), true );
		
		// // Iterate over each post
		// foreach ( $response['data'] as $post ) {
		// 	// Display the post on the profile
		// 	echo "<div class='post'>".$post['message']."</div>";
		// }
		// // End posts

        echo '<div class="wpr-facebook-feed">';
        
        echo '<a target="_blank" class="wpr-login-to-facebook" href="https://www.facebook.com/v13.0/dialog/oauth?client_id=1184287221975469&redirect_uri=https://reastats.kinsta.cloud/token/social-network-fb.php">Log In</a>';
		?>

			<div
				class="fb-like"
				data-share="true"
				data-width="450"
				data-show-faces="true">
			</div>

		<?php
        echo '<div>';

    }
}