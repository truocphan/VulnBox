<?php
/**
 * Settings API: JupiterX_Core_Control_Panel_Settings base class
 *
 * @package JupiterX_Core\Framework\Control_Panel\Settings
 *
 * @since 1.18.0
 */

if ( ! class_exists( 'JupiterX_Core_Control_Panel_Settings' ) ) {
	/**
	 * Settings.
	 *
	 * @since 1.18.0
	 */
	class JupiterX_Core_Control_Panel_Settings {

		/**
		 * Constructor.
		 *
		 * @since 1.18.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_jupiterx_core_cp_settings', [ $this, 'ajax_handler' ] );
			add_action( 'wp_ajax_jupiterx_control_panel_welcome_box', [ $this, 'welcome_box' ] );
		}

		/**
		 * Map the requests to proper methods.
		 *
		 * @since 1.18.0
		 */
		public function ajax_handler() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			jupiterx_log(
				"[Control Panel > Settings] To handle the request, the following data is expected to be an array consisting of 'action', '_wpnonce', 'type' and 'fields' for save type.",
				$_POST
			);

			$type = jupiterx_post( 'type' );

			if ( ! $type ) {
				wp_send_json_error(
					__( 'Type param is missing.', 'jupiterx-core' )
				);
			}

			if ( 'flush' === $type ) {
				$this->flush();
			}

			if ( 'save' === $type ) {
				$this->save();
			}

			wp_send_json_error(
				/* translators: Function request type to initialize. */
				sprintf( esc_html__( 'Type param (%s) is not valid.', 'jupiterx-core' ), $type )
			);
		}

		/**
		 * Flush assets cache.
		 *
		 * @since 1.18.0
		 */
		public function flush() {

			jupiterx_core_flush_cache();

			wp_send_json_success( esc_html__( 'Assets flushed successfully.', 'jupiterx-core' ) );
		}

		/**
		 * Save settings.
		 *
		 * @since 1.18.0
		 */
		public function save() {
			$fields = jupiterx_post( 'fields' );

			if ( ! $fields ) {
				wp_send_json_error( esc_html__( 'Fields param is missing.', 'jupiterx-core' ) );
			}

			if ( ! jupiterx_is_pro() ) {
				$pro_fields = [
					'jupiterx_adobe_fonts_project_id',
					'jupiterx_tracking_codes_after_head',
					'jupiterx_tracking_codes_before_head',
					'jupiterx_tracking_codes_after_body',
					'jupiterx_tracking_codes_before_body',
				];

				foreach ( $pro_fields as $name ) {
					unset( $fields[ $name ] );
				}
			}

			foreach ( $fields as $name => $value ) {
				$name = preg_replace( '/(jupiterx|artbees)_/', '', $name, 1 );
				jupiterx_update_option( $name, $value );
			}

			wp_send_json_success( esc_html__( 'Settings saved successfully.', 'jupiterx-core' ) );
		}

		/**
		 * Dismiss jupiterx control panel welcome box.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function welcome_box() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			update_option( 'jupiterx_dashboard_welcome_box', 'false' );

			wp_send_json_success();
		}

	}

	new JupiterX_Core_Control_Panel_Settings();
}
