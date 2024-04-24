<?php
/**
 * Verification email to instructor class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.6.12
 */

namespace Masteriyo\Emails\Instructor;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Instructor registration email to instructor class. Used for sending new account email.
 *
 * @since 1.6.12
 *
 * @package Masteriyo\Emails
 */
class VerificationEmailToInstructor extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.6.12
	 *
	 * @var String
	 */
	protected $id = 'instructor-email-verification/to/instructor';

	/**
	 * HTML template path.
	 *
	 * @since 1.6.12
	 *
	 * @var string
	 */
	protected $html_template = 'emails/instructor/email-verification.php';

	/**
	 * Send this email.
	 *
	 * @since 1.6.12
	 *
	 * @param @param \Masteriyo\Models\User $student The student user object.
	 */
	public function trigger( $instructor ) {
		$instructor = masteriyo_get_user( $instructor );

		// Bail early if instructor doesn't exist.
		if ( is_wp_error( $instructor ) || is_null( $instructor ) ) {
			return;
		}

		if ( empty( $instructor->get_email() ) ) {
			return;
		}

		$this->set_recipients( $instructor->get_email() );

		$this->set( 'email_heading', $this->get_heading() );
		$this->set( 'instructor', $instructor );

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
	 * @since 1.6.12
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return true;
	}

	/**
	 * Return subject.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_subject() {
		/**
		 * Filter instructor email verification subject to admin.
		 *
		 * @since 1.6.12
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.instructor.email_verification.subject' ) );

		$subject = empty( trim( $subject ) ) ? _x( 'Complete Your Registration', 'Email subject', 'masteriyo' ) : $subject;

		return $this->format_string( $subject );
	}

	/**
	 * Return heading.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_heading() {
		/**
		 * Filter instructor email verification heading to instructor.
		 *
		 * @since 1.6.12
		 *
		 * @param string $heading.
		 */
		$heading = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.instructor.email_verification.heading' ) );

		return $this->format_string( $heading );
	}

	/**
	 * Return additional content.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_additional_content() {

		/**
		 * Filter instructor email verification additional content to instructor.
		 *
		 * @since 1.6.12
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.instructor.email_verification.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
