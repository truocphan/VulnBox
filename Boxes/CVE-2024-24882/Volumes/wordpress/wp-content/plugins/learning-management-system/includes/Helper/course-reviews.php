<?php
/**
 * Helper functions for course reviews.
 *
 * @since 1.5.9
 */

use Masteriyo\Enums\CommentStatus;

if ( ! function_exists( 'masteriyo_get_course_reviews_infinite_loading_pages_count' ) ) {
	/**
	 * Get count of pages for course reviews infinite loading.
	 *
	 * @since 1.5.9
	 *
	 * @param integer|string|\Masteriyo\Models\Course|\WP_Post $course_id Course ID or object.
	 *
	 * @return integer
	 */
	function masteriyo_get_course_reviews_infinite_loading_pages_count( $course_id ) {
		/**
		 * Filters maximum course reviews per page.
		 *
		 * @since 1.5.9
		 *
		 * @param integer $per_page Course reviews per page.
		 */
		$per_page = apply_filters( 'masteriyo_course_reviews_per_page', 5 );
		$course   = masteriyo_get_course( $course_id );

		if ( is_null( $course ) ) {
			/**
			 * Filters the count of pages for course reviews infinite loading.
			 *
			 * @since 1.5.9
			 *
			 * @param integer $count The count.
			 */
			return apply_filters( 'masteriyo_course_review_pages_count', 0 );
		}

		$result = masteriyo_get_course_reviews(
			array(
				'course_id' => $course->get_id(),
				'status'    => array( CommentStatus::APPROVE_STR, CommentStatus::TRASH ),
				'per_page'  => $per_page,
				'page'      => 1,
				'paginate'  => true,
				'parent'    => 0,
			)
		);

		/**
		 * Filters the count of pages for course reviews infinite loading.
		 *
		 * @since 1.5.9
		 *
		 * @param integer $count The count.
		 */
		return apply_filters( 'masteriyo_course_review_pages_count', $result->max_num_pages );
	}
}

if ( ! function_exists( 'masteriyo_get_replies_of_course_reviews' ) ) {
	/**
	 * Get replies of course reviews.
	 *
	 * @since 1.5.9
	 *
	 * @param integer[] $review_ids Review Ids.
	 *
	 * @return array
	 */
	function masteriyo_get_replies_of_course_reviews( $review_ids ) {
		$replies = masteriyo_get_course_reviews(
			array(
				'status'     => array( 'approve', 'trash' ),
				'parent__in' => $review_ids,
			)
		);

		/**
		 * Filters replies of course reviews.
		 *
		 * @since 1.5.9
		 *
		 * @param \Masteriyo\Models\CourseReview $replies Replies for the given course reviews.
		 * @param integer[] $review_ids Course review IDs.
		 */
		return apply_filters( 'masteriyo_replies_of_course_reviews', $replies, $review_ids );
	}
}

if ( ! function_exists( 'masteriyo_get_course_reviews_infinite_loading_page_html' ) ) {
	/**
	 * Get html for a list of course reviews equivalent to one page for infinite loading.
	 *
	 * @since 1.5.9
	 *
	 * @param integer|\Masteriyo\Models\Course|\WP_Post $course_id Course ID.
	 * @param integer $page Page number.
	 * @param boolean $echo Whether to echo the html or not.
	 *
	 * @return array|void
	 */
	function masteriyo_get_course_reviews_infinite_loading_page_html( $course_id, $page = 1, $echo = false ) {
		/**
		 * Filters maximum course reviews per page.
		 *
		 * @since 1.5.9
		 *
		 * @param integer $per_page Course reviews per page.
		 */
		$per_page            = apply_filters( 'masteriyo_course_reviews_per_page', 5 );
		$reviews_and_replies = masteriyo_get_course_reviews_and_replies( $course_id, $page, $per_page );
		$course_reviews      = $reviews_and_replies['reviews'];
		$all_replies         = $reviews_and_replies['replies'];

		if ( ! $echo ) {
			ob_start();
		}

		foreach ( $course_reviews as $course_review ) {
			$replies = isset( $all_replies[ $course_review->get_id() ] ) ? $all_replies[ $course_review->get_id() ] : array();

			/**
			 * Renders the template to display review item in single course page's reviews list section.
			 *
			 * @since 1.0.5
			 *
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 * @param \Masteriyo\Models\CourseReview[] $replies Replies to the course review.
			 * @param integer $course_id Course ID.
			 */
			do_action( 'masteriyo_template_course_review', $course_review, $replies, $course_id );
		}

		if ( $echo ) {
			return;
		}
		return ob_get_clean();
	}
}
