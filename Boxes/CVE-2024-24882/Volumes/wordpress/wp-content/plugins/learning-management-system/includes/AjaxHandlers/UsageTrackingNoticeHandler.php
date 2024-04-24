<?php
/**
 * AllowUsageNotice Ajax handler.
 *
 * @since 1.6.0
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\Exceptions\RestException;

/**
 * AllowUsageNotice ajax handler.
 */
class UsageTrackingNoticeHandler extends AjaxHandler {

	/**
	 * AllowUsageNotice ajax action.
	 *
	 * @since 1.6.0
	 * @var string
	 */
	public $action = 'masteriyo_allow_usage_notice';

	/**
	 * Process allow usage notice ajax request.
	 *
	 * @since 1.6.0
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax handler allow usage notice.
	 *
	 * @since 1.6.0
	 */
	public function process() {
		// Check for nonce in $_POST array.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				),
				400
			);
		}

		// Verify nonce for security.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_allow_usage_notice_nonce' ) ) {
			wp_send_json_error(
				__( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ),
				400
			);
		}

		// Sanitize action variable.
		$action = isset( $_POST['masteriyo_action'] ) ? sanitize_text_field( $_POST['masteriyo_action'] ) : null;

		if ( 'allow' === $action ) {
			masteriyo_set_setting( 'advance.tracking.allow_usage', true );
			masteriyo_set_usage_tracking_preference_by_user();
		} elseif ( 'deny' === $action ) {
			masteriyo_set_setting( 'advance.tracking.allow_usage', false );
			masteriyo_set_usage_tracking_preference_by_user();
		} elseif ( 'close' === $action ) {
			masteriyo_set_usage_tracking_notice_is_cancelled();
		} else {
			wp_send_json_error(
				__( 'Invalid action.', 'masteriyo' ),
				400
			);
		}

		wp_send_json_success();
	}
}
