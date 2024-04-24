<?php

namespace Masteriyo\Jobs;

use ThemeGrill\OpenAI\ChatGPT;

/**
 * Class CreateCourseContentJob
 *
 * This class is responsible for handling the action hook related to the create_course_content_job
 *
 * @since 1.6.15
 *
 * @package Masteriyo\Jobs
 */
class CreateCourseContentJob {
	/**
	 * The unique identifier for scheduling and handling the create_course_content_job action.
	 *
	 * @since 1.6.15
	 */
	const NAME = 'masteriyo/job/create_course_content_job';

	/**
	 * Register the action hook handler
	 *
	 * @since 1.6.15
	 */
	public function register() {
		add_action( self::NAME, array( $this, 'handle' ), 10, 4 );
	}

	/**
	 * Handle the action.
	 *
	 * Create content for a course.
	 *
	 * @since 1.6.15
	 *
	 * @param  int $num_course_highlight_points
	 * @param string $course_title The title of the course.
	 * @param string $course_idea  The main idea behind the course.
	 * @param int  $course_id      The course ID.
	 */
	public function handle( $num_course_highlight_points, $course_title, $course_idea, $course ) {

		$course = masteriyo_get_course( $course );

		if ( is_null( $course ) || is_wp_error( $course ) ) {
			return;
		}

		$chatgpt = ChatGPT::get_instance( masteriyo_get_setting( 'advance.openai.api_key' ) );

		if ( null === $chatgpt ) {
			return;
		}

		if ( 1 > $num_course_highlight_points ) {
			return;
		}

		$lessons      = masteriyo_get_lessons( array( 'course_id' => $course->get_id() ) );
		$lesson_names = array();

		if ( count( $lessons ) ) {
			$lesson_names = array_filter(
				array_map(
					function( $lesson ) {
						if ( is_wp_error( $lesson ) || is_null( $lesson ) ) {
							return null;
						}
						return $lesson->get_name();
					},
					$lessons
				)
			);
		}

		$course_content_prompt = masteriyo_generate_course_content_prompt( $course_title, $course_idea, $lesson_names, 2, $num_course_highlight_points );
		$response_text         = masteriyo_openai_retry( array( $chatgpt, 'send_prompt' ), array( $course_content_prompt ), 2 ); // Max retry time 2.

		if ( is_null( $response_text ) || is_wp_error( $response_text ) || empty( $response_text ) ) {
			return;
		}

		$course_content = is_array( $response_text ) ? $response_text : json_decode( $response_text, true );

		$course_description = isset( $course_content['description'] ) ? wp_kses_post( $course_content['description'] ) : '';
		$course_highlights  = isset( $course_content['highlight_points'] ) ? wp_kses_post( $course_content['highlight_points'] ) : '';

		if ( $course_description ) {
			$course->set_description( $course_description );
		}

		if ( $course_highlights ) {
			$course->set_highlights( $course_highlights );
		}

		$course->save();
	}
}
