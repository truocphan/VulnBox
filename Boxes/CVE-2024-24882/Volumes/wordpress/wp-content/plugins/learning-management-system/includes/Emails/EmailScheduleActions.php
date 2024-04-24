<?php
/**
 * Email schedule actions class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.5.35
 */

namespace Masteriyo\Emails;

use Masteriyo\Emails\Admin\InstructorApplyEmailToAdmin;
use Masteriyo\Emails\Admin\NewOrderEmailToAdmin;
use Masteriyo\Emails\Instructor\InstructorApplyApprovedEmailToInstructor;
use Masteriyo\Emails\Instructor\InstructorRegistrationEmailToInstructor;
use Masteriyo\Emails\Instructor\VerificationEmailToInstructor;
use Masteriyo\Emails\Student\CancelledOrderEmailToStudent;
use Masteriyo\Emails\Student\CompletedOrderEmailToStudent;
use Masteriyo\Emails\Student\InstructorApplyRejectedEmailToStudent;
use Masteriyo\Emails\Student\OnHoldOrderEmailToStudent;
use Masteriyo\Emails\Student\StudentRegistrationEmailToStudent;
use Masteriyo\Emails\Student\VerificationEmailToStudent;

defined( 'ABSPATH' ) || exit;

class EmailScheduleActions {

	/**
	 * Initialize.
	 *
	 * @since 1.5.35
	 */
	public static function init() {
		add_action( 'masteriyo/schedule/email/reset-password', array( __CLASS__, 'send_reset_password' ), 10, 2 );

		add_action( 'masteriyo/schedule/email/new-order/to/admin', array( __CLASS__, 'send_new_order_email_to_admin' ) );
		add_action( 'masteriyo/schedule/email/instructor-apply/to/admin', array( __CLASS__, 'send_instructor_apply_email_to_admin' ) );
		add_action( 'masteriyo/schedule/email/instructor-apply-approved/to/instructor', array( __CLASS__, 'send_instructor_apply_approved_email_to_instructor' ) );
		add_action( 'masteriyo/schedule/email/instructor-apply-rejected/to/student', array( __CLASS__, 'send_instructor_apply_rejected_email_to_student' ) );

		add_action( 'masteriyo/schedule/email/completed-order/to/student', array( __CLASS__, 'send_completed_order_to_student' ) );
		add_action( 'masteriyo/schedule/email/cancelled-order/to/student', array( __CLASS__, 'send_cancelled_order_to_student' ) );
		add_action( 'masteriyo/schedule/email/onhold-order/to/student', array( __CLASS__, 'send_onhold_order_to_student' ) );

		add_action( 'masteriyo/schedule/email/student-registration/to/student', array( __CLASS__, 'send_student_registration_email_to_student' ) );
		add_action( 'masteriyo/schedule/email/instructor-registration/to/instructor', array( __CLASS__, 'send_instructor_registration_email_to_instructor' ) );

		add_action( 'masteriyo/schedule/email/student-email-verification/to/student', array( __CLASS__, 'send_student_verification_email_to_student' ) );
		add_action( 'masteriyo/schedule/email/instructor-email-verification/to/instructor', array( __CLASS__, 'send_instructor_verification_email_to_instructor' ) );
	}

	/**
	 * Send user reset password email.
	 *
	 * @since 1.5.36
	 * @since 1.6.1 Added $reset_key parameter.
	 *
	 * @param int $user_id User ID.
	 * @param string $reset_key Password reset key.
	 */
	public static function send_reset_password( $user_id, $reset_key ) {
		$email = new ResetPasswordEmail();
		$email->trigger( $user_id, $reset_key );
	}

	/**
	 * Send completed order to student.
	 *
	 * @since 1.5.35
	 *
	 * @param \Masteriyo\Models\User $student_id
	 */
	public static function send_completed_order_to_student( $student_id ) {
		$email = new CompletedOrderEmailToStudent();
		$email->trigger( $student_id );
	}

	/**
	 * Send cancelled order to student.
	 *
	 * @since 1.5.35
	 *
	 * @param \Masteriyo\Models\User $student_id
	 */
	public static function send_cancelled_order_to_student( $student_id ) {
		$email = new CancelledOrderEmailToStudent();
		$email->trigger( $student_id );
	}

	/**
	 * Send onhold order to student.
	 *
	 * @since 1.5.35
	 *
	 * @param \Masteriyo\Models\User $student_id
	 */
	public static function send_onhold_order_to_student( $student_id ) {
		$email = new OnHoldOrderEmailToStudent();
		$email->trigger( $student_id );
	}

	/**
	 * Send student registration email to student.
	 *
	 * @since 1.5.35
	 *
	 * @param int $student_id student user ID.
	 */
	public static function send_student_registration_email_to_student( $student_id ) {
		$email = new StudentRegistrationEmailToStudent();
		$email->trigger( $student_id );
	}

	/**
	 * Send instructor registration email to instructor.
	 *
	 * @since 1.5.35
	 *
	 * @param int $instructor_id Instructor user ID.
	 */
	public static function send_instructor_registration_email_to_instructor( $instructor_id ) {
		$email = new InstructorRegistrationEmailToInstructor();
		$email->trigger( $instructor_id );
	}

	/**
	 * Send new order email to admin.
	 *
	 * @since 1.5.35
	 *
	 * @param int $order_id Order ID.
	 */
	public static function send_new_order_email_to_admin( $order_id ) {
		$email = new NewOrderEmailToAdmin();
		$email->trigger( $order_id );
	}

		/**
	 * Send student verification email to student.
	 *
	 * @since 1.6.12
	 *
	 * @param int $student_id student user ID.
	 */
	public static function send_student_verification_email_to_student( $student_id ) {
		$email = new VerificationEmailToStudent();
		$email->trigger( $student_id );
	}

	/**
	 * Send instructor verification email to instructor.
	 *
	 * @since 1.6.12
	 *
	 * @param int $instructor_id Instructor user ID.
	 */
	public static function send_instructor_verification_email_to_instructor( $instructor_id ) {
		$email = new VerificationEmailToInstructor();
		$email->trigger( $instructor_id );
	}

	/**
	 * Send instructor apply email to admin.
	 *
	 * @since 1.6.13
	 *
	 * @param int $user_id User ID.
	 */
	public static function send_instructor_apply_email_to_admin( $user_id ) {
		$email = new InstructorApplyEmailToAdmin();
		$email->trigger( $user_id );
	}

	/**
	 * Send instructor apply email approved to instructor.
	 *
	 * @since 1.6.13
	 *
	 * @param int $student_id User ID.
	 */
	public static function send_instructor_apply_approved_email_to_instructor( $student_id ) {
		$email = new InstructorApplyApprovedEmailToInstructor();
		$email->trigger( $student_id );
	}

	/**
	 * Send instructor apply rejected email to student.
	 *
	 * @since 1.6.13
	 *
	 * @param int $student_id User ID.
	 */
	public static function send_instructor_apply_rejected_email_to_student( $student_id ) {
		$email = new InstructorApplyRejectedEmailToStudent();
		$email->trigger( $student_id );
	}
}
