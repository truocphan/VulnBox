<?php
/**
 * Withdraw request pending email class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.6.14
 */

namespace Masteriyo\Emails\Instructor;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Abstracts\Email;

/**
 * Withdraw request pending email class. Used for sending email to instructor.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Emails
 */
class WithdrawRequestPendingEmailToInstructor extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $id = 'withdraw-request-approved/to/admin';

	/**
	 * HTML template path.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $html_template = 'emails/instructor/withdraw-request-pending.php';

	/**
	 * Send this email.
	 *
	 * @since 1.6.14
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 */
	public function trigger( $withdraw ) {
		$withdraw = masteriyo_get_withdraw( $withdraw );

		if ( ! $withdraw ) {
			return;
		}

		$withdrawer = $withdraw->get_withdrawer();

		if ( ! $withdrawer ) {
			return;
		}

		$email = $withdrawer->get_email();

		$this->set_recipients( $email );
		$this->set( 'withdraw', $withdraw );
		$this->set( 'withdrawer', $withdrawer );

		$this->send(
			$this->get_recipients(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);
	}

	/**
	 * Return true if it is enabled.
	 *
	 * @since 1.6.14
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return masteriyo_string_to_bool( masteriyo_get_setting( 'emails.instructor.withdraw_request_pending.enable' ) );
	}

	/**
	 * Return subject.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	public function get_subject() {
		/**
		 * Filter withdraw request pending email subject to instructor.
		 *
		 * @since 1.6.14
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id() . '_subject', masteriyo_get_setting( 'emails.instructor.withdraw_request_pending.subject' ) );

		return $this->format_string( $subject );
	}

	/**
	 * Return additional content.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	public function get_additional_content() {

		/**
		 * Filter withdraw request pending email additional content to instructor.
		 *
		 * @since 1.6.14
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id() . '_additional_content', masteriyo_get_setting( 'emails.instructor.withdraw_request_pending.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
