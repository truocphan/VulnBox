<?php
/**
 * Resource handler for UserCourse data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Course data.
 *
 * @since 1.6.9
 */
class UserCourseResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\UserCourse $user_course
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $user_course, $context = 'view' ) {
		$data = array(
			'id'          => $user_course->get_id( $context ),
			'user_id'     => $user_course->get_user_id( $context ),
			'course'      => null,
			'type'        => $user_course->get_type( $context ),
			'status'      => $user_course->get_status( $context ),
			'started_at'  => masteriyo_rest_prepare_date_response( $user_course->get_date_start( $context ) ),
			'modified_at' => masteriyo_rest_prepare_date_response( $user_course->get_date_modified( $context ) ),
		);

		/**
		 * Filter user course data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data User Course data.
		 * @param \Masteriyo\Models\Models\UserCourse $user_course User Course object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_user_course_resource_array', $data, $user_course, $context );
	}
}
