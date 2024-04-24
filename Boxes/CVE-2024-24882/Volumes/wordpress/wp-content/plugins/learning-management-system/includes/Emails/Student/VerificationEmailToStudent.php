<?php
/**
 * Verification email to student class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.6.12
 */

namespace Masteriyo\Emails\Student;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Student registration email to student class. Used for sending new account email.
 *
 * @since 1.6.12
 *
 * @package Masteriyo\Emails
 */
class VerificationEmailToStudent extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.6.12
	 *
	 * @var String
	 */
	protected $id = 'student-email-verification/to/student';

	/**
	 * HTML template path.
	 *
	 * @since 1.6.12
	 *
	 * @var string
	 */
	protected $html_template = 'emails/student/email-verification.php';

	/**
	 * Send this email.
	 *
	 * @since 1.6.12
	 *
	 * @param \Masteriyo\Models\User $student The student user object.
	 */
	public function trigger( $student ) {
		$student = masteriyo_get_user( $student );

		// Bail early if student doesn't exist.
		if ( is_wp_error( $student ) || is_null( $student ) ) {
			return;
		}

		if ( empty( $student->get_email() ) ) {
			return;
		}

		$this->set_recipients( $student->get_email() );

		$this->set( 'email_heading', $this->get_heading() );
		$this->set( 'student', $student );

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
		 * Filter student email verification subject to admin.
		 *
		 * @since 1.6.12
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.email_verification.subject' ) );

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
		 * Filter student email verification heading to student.
		 *
		 * @since 1.6.12
		 *
		 * @param string $heading.
		 */
		$heading = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.email_verification.heading' ) );

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
		 * Filter student email verification additional content to student.
		 *
		 * @since 1.6.12
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.email_verification.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
