<?php
/**
 * Helper functions for quiz reviews.
 *
 * @since 1.7.0
 */

use Masteriyo\Enums\CommentStatus;

if ( ! function_exists( 'masteriyo_get_replies_of_quiz_reviews' ) ) {
	/**
	 * Get replies of quiz reviews.
	 *
	 * @since 1.7.0
	 *
	 * @param integer[] $review_ids Review Ids.
	 *
	 * @return array
	 */
	function masteriyo_get_replies_of_quiz_reviews( $review_ids ) {
		$replies = masteriyo_get_quiz_reviews(
			array(
				'status'     => array( 'approve', 'trash' ),
				'parent__in' => $review_ids,
			)
		);

		/**
		 * Filters replies of quiz reviews.
		 *
		 * @since 1.7.0
		 *
		 * @param \Masteriyo\Models\QuizReview $replies Replies for the given quiz reviews.
		 * @param integer[] $review_ids quiz review IDs.
		 */
		return apply_filters( 'masteriyo_replies_of_quiz_reviews', $replies, $review_ids );
	}
}
