<?php
/**
 * Triggers handler.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration;

use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

/**
 * Triggers handler.
 *
 * @since 1.6.15
 */
class Triggers {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.6.15
	 */
	protected function init_hooks() {
		add_filter( 'gamipress_activity_triggers', array( $this, 'add_triggers' ) );

		add_filter( 'gamipress_specific_activity_trigger_label', array( $this, 'add_specific_activity_trigger_label' ) );
		add_filter( 'gamipress_specific_activity_triggers', array( $this, 'add_specific_activity_triggers' ) );

		add_filter( 'gamipress_activity_trigger_label', array( $this, 'get_activity_trigger_label' ), 10, 3 );
		add_filter( 'gamipress_trigger_get_user_id', array( $this, 'get_user_id_for_trigger' ), 10, 3 );
		add_filter( 'gamipress_specific_trigger_get_id', array( $this, 'specific_trigger_get_id' ), 10, 3 );
		add_filter( 'gamipress_log_event_trigger_meta_data', array( $this, 'log_event_trigger_meta_data' ), 10, 5 );
	}

	/**
	 * Add triggers.
	 *
	 * @since 1.6.15
	 *
	 * @param array $triggers
	 *
	 * @return array
	 */
	public function add_triggers( $triggers ) {
		$triggers[ __( 'Masteriyo LMS', 'masteriyo' ) ] = array(
			// Quizzes
			'masteriyo_gamipress_complete_quiz'                   => __( 'Complete a quiz', 'masteriyo' ),
			'masteriyo_gamipress_complete_specific_quiz'          => __( 'Complete a specific quiz', 'masteriyo' ),
			'masteriyo_gamipress_complete_quiz_specific_course'   => __( 'Complete any quiz of a specific course', 'masteriyo' ),
			'masteriyo_gamipress_complete_quiz_course_category'   => __( 'Complete a quiz of a course of a category', 'masteriyo' ),

			// Pass
			'masteriyo_gamipress_pass_quiz'                       => __( 'Successfully pass a quiz', 'masteriyo' ),
			'masteriyo_gamipress_pass_specific_quiz'              => __( 'Successfully pass a specific quiz', 'masteriyo' ),
			'masteriyo_gamipress_pass_quiz_specific_course'       => __( 'Successfully pass a quiz of a specific course', 'masteriyo' ),
			'masteriyo_gamipress_pass_quiz_course_category'       => __( 'Successfully pass a quiz of a course of a category', 'masteriyo' ),

			// Fail
			'masteriyo_gamipress_fail_quiz'                       => __( 'Fail a quiz', 'masteriyo' ),
			'masteriyo_gamipress_fail_specific_quiz'              => __( 'Fail a specific quiz', 'masteriyo' ),
			'masteriyo_gamipress_fail_quiz_specific_course'       => __( 'Fail a quiz of a specific course', 'masteriyo' ),
			'masteriyo_gamipress_fail_quiz_course_category'       => __( 'Fail a quiz of a course of a category', 'masteriyo' ),

			// Lessons
			'masteriyo_gamipress_complete_lesson'                 => __( 'Complete a lesson', 'masteriyo' ),
			'masteriyo_gamipress_complete_specific_lesson'        => __( 'Complete a specific lesson', 'masteriyo' ),
			'masteriyo_gamipress_complete_lesson_specific_course' => __( 'Complete a lesson of a specific course', 'masteriyo' ),
			'masteriyo_gamipress_complete_lesson_course_category' => __( 'Complete a lesson of a course of a category', 'masteriyo' ),

			// Courses
			'masteriyo_gamipress_enroll_course'                   => __( 'Enroll a course', 'masteriyo' ),
			'masteriyo_gamipress_enroll_specific_course'          => __( 'Enroll a specific course', 'masteriyo' ),
			'masteriyo_gamipress_enroll_course_category'          => __( 'Enroll a course of a category', 'masteriyo' ),
			'masteriyo_gamipress_complete_course'                 => __( 'Complete a course', 'masteriyo' ),
			'masteriyo_gamipress_complete_specific_course'        => __( 'Complete a specific course', 'masteriyo' ),
			'masteriyo_gamipress_complete_course_category'        => __( 'Complete a course of a category', 'masteriyo' ),
		);

		return $triggers;
	}

