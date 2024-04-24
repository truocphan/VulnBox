<?php
/**
 * Resource handler for Quiz Review data.
 *
 * @since 1.7.0
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Quiz Review data.
 *
 * @since 1.7.0
 */
class QuizReviewResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.7.0
	 *
	 * @param \Masteriyo\Models\QuizReview $quiz_review
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $quiz_review, $context = 'view' ) {
		$author = masteriyo_get_user( $quiz_review->get_author_id( $context ) );

		$data = array(
			'id'                => $quiz_review->get_id(),
			'author_id'         => $quiz_review->get_author_id( $context ),
			'author_name'       => $quiz_review->get_author_name( $context ),
			'author_email'      => $quiz_review->get_author_email( $context ),
			'author_url'        => $quiz_review->get_author_url( $context ),
			'author_avatar_url' => is_wp_error( $author ) ? '' : $author->get_avatar_url(),
			'ip_address'        => $quiz_review->get_ip_address( $context ),
			'date_created'      => masteriyo_rest_prepare_date_response( $quiz_review->get_date_created( $context ) ),
			'title'             => $quiz_review->get_title( $context ),
			'description'       => $quiz_review->get_content( $context ),
			'rating'            => $quiz_review->get_rating( $context ),
			'status'            => $quiz_review->get_status( $context ),
			'agent'             => $quiz_review->get_agent( $context ),
			'type'              => $quiz_review->get_type( $context ),
			'parent'            => $quiz_review->get_parent( $context ),
			'quiz'              => null,
			'replies_count'     => $quiz_review->total_replies_count(),
		);

		$quiz = masteriyo_get_quiz( $quiz_review->get_quiz_id() );

		if ( $quiz ) {
			$data['quiz'] = array(
				'id'   => $quiz->get_id(),
				'name' => $quiz->get_name(),
			);
		}

		/**
		 * Filter quiz review data array resource.
		 *
		 * @since 1.7.0
		 *
		 * @param array $data quiz data.
		 * @param \Masteriyo\Models\QuizReview $quiz_review quiz object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_quiz_review_resource_array', $data, $quiz_review, $context );
	}
}
