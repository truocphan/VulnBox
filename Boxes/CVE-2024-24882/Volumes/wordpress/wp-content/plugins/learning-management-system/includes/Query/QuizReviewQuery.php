<?php
/**
 * Class for parameter-based Course Review querying
 *
 * @package  Masteriyo\Query
 * @version 1.7.0
 * @since   1.7.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz query class.
 */
class QuizReviewQuery extends ObjectQuery {

	/**
	 * Valid query vars for quiz reviews.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'quiz_id' => '',
				'status'  => 'all',
			)
		);
	}

	/**
	 * Get quiz reviews matching the current query vars.
	 *
	 * @since 1.7.0
	 *
	 * @return Masteriyo\Models\QuizReview[] Quiz review objects
	 */
	public function get_quizes_reviews() {
		/**
		 * Filters quiz review object query args.
		 *
		 * @since 1.7.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_quiz_review_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'quiz_review.store' )->query( $args );

		/**
		 * Filters quiz review object query results.
		 *
		 * @since 1.7.0
		 *
		 * @param Masteriyo\Models\QuizReview[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_quiz_review_object_query', $results, $args );
	}
}
