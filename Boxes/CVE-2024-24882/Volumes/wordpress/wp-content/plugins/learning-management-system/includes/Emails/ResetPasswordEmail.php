<?php
/**
 * ResetPasswordEmail class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * ResetPasswordEmail Class. Used for sending password reset email.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class ResetPasswordEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $id = 'reset-password';

	/**
	 * Password reset key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $reset_key;

	/**
	 * HTML template path.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/reset-password.php';

	/**
	 * Send this email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $user_id User ID.
	 * @param string $reset_key Password reset key.
	 */
	public function trigger( $user_id, $reset_key ) {
		$user = masteriyo_get_user( $user_id );

		// Bail early if user doesn't exist.
		if ( is_wp_error( $user ) || is_null( $user ) ) {
			return;
		}

		$this->set_recipients( stripslashes( $user->get_email() ) );

		// Bail if recipient is empty.
		if ( empty( $this->get_recipients() ) ) {
			return;
		}

		$this->setup_locale();
		$this->set( 'user', $user );
		$this->set( 'reset_key', $reset_key );

		$this->send(
			$this->get_recipients(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);
	}

	/**
	 * Get default email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( 'Password Reset Request!', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Password Reset Request', 'masteriyo' );
	}

	/**
	 * Default content to show above the email footer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_additional_content() {
		return __( 'Thanks for reading.', 'masteriyo' );
	}

	/**
	 * Set the password reset key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 */
	public function set_reset_key( $key ) {
		$this->reset_key = $key;
	}

	/**
	 * Get the password reset key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_reset_key() {
		return $this->reset_key;
	}

	/**
	 * Return additional content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_additional_content() {
		return $this->get_default_additional_content();
	}


	/**
	 * Return subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_subject() {
		return $this->get_default_subject();
	}

	/**
	 * Return true if the email is enabled.
	 *
	 * @since 1.5.35
	 *
	 * @return boolean
	 */
	public function is_enabled() {

		/**
		 * Filters boolean-like value: 'yes' if reset password email should be disabled, otherwise 'no'.
		 *
		 * @since 1.0.0
		 *
		 * @param string $is_disabled 'yes' if reset password email should be disabled, otherwise 'no'.
		 */
		$is_disabled = masteriyo_string_to_bool( apply_filters( 'masteriyo_disable_reset_password_email', 'no' ) );

		return ! $is_disabled;
	}
}
