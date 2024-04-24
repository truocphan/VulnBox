<?php
/**
 * Instructor apply rejected email to student class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.6.13
 */

namespace Masteriyo\Emails\Student;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Instructor apply rejected email to student class. Used for sending new account email.
 *
 * @since 1.6.13
 *
 * @package Masteriyo\Emails
 */
class InstructorApplyRejectedEmailToStudent extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.6.13
	 *
	 * @var String
	 */
	protected $id = 'instructor-apply-rejected/to/student';

	/**
	 * HTML template path.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	protected $html_template = 'emails/student/instructor-apply-rejected.php';

	/**
	 * Send this email.
	 *
	 * @since 1.6.13
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
	 * @since 1.6.13
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return masteriyo_string_to_bool( masteriyo_get_setting( 'emails.student.instructor_apply_rejected.enable' ) );
	}

	/**
	 * Return subject.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	public function get_subject() {
		/**
		 * Filter Instructor apply rejected email subject to admin.
		 *
		 * @since 1.6.13
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id() . '_subject', masteriyo_get_setting( 'emails.student.instructor_apply_rejected.subject' ) );

		return $this->format_string( $subject );
	}

	/**
	 * Return heading.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	public function get_heading() {
		/**
		 * Filter Instructor apply rejected email heading to student.
		 *
		 * @since 1.6.13
		 *
		 * @param string $heading.
		 */
		$heading = apply_filters( $this->get_full_id() . '_heading', masteriyo_get_setting( 'emails.student.instructor_apply_rejected.heading' ) );

		return $this->format_string( $heading );
	}

	/**
	 * Return additional content.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	public function get_additional_content() {

		/**
		 * Filter Instructor apply rejected email additional content to student.
		 *
		 * @since 1.6.13
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id() . '_additional_content', masteriyo_get_setting( 'emails.student.instructor_apply_rejected.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
