<?php
/**
 * ReviewNotice Ajax handler.
 *
 * @since 1.4.3
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\Exceptions\RestException;
use WP_Error;

/**
 * ReviewNotice ajax handler.
 */
class ReviewNoticeAjaxHandler extends AjaxHandler {

	/**
	 * ReviewNotice ajax action.
	 *
	 * @since 1.4.3
	 * @var string
	 */
	public $action = 'masteriyo_review_notice';

	/**
	 * Process review notice ajax request.
	 *
	 * @since 1.4.3
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax handler review notice.
	 *
	 * @since 1.4.3
	 */
	public function process() {
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				),
				400
			);
			return;
		}

		try {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_review_notice_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			$action = isset( $_POST['masteriyo_action'] ) ? sanitize_text_field( $_POST['masteriyo_action'] ) : null;

			$notice = $this->get_notice_setting();

			if ( 'review_received' === $action || 'already_reviewed' === $action ) {
				$notice['reviewed'] = true;
			} elseif ( 'remind_me_later' === $action ) {
				$notice['time_to_ask'] = time() + DAY_IN_SECONDS;
			} elseif ( 'close_notice' === $action ) {
				$notice['closed_count'] = $notice['closed_count'] + 1;
				$notice['time_to_ask']  = time() + DAY_IN_SECONDS;
			} else {
				throw new RestException( 'masteriyo_invalid_action', __( 'Invalid action name!', 'masteriyo' ) );
			}

			// Update review notice only if there is any changes.
			if ( array_diff( $notice, $this->get_notice_setting() ) ) {
				update_option( 'masteriyo_review_notice', $notice, true );
			}

			wp_send_json_success();
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
	 * Return notice setting.
	 *
	 * @since 1.4.5
	 *
	 * @return array
	 */
	protected function get_notice_setting() {
		$notice = get_option( 'masteriyo_review_notice', array() );

		return wp_parse_args(
			$notice,
			array(
				'time_to_ask'  => time() + WEEK_IN_SECONDS,
				'reviewed'     => false,
				'closed_count' => 0,
			)
		);
	}
}