	/**
	 * Add specific activity triggers labels.
	 *
	 * @since 1.6.15
	 *
	 * @param array $specific_activity_trigger_labels
	 *
	 * @return array
	 */
	public function add_specific_activity_trigger_label( $specific_activity_trigger_labels ) {
		// Quizzes
		$specific_activity_trigger_labels['masteriyo_gamipress_complete_specific_quiz']        = __( 'Complete the quiz %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_complete_quiz_specific_course'] = __( 'Complete any quiz of the course %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_pass_specific_quiz']            = __( 'Pass the quiz %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_pass_quiz_specific_course']     = __( 'Pass a quiz of the course %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_fail_specific_quiz']            = __( 'Fail the quiz %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_fail_quiz_specific_course']     = __( 'Fail a quiz of the course %s', 'masteriyo' );

		// Lessons
		$specific_activity_trigger_labels['masteriyo_gamipress_complete_specific_lesson']        = __( 'Complete the lesson %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_complete_lesson_specific_course'] = __( 'Complete a lesson of the course %s', 'masteriyo' );

		// Courses
		$specific_activity_trigger_labels['masteriyo_gamipress_enroll_specific_course']   = __( 'Enroll the course %s', 'masteriyo' );
		$specific_activity_trigger_labels['masteriyo_gamipress_complete_specific_course'] = __( 'Complete the course %s', 'masteriyo' );

		return $specific_activity_trigger_labels;
	}

	/**
	 * Add specific activity triggers.
	 *
	 * @since 1.6.15
	 *
	 * @param array $specific_activity_triggers
	 *
	 * @return array
	 */
	public function add_specific_activity_triggers( $specific_activity_triggers ) {
		// Quizzes
		$specific_activity_triggers['masteriyo_gamipress_complete_specific_quiz']        = array( PostType::QUIZ );
		$specific_activity_triggers['masteriyo_gamipress_complete_quiz_specific_course'] = array( PostType::COURSE );

		$specific_activity_triggers['masteriyo_gamipress_pass_specific_quiz']        = array( PostType::QUIZ );
		$specific_activity_triggers['masteriyo_gamipress_pass_quiz_specific_course'] = array( PostType::COURSE );

		$specific_activity_triggers['masteriyo_gamipress_fail_specific_quiz']        = array( PostType::QUIZ );
		$specific_activity_triggers['masteriyo_gamipress_fail_quiz_specific_course'] = array( PostType::COURSE );

		// Lessons
		$specific_activity_triggers['masteriyo_gamipress_complete_specific_lesson']        = array( PostType::LESSON );
		$specific_activity_triggers['masteriyo_gamipress_complete_lesson_specific_course'] = array( PostType::COURSE );

		// Courses
		$specific_activity_triggers['masteriyo_gamipress_enroll_specific_course']   = array( PostType::COURSE );
		$specific_activity_triggers['masteriyo_gamipress_complete_specific_course'] = array( PostType::COURSE );

		return $specific_activity_triggers;
	}

	/**
	 * Build custom activity trigger label.
	 *
	 * @since 1.6.15
	 *
	 * @param string $title
	 * @param integer $requirement_id
	 * @param array $requirement
	 *
	 * @return string
	 */
	public function get_activity_trigger_label( $title, $requirement_id, $requirement ) {
		$tutor_category = ( isset( $requirement['tutor_category'] ) ) ? $requirement['tutor_category'] : '';
		$term           = get_term_by( 'id', $tutor_category, Taxonomy::COURSE_CATEGORY );

		switch ( $requirement['trigger_type'] ) {
			case 'masteriyo_gamipress_complete_quiz_course_category':
				return sprintf( __( 'Complete a quiz of a course of "%s" category', 'masteriyo' ), $term->name );

			case 'masteriyo_gamipress_pass_quiz_course_category':
				return sprintf( __( 'Successfully pass a quiz of a course of %s category', 'masteriyo' ), $term->name );

			case 'masteriyo_gamipress_fail_quiz_course_category':
				return sprintf( __( 'Fail a quiz of a course of %s category', 'masteriyo' ), $term->name );

			case 'masteriyo_gamipress_complete_lesson_course_category':
				return sprintf( __( 'Complete a lesson of a course of %s category', 'masteriyo' ), $term->name );

			case 'masteriyo_gamipress_complete_course_category':
				return sprintf( __( 'Complete a course of %s category', 'masteriyo' ), $term->name );

			case 'masteriyo_gamipress_enroll_course_category':
				return sprintf( __( 'Enroll a course of %s category', 'masteriyo' ), $term->name );
		}

		return $title;
	}

