<?php

/**
 * Handles enable widgets on modal.
 *
 * @package JupiterX_Core\Control_Panel_2\Enable_Widgets
 *
 * @since 2.5.0
 */
if ( ! class_exists( 'JupiterX_Core_Control_Panel_Enable_Widgets' ) ) {

	/**
	 * Enable widgets on modal.
	 *
	 * @since 2.5.0
	 */
	class JupiterX_Core_Control_Panel_Enable_Widgets {

		/**
		 * Class constructor.
		 *
		 * @since 2.5.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_jupiterx_core_cp_reminde_me_later', [ $this, 'reminde_me_later' ] );
			add_action( 'wp_ajax_jupiterx_core_cp_update_elements', [ $this, 'update_elements' ] );
			add_action( 'wp_ajax_jupiterx_core_cp_dismiss_enable_widgets', [ $this, 'dismiss_enable_widgets' ] );
		}

		/**
		 * Handle reminde me later.
		 *
		 * @since 2.5.0
		 */
		public function reminde_me_later() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
			}

			if ( function_exists( 'jupiterx_get_option' ) ) {
				jupiterx_update_option( 'enable_widgets_reminder', time() + ( 60 * 60 * 24 * 7 ) );
			}

			wp_send_json_success();
		}

		/**
		 * Update elements.
		 *
		 * @since 2.5.0
		 */
		public function update_elements() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
			}

			$elements = filter_input( INPUT_POST, 'elements', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

			jupiterx_update_option( 'elements', $elements );
			jupiterx_update_option( 'enable_widgets_reminder', 'deleted' );

			wp_send_json_success();
		}

		/**
		 * Dismiss enable widgets.
		 *
		 * @since 2.5.0
		 */
		public function dismiss_enable_widgets() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
			}

			jupiterx_update_option( 'enable_widgets_reminder', 'deleted' );

			wp_send_json_success();
		}
	}
}

new JupiterX_Core_Control_Panel_Enable_Widgets();
