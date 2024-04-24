<?php
/**
 * Ajax handler for infinite loading course reviews.
 *
 * @since 1.5.9
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

class CourseReviewsInfiniteLoadingAjaxHandler extends AjaxHandler {

	/**
	 * Ajax action name.
	 *
	 * @since 1.5.9
	 *
	 * @var string
	 */
	public $action = 'masteriyo_course_reviews_infinite_loading';

	/**
	 * Register ajax handler.
	 *
	 * @since 1.5.9
	 */
	public function register() {
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'process' ) );
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax request.
	 *
	 * @since 1.5.9
	 */
	public function process() {
		if ( ! isset( $_REQUEST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				),
				400
			);
			return;
		}

		try {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'masteriyo_course_reviews_infinite_loading_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			$this->validate_request();

			$page      = absint( $_REQUEST['page'] );
			$course_id = absint( $_REQUEST['course_id'] );

			/**
			 * Filters course reviews list html for a page while infinite loading.
			 *
			 * @since 1.5.9
			 *
			 * @param string $html The course reviews html.
			 * @param integer $course_id Course ID.
			 * @param integer $page Current page number.
			 */
			$html = apply_filters(
				'masteriyo_course_reviews_infinite_loading_page_html',
				masteriyo_get_course_reviews_infinite_loading_page_html( $course_id, $page ),
				$course_id,
				$page
			);

			wp_send_json_success(
				array(
					'html' => $html,
				)
			);
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
	 * Validate ajax request.
	 *
	 * @since 1.5.9
	 */
	protected function validate_request() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'masteriyo_course_reviews_infinite_loading_nonce' ) ) {
			throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
		}
		if ( ! isset( $_REQUEST['page'] ) ) {
			throw new \Exception( __( 'Page number is required.', 'masteriyo' ) );
		}
		if ( ! isset( $_REQUEST['course_id'] ) ) {
			throw new \Exception( __( 'Course ID is required.', 'masteriyo' ) );
		}

		$course = masteriyo_get_course( absint( $_REQUEST['course_id'] ) );

		if ( is_null( $course ) ) {
			throw new \Exception( __( 'Invalid course ID.', 'masteriyo' ) );
		}

		/**
		 * Filters validation result for course reviews infinite loading ajax request.
		 * Return true for valid. Return \Throwable instance for error.
		 *
		 * @since 1.5.9
		 *
		 * @param boolean $is_valid True for valid. Return \Throwable instance for error.
		 */
		$validation = apply_filters( 'masteriyo_validate_course_reviews_infinite_loading_ajax_request', true );

		if ( $validation instanceof \Throwable ) {
			throw $validation;
		}

		return true;
	}
}
