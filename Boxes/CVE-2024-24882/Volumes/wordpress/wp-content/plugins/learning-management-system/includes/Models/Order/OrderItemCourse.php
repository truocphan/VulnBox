<?php
/**
 * Order Line Item (course)
 *
 * @package Masteriyo\Classes
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Models\Order;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Repository\OrderItemCourseRepository;

/**
 * Order item course class.
 */
class OrderItemCourse extends OrderItem {

	/**
	 * Stores order item data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'course_id' => 0,
		'quantity'  => 1,
		'subtotal'  => 0,
		'total'     => 0,
	);

	/**
	 * Get the order item if ID
	 *
	 * @since 1.0.0
	 *
	 * @param OrderItemCourseRepository $repository Order Repository.
	 */
	public function __construct( OrderItemCourseRepository $repository ) {
		parent::__construct();
		$this->repository = $repository;
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course ID.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get the course type.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return 'course';
	}

	/**
	 * Get the courses quantity.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_quantity( $context = 'view' ) {
		return $this->get_prop( 'quantity', $context );
	}

	/**
	 * Get the sub total amount.
	 *
	 * @since  1.0.0
	 *r
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_subtotal( $context = 'view' ) {
		return $this->get_prop( 'subtotal', $context );
	}

	/**
	 * Get the total amount.
	 *
	 * @since  1.0.0
	 *r
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}


	/**
	 * Get formatted subtotal for rest.
	 *
	 * @since 1.5.36
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_rest_formatted_subtotal( $context = 'view' ) {
		$subtotal = masteriyo_price( $this->get_subtotal( $context ), array( 'currency' => $this->get_order()->get_currency() ) );

		/**
		 * Filters the rest formatted subtotal for course.
		 *
		 * @since 1.5.36
		 *
		 * @param integer $subtotal The total.
		 * @param Masteriyo\Models\Order\OrderItemCourse $order_item_course The order object.
		 */
		return apply_filters( 'masteriyo_order_item_course_formatted_subtotal', $subtotal, $this );
	}


	/**
	 * Get formatted total for rest.
	 *
	 * @since 1.5.36
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_rest_formatted_total( $context = 'view' ) {
		$args  = array(
			'currency' => $this->get_order()->get_currency(),
			'html'     => false,
		);
		$total = masteriyo_price( $this->get_total( $context ), $args );

		/**
		 * Filters the rest formatted total for order item course.
		 *
		 * @since 1.5.36
		 *
		 * @param integer $total The total.
		 * @param Masteriyo\Models\Order\OrderItemCourse $order_item_course The order item course object.
		 */
		return apply_filters( 'masteriyo_order_item_course_formatted_total', $total, $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		if ( $course_id > 0 && 'mto-course' !== get_post_type( absint( $course_id ) ) ) {
			$this->error( 'order_item_course_invalid_course_id', __( 'Invalid course ID', 'masteriyo' ) );
		}
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the course type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type course ID.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set the course quantity.
	 *
	 * @since 1.0.0
	 *
	 * @param string $quantity course ID.
	 */
	public function set_quantity( $quantity ) {
		$this->set_prop( 'quantity', masteriyo_stock_amount( $quantity ) );
	}

	/**
	 * Line subtotal (before discounts).
	 *
	 * @since 1.0.0
	 *
	 * @param string $sub_total Subtotal.
	 */
	public function set_subtotal( $sub_total ) {
		$sub_total = masteriyo_format_decimal( $sub_total );

		if ( ! is_numeric( $sub_total ) ) {
			$sub_total = 0;
		}

		$this->set_prop( 'subtotal', $sub_total );
	}

	/**
	 * Setline total amount (after discounts).
	 *
	 * @since 1.0.0
	 *
	 * @param double $total Total amount.
	 */
	public function set_total( $total ) {
		$total = masteriyo_format_decimal( $total );

		if ( ! is_numeric( $total ) ) {
			$total = 0;
		}

		$this->set_prop( 'total', $total );

		// Subtotal cannot be less than total.
		if ( '' === $this->get_subtotal() || $this->get_subtotal() < $this->get_total() ) {
			$this->set_subtotal( $total );
		}
	}

	/**
	 * Get the associated course.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Course|null
	 */
	public function get_course() {
		$course = masteriyo_get_course( $this->get_course_id() );

		/**
		 * Filters course object of an order item.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Course|null $course The course object of an order item.
		 * @param Masteriyo\Models\Order\OrderItemCourse $order_item_course Order item course object.
		 */
		return apply_filters( 'masteriyo_order_item_course', $course, $this );
	}

	/**
	 * Set properties based on passed in course object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Course $course Course object.
	 */
	public function set_course( $course ) {
		if ( ! is_a( $course, 'Masteriyo\Models\Course' ) ) {
			$this->error( 'order_item_course_invalid_course', __( 'Invalid course', 'masteriyo' ) );
		}

		$this->set_course_id( $course->get_id() );
		$this->set_name( $course->get_name() );
	}

}
