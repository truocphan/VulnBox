<?php
/**
 * Resource handler for Course Review data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Course Review data.
 *
 * @since 1.6.9
 */
class CourseReviewResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseReview $course_review
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $course_review, $context = 'view' ) {
		$author = masteriyo_get_user( $course_review->get_author_id( $context ) );

		$data = array(
			'id'                => $course_review->get_id(),
			'author_id'         => $course_review->get_author_id( $context ),
			'author_name'       => $course_review->get_author_name( $context ),
			'author_email'      => $course_review->get_author_email( $context ),
			'author_url'        => $course_review->get_author_url( $context ),
			'author_avatar_url' => is_wp_error( $author ) ? '' : $author->get_avatar_url(),
			'ip_address'        => $course_review->get_ip_address( $context ),
			'date_created'      => masteriyo_rest_prepare_date_response( $course_review->get_date_created( $context ) ),
			'title'             => $course_review->get_title( $context ),
			'description'       => $course_review->get_content( $context ),
			'rating'            => $course_review->get_rating( $context ),
			'status'            => $course_review->get_status( $context ),
			'agent'             => $course_review->get_agent( $context ),
			'type'              => $course_review->get_type( $context ),
			'parent'            => $course_review->get_parent( $context ),
			'course'            => null,
			'replies_count'     => $course_review->total_replies_count(),
		);

		$course = masteriyo_get_course( $course_review->get_course_id() );

		if ( $course ) {
			$data['course'] = array(
				'id'   => $course->get_id(),
				'name' => $course->get_name(),
			);
		}

		/**
		 * Filter course review data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Course data.
		 * @param \Masteriyo\Models\CourseReview $course_review Course object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_course_review_resource_array', $data, $course_review, $context );
	}
}
