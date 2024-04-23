<?php
/**
 * InstaWP Migration Process
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INSTAWP_Migration' ) ) {
	class INSTAWP_Migration {

		protected static $_instance = null;

		/**
		 * INSTAWP_Migration Constructor
		 */
		public function __construct() {

			if ( isset( $_GET['page'] ) && in_array( sanitize_text_field( $_GET['page'] ), array( 'instawp', 'instawp-template-migrate' ) ) ) {
				add_filter( 'admin_footer_text', '__return_false' );
				add_filter( 'update_footer', '__return_false', 99 );
			}

			add_action( 'wp_ajax_instawp_update_settings', array( $this, 'update_settings' ) );
			add_action( 'wp_ajax_instawp_connect_api_url', array( $this, 'connect_api_url' ) );
			add_action( 'wp_ajax_instawp_reset_plugin', array( $this, 'reset_plugin' ) );
		}


		function reset_plugin() {

			$reset_type = isset( $_POST['reset_type'] ) ? sanitize_text_field( $_POST['reset_type'] ) : '';
			$reset_type = empty( $reset_type ) ? InstaWP_Setting::get_option( 'instawp_reset_type', 'soft' ) : $reset_type;

			if ( ! in_array( $reset_type, array( 'soft', 'hard' ) ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid reset type.' ) ) );
			}

			if ( ! instawp_reset_running_migration( $reset_type ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Plugin reset unsuccessful.' ) ) );
			}

			wp_send_json_success( array( 'message' => esc_html__( 'Plugin reset successfully.' ) ) );
		}


		function connect_api_url() {

			$return_url      = urlencode( admin_url( 'tools.php?page=instawp' ) );
			$connect_api_url = InstaWP_Setting::get_api_domain() . '/authorize?source=InstaWP Connect&return_url=' . $return_url;

			wp_send_json_success( array( 'connect_url' => $connect_api_url ) );
		}


		function update_settings() {

			$_form_data = isset( $_REQUEST['form_data'] ) ? wp_kses_post( $_REQUEST['form_data'] ) : '';
			$_form_data = str_replace( 'amp;', '', $_form_data );

			parse_str( $_form_data, $form_data );

			$settings_nonce = InstaWP_Setting::get_args_option( 'instawp_settings_nonce', $form_data );

			if ( ! wp_verify_nonce( $settings_nonce, 'instawp_settings_nonce_action' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Failed. Please try again reloading the page.' ) ) );
			}

			foreach ( InstaWP_Setting::get_migrate_settings_fields() as $field_id ) {
				if ( ! isset( $form_data[ $field_id ] ) ) {
					continue;
				}
				$field_value = InstaWP_Setting::get_args_option( $field_id, $form_data );

				if ( 'instawp_api_options' === $field_id ) {
					$api_key     = InstaWP_Setting::get_args_option( 'api_key', $field_value );
					$api_options = InstaWP_Setting::get_option( 'instawp_api_options', array() );
					$old_api_key = InstaWP_Setting::get_args_option( 'api_key', $api_options );

					if ( ! empty( $api_key ) && $api_key != $old_api_key ) {
						$api_key_check_response = InstaWP_Rest_Api::config_check_key( $api_key );
	
						if ( isset( $api_key_check_response['error'] ) && $api_key_check_response['error'] == 1 ) {
							wp_send_json_error( array( 'message' => InstaWP_Setting::get_args_option( 'message', $api_key_check_response, esc_html__( 'Error. Invalid API Key', 'instawp-connect' ) ) ) );
						}
						continue;
					}
					$field_value = array_merge( $api_options, $field_value );
				}
				InstaWP_Setting::update_option( $field_id, $field_value );
			}

			wp_send_json_success( array( 'message' => esc_html__( 'Success. Settings updated.' ) ) );
		}

		/**
		 * @return INSTAWP_Migration
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

INSTAWP_Migration::instance();


