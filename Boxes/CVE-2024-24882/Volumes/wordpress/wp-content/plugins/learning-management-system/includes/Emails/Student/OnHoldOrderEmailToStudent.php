<?php
/**
 * OnHold order email class to student.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.5.35
 */

namespace Masteriyo\Emails\Student;

use Masteriyo\Abstracts\Email;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Order onhold email class student.
 *
 * @since 1.5.35
 *
 * @package Masteriyo\Emails
 */
class OnHoldOrderEmailToStudent extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.5.35
	 *
	 * @var String
	 */
	protected $id = 'onhold-order/to/student';

	/**
	 * HTML template path.
	 *
	 * @since 1.5.35
	 *
	 * @var string
	 */
	protected $html_template = 'emails/student/onhold-order.php';

	/**
	 * Send this email.
	 *
	 * @since 1.5.35
	 *
	 * @param \Masteriyo\Models\Order $order
	 */
	public function trigger( $order ) {
		$order = masteriyo_get_order( $order );

		if ( ! $order ) {
			return;
		}
		$student = masteriyo_get_user( $order->get_customer_id() );

		if ( is_wp_error( $student ) || is_null( $student ) ) {
			return;
		}

		// Bail early if order doesn't exist.
		if ( empty( $student->get_email() ) ) {
			return;
		}

		$this->set_recipients( $student->get_email() );

		$this->set( 'order', $order );
		$this->set( 'customer', $order->get_customer() );
		$this->set( 'order_item_course', current( $order->get_items( 'course' ) ) );

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
		return masteriyo_string_to_bool( masteriyo_get_setting( 'emails.student.onhold_order.enable' ) );
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
		 * Filter order onhold email subject to student.
		 *
		 * @since 1.5.35
		 *
		 * @param string $subject.
		 */
		$subject = apply_filters( $this->get_full_id() . '_subject', masteriyo_get_setting( 'emails.student.onhold_order.subject' ) );

		return $this->format_string( $subject );
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
		 * Filter order onhold email additional content to student.
		 *
		 * @since 1.5.35
		 *
		 * @param string $additional_content.
		 */
		$additional_content = apply_filters( $this->get_full_id() . '_additional_content', masteriyo_get_setting( 'emails.student.onhold_order.additional_content' ) );

		return $this->format_string( $additional_content );
	}
}
