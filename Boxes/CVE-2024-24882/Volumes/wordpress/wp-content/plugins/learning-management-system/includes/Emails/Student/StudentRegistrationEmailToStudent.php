<?php
/**
 * Student registration email to student class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.5.35
 */

namespace Masteriyo\Emails\Student;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Student registration email to student class. Used for sending new account email.
 *
 * @since 1.5.35
 *
 * @package Masteriyo\Emails
 */
class StudentRegistrationEmailToStudent extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.5.35
	 *
	 * @var String
	 */
	protected $id = 'student-registration/to/student';

	/**
	 * HTML template path.
	 *
	 * @since 1.5.35
	 *
	 * @var string
	 */
	protected $html_template = 'emails/student/student-registration.php';

	/**
	 * Send this email.
	 *
	 * @since 1.5.35
	 *
	 * @param \Masteriyo\Models\User $student
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
	 * @since 1.5.35
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return masteriyo_string_to_bool( masteriyo_get_setting( 'emails.student.student_registration.enable' ) );
	}

	/**
	 * Return subject.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_subject() {
		/**
		 * Filter student registration email subject to admin.
		 *
		 * @since 1.5.35
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.student_registration.subject' ) );

		return $this->format_string( $subject );
	}

	/**
	 * Return heading.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_heading() {
		/**
		 * Filter student registration email heading to student.
		 *
		 * @since 1.5.35
		 *
		 * @param string $heading.
		 */
		$heading = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.student_registration.heading' ) );

		return $this->format_string( $heading );
	}

	/**
	 * Return additional content.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_additional_content() {

		/**
		 * Filter student registration email additional content to student.
		 *
		 * @since 1.5.35
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id(), masteriyo_get_setting( 'emails.student.student_registration.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
