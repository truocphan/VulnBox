<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Plugin;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Templates_Modal_Popups setup
 *
 * @since 1.0
 */
class WPR_Templates_Modal_Popups {

	/**
	** Instance of Elemenntor Frontend class.
	*
	** @var \Elementor\Frontend()
	*/
	private static $elementor_instance;

	/**
	** Constructor
	*/
	public function __construct() {
		// Elementor Frontend
		self::$elementor_instance = \Elementor\Plugin::instance();

		add_action( 'template_include', [ $this, 'set_post_type_template' ], 9999 );

		add_action( 'wp_footer', [ $this, 'render_popups' ] );
	}

	/**
	 * Set blank template for editor
	 */
	public function set_post_type_template( $template ) {

		if ( is_singular( 'wpr_templates' ) ) {
			if ( 'wpr-popups' === Utilities::get_elementor_template_type(get_the_ID()) && self::$elementor_instance->preview->is_preview_mode() ) {
				$template = WPR_ADDONS_PATH . 'modules/popup/editor.php';
			}

			return $template;
		}

		return $template;
	}

	/**
	** Popups
	*/
	public function render_popups() {
    	$conditions = json_decode( get_option('wpr_popup_conditions'), true );

    	if ( ! empty( $conditions ) ) {
	    	$conditions = $this->reverse_template_conditions( $conditions );

	    	// Global
    		if ( isset( $conditions['global'] ) ) {
    			WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'global' );
    		}

    		// Custom
			if ( wpr_fs()->can_use_premium_code() ) {
				// Archive
				\WprAddonsPro\Classes\Pro_Modules::archive_pages_popup_conditions( $conditions );

				// Single
				\WprAddonsPro\Classes\Pro_Modules::single_pages_popup_conditions( $conditions );
			}


    		// Enqueue ScrolBar JS //TODO - check if displayed multiple times
    		wp_enqueue_script( 'wpr-popup-scroll-js', WPR_ADDONS_URL .'assets/js/lib/perfect-scrollbar/perfect-scrollbar.min.js', [ 'jquery' ], '0.4.9' );
        }
	}

    /**
    ** Reverse Template Conditions
    */
	public function reverse_template_conditions( $conditions ) {
    	$reverse = [];

    	foreach ( $conditions as $key => $condition ) {
    		foreach( $condition as $location ) {
    			if ( ! isset( $reverse[$location] ) ) {
    				$reverse[$location] = [ $key ];
    			} else {
    				array_push( $reverse[$location], $key );
    			}
    		}
    	}

    	return $reverse;
	}

    /**
    ** Display Popups by Location
    */
	public static function display_popups_by_location( $conditions, $page ) {
    	foreach ( $conditions[$page] as $key => $popup ) {
    		WPR_Templates_Modal_Popups::render_popup_content( $popup );
    	}
	}

	/**
	** Display Elementor Content
	*/
	public static function render_popup_content( $slug ) {
		$template_name = '';

		$template_id = Utilities::get_template_id( $slug );
		$get_settings = WPR_Templates_Modal_Popups::get_template_settings( $slug );
		$get_elementor_content = self::$elementor_instance->frontend->get_builder_content( $template_id, false );

		if ( '' === $get_elementor_content ) {
			return;
		}

		// Encode Settings
		$get_encoded_settings = ! empty( $get_settings ) ? wp_json_encode( $get_settings ) : '[]';

		// Template Settings Attribute
		$template_settings_attr = "data-settings='". esc_attr($get_encoded_settings) ."'";

		// Return if NOT available for current user
		if ( ! WPR_Templates_Modal_Popups::check_available_user_roles( $get_settings['popup_show_for_roles'] ) ) {
			return;
		}

		if ( ! self::$elementor_instance->preview->is_preview_mode() ) {
	    	echo '<div id="wpr-popup-id-'. esc_attr($template_id) .'" class="wpr-template-popup" '. $template_settings_attr .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	    		echo '<div class="wpr-template-popup-inner">';

		    		// Popup Overlay & Close Button
	    			echo '<div class="wpr-popup-overlay"></div>';

		    		// Template Container
	    			echo '<div class="wpr-popup-container">';

		    		// Close Button
						echo '<div class="wpr-popup-close-btn"><i class="eicon-close"></i></div>';
					// if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
					// 	echo '<div class="wpr-popup-close-btn"><i class="fa fa-times"></i></div>';
					// } else {
					// 	echo '<div class="wpr-popup-close-btn"><i class="eicon-close"></i></div>';
					// }

		    		// Elementor Template Content
	    			echo '<div class="wpr-popup-container-inner">';
						echo ''. $get_elementor_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	    			echo '</div>';

	    			echo '</div>';

	    		echo '</div>';
	    	echo '</div>';
		}
	}

    /**
    ** Get Template Settings
    */
	public static function get_template_settings( $slug ) {
    	$settings = [];
    	$defaults = [];

		$template_id = Utilities::get_template_id( $slug );
		$meta_settings = get_post_meta( $template_id, '_elementor_page_settings', true );

		$popup_defaults = [
			'popup_trigger' => 'load',
			'popup_load_delay' => 1,
			'popup_scroll_progress' => 10,
			'popup_inactivity_time' => 15,
			'popup_element_scroll' => '',
			'popup_custom_trigger' => '',
			'popup_specific_date' => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
			'popup_stop_after_date' => false,
			'popup_stop_after_date_select' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
			'popup_show_again_delay' => 1,
			'popup_disable_esc_key' => false,
			'popup_automatic_close_switch' => false,
			'popup_automatic_close_delay' => 10,
			'popup_animation' => 'fade',
			'popup_animation_duration' => 1,
			'popup_show_for_roles' => '',
			'popup_show_via_referral' => false,
			'popup_referral_keyword' => '',
			'popup_display_as' => 'modal',
			'popup_show_on_device' => true,
			'popup_show_on_device_mobile' => true,
			'popup_show_on_device_tablet' => true,
			'popup_disable_page_scroll' => true,
			'popup_overlay_disable_close' => false,
			'popup_close_button_display_delay' => 0,
		];

		// Determine Template
		if ( strpos( $slug, 'popup') ) {
			$defaults = $popup_defaults;
		}

		foreach( $defaults as $option => $value ) {
			if ( isset($meta_settings[$option]) ) {
				$settings[$option] = $meta_settings[$option];
			}
		}

    	return array_merge( $defaults, $settings );
	}

	/**
	** Check Available User Rols
	*/
	public static function check_available_user_roles( $selected_roles ) {
		if ( empty( $selected_roles ) ) {
			return true;
		}

		$current_user = wp_get_current_user();

		if ( ! empty( $current_user->roles ) ) {
			$role = $current_user->roles[0];
		} else {
			$role = 'guest';
		}

		if ( in_array( $role, $selected_roles ) ) {
			return true;
		}

		return false;
	}
}