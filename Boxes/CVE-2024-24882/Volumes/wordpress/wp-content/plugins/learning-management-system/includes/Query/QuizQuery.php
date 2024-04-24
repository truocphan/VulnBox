<?php
/**
 * Class for parameter-based Quiz querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz query class.
 */
class QuizQuery extends ObjectQuery {

	/**
	 * Valid query vars for quizzes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'slug'          => '',
				'date_created'  => null,
				'date_modified' => null,
				'status'        => array( PostStatus::DRAFT, PostStatus::PENDING, PostStatus::PVT, PostStatus::PUBLISH ),
				'parent_id'     => '',
				'course_id'     => '',
			)
		);
	}

	/**
	 * Get quizzes matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Quiz[] quiz objects
	 */
	public function get_quizes() {
		/**
		 * Filters quiz object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_quiz_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'quiz.store' )->query( $args );

		/**
		 * Filters quiz object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Quiz[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_quiz_object_query', $results, $args );
	}
}
