<?php
/**
 * Resource handler for Course question-answer model data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Course question-answer model data.
 *
 * @since 1.6.9
 */
class CourseQuestionAnswerResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $course_qa, $context = 'view' ) {
		$data = array(
			'id'              => $course_qa->get_id(),
			'course_id'       => $course_qa->get_course_id(),
			'course_name'     => '',
			'user_name'       => $course_qa->get_user_name( $context ),
			'user_email'      => $course_qa->get_user_email( $context ),
			'user_url'        => $course_qa->get_user_url( $context ),
			'user_avatar'     => $course_qa->get_avatar_url( $context ),
			'ip_address'      => $course_qa->get_ip_address( $context ),
			'created_at'      => masteriyo_rest_prepare_date_response( $course_qa->get_created_at( $context ) ),
			'content'         => $course_qa->get_content( $context ),
			'status'          => $course_qa->get_status( $context ),
			'agent'           => $course_qa->get_agent( $context ),
			'parent'          => $course_qa->get_parent( $context ),
			'user_id'         => $course_qa->get_user_id( $context ),
			'by_current_user' => $course_qa->is_created_by_current_user(),
			'sender'          => $course_qa->is_created_by_student() ? 'student' : 'instructor',
		);

		if ( 0 === $course_qa->get_parent( $context ) ) {
			$data['answers_count'] = $course_qa->get_answers_count();
		}

		$course = $course_qa->get_course();

		if ( $course ) {
			$data['course_name'] = $course->get_name();
		}

		/**
		 * Filter course question-answer model data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Course question-answer model data.
		 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa Course question-answer model object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_course_question_answer_resource_array', $data, $course_qa, $context );
	}
}
