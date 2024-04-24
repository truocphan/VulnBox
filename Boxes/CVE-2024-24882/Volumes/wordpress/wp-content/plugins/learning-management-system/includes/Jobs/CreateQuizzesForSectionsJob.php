<?php

namespace Masteriyo\Jobs;

use ThemeGrill\OpenAI\ChatGPT;

/**
 * Class CreateQuizzesForSectionsJob
 *
 * This class is responsible for handling the action hook related to the create_quizzes_for_sections_job
 *
 * @since 1.6.15
 *
 * @package Masteriyo\Jobs
 */
class CreateQuizzesForSectionsJob {
	/**
	 * Hook to run the job.
	 *
	 * @since 1.6.15
	 */
	const HOOK = 'masteriyo/job/create_quizzes_for_sections';

	/**
	 * Register the action hook handler
	 *
	 * @since 1.6.15
	 */
	public function register() {
		add_action( self::HOOK, array( $this, 'handle' ), 10, 6 );
	}

	/**
	 * Handle the action
	 *
	 * Create quizzes for the sections of a course.
	 *
	 * @since 1.6.15
	 *
	 * @param int $num_questions_per_quiz
	 * @param int $num_quizzes
	 * @param string $create_quiz  The type of quiz to create ('none', 'each_section', 'last_section').
	 * @param string $course_title The title of the course.
	 * @param string $course_idea  The main idea behind the course.
	 * @param int  $course_id      The course ID.
	 */
	public function handle( $num_questions_per_quiz, $num_quizzes, $create_quiz, $course_title, $course_idea, $course ) {

		$course = masteriyo_get_course( $course );

		if ( is_null( $course ) || is_wp_error( $course ) ) {
			return;
		}

		$chatgpt = ChatGPT::get_instance( masteriyo_get_setting( 'advance.openai.api_key' ) );

		if ( null === $chatgpt ) {
			return;
		}

		if ( 1 > $num_questions_per_quiz || 1 > $num_quizzes ) {
			return;
		}

		$sections = masteriyo_get_sections( array( 'course_id' => $course->get_id() ) );

		if ( ! count( $sections ) ) {
			return;
		}

		if ( 'last_section' === $create_quiz ) {
			$sections = array( end( $sections ) );
		}

		foreach ( $sections as $section ) {
			$sections_content_prompt = masteriyo_generate_section_quizzes_prompt( $section->get_name(), $course_title, $course_idea, $num_quizzes, $num_questions_per_quiz );
			$response_text           = masteriyo_openai_retry( array( $chatgpt, 'send_prompt' ), array( $sections_content_prompt ), 2 ); // Max retry time 2.

			if ( is_null( $response_text ) || is_wp_error( $response_text ) || empty( $response_text ) ) {
				continue;
			}

			$section_quizzes = is_string( $response_text ) ? json_decode( $response_text, true ) : $response_text;

			if ( ! isset( $section_quizzes['quizzes'] ) ) {
				continue;
			}

			$section_quizzes = $section_quizzes['quizzes'];

			if ( ! empty( $section_quizzes ) ) {
				foreach ( $section_quizzes as $section_quiz ) {
					masteriyo_openai_create_quiz( $course, $section, $section_quiz );
				}
			}
		}
	}
}
