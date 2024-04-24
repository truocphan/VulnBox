<?php
/**
 * DeactivationFeedback Ajax handler.
 *
 * @since 1.6.0
 *
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\Exceptions\RestException;

/**
 * DeactivationFeedback ajax handler.
 */
class DeactivationFeedbackAjaxHandler extends AjaxHandler {

	/**
	 * The URL for submitting deactivation feedback.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	const FEEDBACK_URL = 'https://stats.wpeverest.com/wp-json/tgreporting/v1/deactivation/';

	/**
	 * DeactivationFeedback ajax action.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	public $action = 'masteriyo_deactivation_feedback';

	/**
	 * Process deactivation feedback ajax request.
	 *
	 * @since 1.6.0
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax handler review notice.
	 *
	 * @since 1.6.0
	 */
	public function process() {

		if ( ! isset( $_POST['_wpnonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				),
				400
			);
			return;
		}

		try {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'masteriyo_deactivation_feedback_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			if ( ! isset( $_POST['reason_slug'] ) ) {
				throw new RestException( 'masteriyo_invalid_action', __( 'Invalid action name!', 'masteriyo' ) );

			}

			$reason_text = '';
			$reason_slug = '';

			if ( ! empty( $_POST['reason_slug'] ) ) {
				$reason_slug = sanitize_text_field( wp_unslash( $_POST['reason_slug'] ) );
			}

			if ( isset( $_POST[ "reason_{$reason_slug}" ] ) && ! empty( $_POST[ "reason_{$reason_slug}" ] ) ) {
				$reason_text = sanitize_text_field( wp_unslash( $_POST[ "reason_{$reason_slug}" ] ) );
			}

			$deactivation_data = array(
				'reason_slug'  => $reason_slug,
				'reason_text'  => $reason_text,
				'admin_email'  => get_bloginfo( 'admin_email' ),
				'website_url'  => esc_url_raw( get_bloginfo( 'url' ) ),
				'base_product' => is_plugin_active( 'learning-management-system-pro/lms.php' ) ? 'learning-management-system-pro/lms.php' : 'learning-management-system/lms.php',
			);

			$this->send_api_request( $deactivation_data );

			wp_send_json_success( $deactivation_data );

		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				),
				400
			);
		}
	}

	/**
	 * Sends an API request with deactivation data.
	 *
	 * @since 1.6.0
	 *
	 * @param array $deactivation_data Deactivation Data.
	 *
	 * @return string The response body from the API request.
	 */
	private function send_api_request( $deactivation_data ) {
		$response = wp_remote_post(
			self::FEEDBACK_URL,
			array(
				'method'      => 'POST',
				'timeout'     => 10,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $this->get_headers(),
				'body'        => array( 'deactivation_data' => $deactivation_data ),
			)
		);

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Return headers.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	private function get_headers() {
		return array(
			'user-agent' => 'Masteriyo/' . masteriyo_get_version() . '; ' . get_bloginfo( 'url' ),
		);
	}
}
