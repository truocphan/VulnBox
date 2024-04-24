<?php
/**
 * Resource handler for Quiz data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Quiz data.
 *
 * @since 1.6.9
 */
class QuizResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Quiz $quiz
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $quiz, $context = 'view' ) {
		$section = masteriyo_get_section( $quiz->get_parent_id( $context ) );
		$course  = masteriyo_get_course( $quiz->get_course_id( $context ) );

		/**
		 * Filters short description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $short_description The short description.
		 */
		$short_description = 'view' === $context ? apply_filters( 'masteriyo_short_description', $quiz->get_short_description() ) : $quiz->get_short_description();

		$data = array(
			'id'                                => $quiz->get_id(),
			'name'                              => wp_specialchars_decode( $quiz->get_name( $context ) ),
			'slug'                              => $quiz->get_slug( $context ),
			'permalink'                         => $quiz->get_permalink(),
			'preview_link'                      => $quiz->get_preview_link(),
			'parent_id'                         => $quiz->get_parent_id( $context ),
			'course_id'                         => $quiz->get_course_id( $context ),
			'course_name'                       => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'menu_order'                        => $quiz->get_menu_order( $context ),
			'parent_menu_order'                 => $section ? $section->get_menu_order( $context ) : 0,
			'status'                            => $quiz->get_status( $context ),
			'description'                       => 'view' === $context ? wpautop( do_shortcode( $quiz->get_description() ) ) : $quiz->get_description( $context ),
			'short_description'                 => $short_description,
			'date_created'                      => masteriyo_rest_prepare_date_response( $quiz->get_date_created( $context ) ),
			'date_modified'                     => masteriyo_rest_prepare_date_response( $quiz->get_date_modified( $context ) ),
			'pass_mark'                         => $quiz->get_pass_mark( $context ),
			'full_mark'                         => $quiz->get_full_mark( $context ),
			'duration'                          => $quiz->get_duration( $context ),
			'attempts_allowed'                  => $quiz->get_attempts_allowed( $context ),
			'questions_display_per_page'        => $quiz->get_questions_display_per_page( $context ),
			'questions_display_per_page_global' => masteriyo_get_setting( 'quiz.styling.questions_display_per_page' ),
			'questions_count'                   => $quiz->get_questions_count(),
		);

		/**
		 * Filter quiz data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Quiz data.
		 * @param \Masteriyo\Models\Quiz $quiz Quiz object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_quiz_resource_array', $data, $quiz, $context );
	}
}