	/**
	 * Get user for a given trigger action.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $user_id user ID to override.
	 * @param string $trigger Trigger name.
	 * @param array $args Passed trigger args.
	 *
	 * @return integer User ID.
	 */
	public function get_user_id_for_trigger( $user_id, $trigger, $args ) {
		switch ( $trigger ) {
			// Quizzes
			case 'masteriyo_gamipress_complete_quiz':
			case 'masteriyo_gamipress_complete_specific_quiz':
			case 'masteriyo_gamipress_complete_quiz_specific_course':
			case 'masteriyo_gamipress_pass_quiz':
			case 'masteriyo_gamipress_pass_specific_quiz':
			case 'masteriyo_gamipress_pass_quiz_specific_course':
			case 'masteriyo_gamipress_fail_quiz':
			case 'masteriyo_gamipress_fail_specific_quiz':
			case 'masteriyo_gamipress_fail_quiz_specific_course':
				// Lessons
			case 'masteriyo_gamipress_complete_lesson':
			case 'masteriyo_gamipress_complete_specific_lesson':
			case 'masteriyo_gamipress_complete_lesson_specific_course':
				// Courses
			case 'masteriyo_gamipress_enroll_course':
			case 'masteriyo_gamipress_enroll_specific_course':
			case 'masteriyo_gamipress_complete_course':
			case 'masteriyo_gamipress_complete_specific_course':
				// Categories
			case 'masteriyo_gamipress_complete_quiz_course_category':
			case 'masteriyo_gamipress_pass_quiz_course_category':
			case 'masteriyo_gamipress_fail_quiz_course_category':
			case 'masteriyo_gamipress_complete_lesson_course_category':
			case 'masteriyo_gamipress_complete_course_category':
			case 'masteriyo_gamipress_enroll_course_category':
				$user_id = $args[1];
				break;
		}

		return $user_id;
	}

	/**
	 * Get the id for a given specific trigger action.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $specific_id Specific ID.
	 * @param string $trigger Trigger name.
	 * @param array  $args Passed trigger args.
	 *
	 * @return integer Specific ID.
	 */
	public function specific_trigger_get_id( $specific_id, $trigger = '', $args = array() ) {
		switch ( $trigger ) {
			case 'masteriyo_gamipress_complete_specific_quiz':
			case 'masteriyo_gamipress_pass_specific_quiz':
			case 'masteriyo_gamipress_fail_specific_quiz':
			case 'masteriyo_gamipress_complete_specific_lesson':
			case 'masteriyo_gamipress_enroll_specific_course':
			case 'masteriyo_gamipress_complete_specific_course':
				$specific_id = $args[0];
				break;
			case 'masteriyo_gamipress_complete_quiz_specific_course':
			case 'masteriyo_gamipress_pass_quiz_specific_course':
			case 'masteriyo_gamipress_fail_quiz_specific_course':
			case 'masteriyo_gamipress_complete_lesson_specific_course':
				$specific_id = $args[2];
				break;
		}

		return $specific_id;
	}

	/**
	 * Extended meta data for event trigger logging.
	 *
	 * @since 1.6.15
	 *
	 * @param array $log_meta
	 * @param integer $user_id
	 * @param string $trigger
	 * @param integer $site_id
	 * @param array $args
	 *
	 * @return array
	 */
	public function log_event_trigger_meta_data( $log_meta, $user_id, $trigger, $site_id, $args ) {
		switch ( $trigger ) {
			// Quizzes
			case 'masteriyo_gamipress_complete_quiz':
			case 'masteriyo_gamipress_complete_specific_quiz':
			case 'masteriyo_gamipress_complete_quiz_specific_course':
			case 'masteriyo_gamipress_pass_quiz':
			case 'masteriyo_gamipress_pass_specific_quiz':
			case 'masteriyo_gamipress_pass_quiz_specific_course':
			case 'masteriyo_gamipress_fail_quiz':
			case 'masteriyo_gamipress_fail_specific_quiz':
			case 'masteriyo_gamipress_fail_quiz_specific_course':
				// Add the quiz and course IDs
				$log_meta['quiz_id']   = $args[0];
				$log_meta['course_id'] = $args[2];
				break;

			// Lessons
			case 'masteriyo_gamipress_complete_lesson':
			case 'masteriyo_gamipress_complete_specific_lesson':
			case 'masteriyo_gamipress_complete_lesson_specific_course':
				// Add the lesson and course IDs
				$log_meta['lesson_id'] = $args[0];
				$log_meta['course_id'] = $args[2];
				break;

			// Courses
			case 'masteriyo_gamipress_enroll_course':
			case 'masteriyo_gamipress_enroll_specific_course':
			case 'masteriyo_gamipress_complete_course':
			case 'masteriyo_gamipress_complete_specific_course':
				// Add the course ID
				$log_meta['course_id'] = $args[0];
				break;
		}

		return $log_meta;
	}
}
