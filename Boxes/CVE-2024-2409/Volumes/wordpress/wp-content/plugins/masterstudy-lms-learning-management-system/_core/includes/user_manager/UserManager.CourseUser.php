<?php

use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumRepository;

new STM_LMS_User_Manager_Course_User();

class STM_LMS_User_Manager_Course_User {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_dashboard_reset_student_progress', array( $this, 'reset_student_progress' ) );
		add_action( 'wp_ajax_stm_lms_dashboard_get_student_progress', array( $this, 'student_progress' ) );
		add_action( 'wp_ajax_stm_lms_dashboard_set_student_item_progress', array( $this, 'set_student_progress' ) );
	}

	public function reset_student_progress() {
		check_ajax_referer( 'stm_lms_dashboard_reset_student_progress', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['user_id'] ) || empty( $data['course_id'] ) ) {
			die;
		}

		$course_id  = intval( $data['course_id'] );
		$student_id = intval( $data['user_id'] );

		$curriculum = ( new CurriculumRepository() )->get_curriculum( $course_id );

		if ( empty( $curriculum['materials'] ) ) {
			die;
		}

		foreach ( $curriculum['materials'] as $material ) {
			switch ( $material['post_type'] ) {
				case 'stm-lessons':
					self::reset_lesson( $student_id, $course_id, $material['post_id'] );
					break;
				case 'stm-assignments':
					self::reset_assignment( $student_id, $course_id, $material['post_id'] );
					break;
				case 'stm-quizzes':
					self::reset_quiz( $student_id, $course_id, $material['post_id'] );
					break;
			}
		}

		stm_lms_reset_user_answers( $course_id, $student_id );

		STM_LMS_Course::update_course_progress( $student_id, $course_id, true );

		wp_send_json( self::_student_progress( $course_id, $student_id ) );
	}

	// phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	public static function _student_progress( $course_id, $student_id ) {
		$curriculum = ( new CurriculumRepository() )->get_curriculum( $course_id );

		foreach ( $curriculum['materials'] as &$material ) {
			$material = array_merge( $material, self::course_material_data( $material, $student_id, $course_id ) );
		}

		$user_stats = STM_LMS_Helpers::simplify_db_array(
			stm_lms_get_user_course(
				$student_id,
				$course_id,
				array(
					'current_lesson_id',
					'progress_percent',
				)
			)
		);
		if ( empty( $user_stats['current_lesson_id'] ) ) {
			$user_stats['current_lesson_id'] = STM_LMS_Lesson::get_first_lesson( $course_id );
		}

		$lesson_type = get_post_meta( $user_stats['current_lesson_id'], 'type', true );
		if ( empty( $lesson_type ) ) {
			$lesson_type = 'text';
		}

		$user_stats['lesson_type'] = $lesson_type;

		$curriculum = array_merge( $user_stats, $curriculum );

		$curriculum['user']         = STM_LMS_User::get_current_user( $student_id );
		$curriculum['course_title'] = get_the_title( $course_id );

		return $curriculum;
	}

	public function student_progress() {
		check_ajax_referer( 'stm_lms_dashboard_get_student_progress', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['user_id'] ) || empty( $data['course_id'] ) ) {
			die;
		}

		$course_id  = intval( $data['course_id'] );
		$student_id = intval( $data['user_id'] );

		wp_send_json( self::_student_progress( $course_id, $student_id ) );
	}

	public function set_student_progress() {
		check_ajax_referer( 'stm_lms_dashboard_set_student_item_progress', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['user_id'] ) || empty( $data['course_id'] ) || empty( $data['item_id'] ) ) {
			die;
		}

		$course_id  = intval( $data['course_id'] );
		$student_id = intval( $data['user_id'] );
		$item_id    = intval( $data['item_id'] );
		$completed  = boolval( $data['completed'] );

		/*For various item types*/
		/*Check item in curriculum*/
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );

		if ( empty( $course_materials ) ) {
			die;
		}

		if ( ! in_array( $item_id, $course_materials, true ) ) {
			die;
		}

		switch ( get_post_type( $item_id ) ) {
			case 'stm-lessons':
				self::complete_lesson( $student_id, $course_id, $item_id );
				break;
			case 'stm-assignments':
				self::complete_assignment( $student_id, $course_id, $item_id, $completed );
				break;
			case 'stm-quizzes':
				self::complete_quiz( $student_id, $course_id, $item_id, $completed );
				break;
		}

		STM_LMS_Course::update_course_progress( $student_id, $course_id );

		wp_send_json( self::_student_progress( $course_id, $student_id ) );
	}

	public static function complete_lesson( $user_id, $course_id, $lesson_id ) {
		$user_lesson = stm_lms_get_user_lesson( $user_id, $course_id, $lesson_id );

		if ( ! empty( $user_lesson ) ) {
			stm_lms_delete_user_lesson( $user_id, $course_id, $lesson_id );
		} else {
			$end_time   = time();
			$start_time = get_user_meta( $user_id, "stm_lms_course_started_{$course_id}_{$lesson_id}", true );
			if ( empty( $start_time ) ) {
				$start_time = time();
			}
			stm_lms_add_user_lesson( compact( 'user_id', 'course_id', 'lesson_id', 'start_time', 'end_time' ) );
		}
	}

	public static function complete_assignment( $user_id, $course_id, $lesson_id, $completed ) {
		$user = STM_LMS_User::get_current_user( $user_id );

		$assignment_name = get_the_title( $lesson_id );

		$assignment_try = STM_LMS_Assignments::number_of_assignments( $lesson_id ) + 1;

		$new_assignment = array(
			'post_type'   => 'stm-user-assignment',
			'post_status' => 'publish',
			'post_title'  => "{$user['login']} on \"{$assignment_name}\"",
		);

		$assignment_id = wp_insert_post( $new_assignment );

		update_post_meta( $assignment_id, 'try_num', $assignment_try );
		update_post_meta( $assignment_id, 'start_time', time() * 1000 );
		update_post_meta( $assignment_id, 'status', '' );
		update_post_meta( $assignment_id, 'assignment_id', $lesson_id );
		update_post_meta( $assignment_id, 'student_id', $user_id );
		update_post_meta( $assignment_id, 'course_id', $course_id );
		$status         = $completed ? 'passed' : 'not_passed';
		$editor_comment = ( $completed ) ? esc_html__( 'Approved by admin', 'masterstudy-lms-learning-management-system' ) : esc_html__( 'Declined by admin', 'masterstudy-lms-learning-management-system' );

		update_post_meta( $assignment_id, 'status', $status );
		update_post_meta( $assignment_id, 'editor_comment', $editor_comment );
	}

	public static function complete_quiz( $user_id, $course_id, $quiz_id, $completed ) {
		if ( ! $completed ) {
			$progress = 0;
			$status   = 'failed';
			self::reset_quiz( $user_id, $course_id, $quiz_id );
			stm_lms_reset_user_answers( $course_id, $user_id );
		} else {
			$progress = 100;
			$status   = 'passed';
			stm_lms_add_user_quiz( compact( 'user_id', 'course_id', 'quiz_id', 'progress', 'status' ) );
		}
	}

	public static function course_material_data( $material, $student_id, $course_id ) {
		$previous_completed = ( isset( $completed ) ) ? $completed : 'first';
		$has_preview        = STM_LMS_Lesson::lesson_has_preview( $material['post_id'] );

		$user      = STM_LMS_User::get_current_user( $student_id );
		$user_id   = $user['id'];
		$duration  = '';
		$questions = '';
		$quiz_info = array();

		if ( 'stm-quizzes' === $material['post_type'] ) {
			$type      = 'quiz';
			$quiz_info = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_quizzes( $user_id, $material['post_id'], array( 'progress' ) ) );
			$completed = STM_LMS_Quiz::quiz_passed( $material['post_id'], $user_id );

			$q = get_post_meta( $material['post_id'], 'questions', true );
			if ( ! empty( $q ) ) :
				/* translators: %s: Post Type Label */
				$questions = sprintf(
					/* translators: %s: Count of Questions */
					_n(
						'%s question',
						'%s questions',
						count(
							explode(
								',',
								$q
							)
						),
						'masterstudy-lms-learning-management-system'
					),
					count(
						explode(
							',',
							$q
						)
					)
				);
			endif;

		} elseif ( 'stm-assignments' === $material['post_type'] ) {
			$type      = 'assignment';
			$completed = class_exists( 'STM_LMS_Assignments' ) ? STM_LMS_Assignments::has_passed_assignment( $material['post_id'], $student_id ) : false;
			$completed = ( ! empty( $completed ) );
		} else {
			$completed = STM_LMS_Lesson::is_lesson_completed( $user_id, $course_id, $material['post_id'] );
			$type      = get_post_meta( $material['post_id'], 'type', true );
			$duration  = get_post_meta( $material['post_id'], 'duration', true );
		}

		if ( empty( $type ) ) {
			$type = 'lesson';
		}

		if ( empty( $duration ) ) {
			$duration = '';
		}

		$locked = str_replace(
			'prev-status-',
			'',
			apply_filters( 'stm_lms_prev_status', "{$previous_completed}", $course_id, $material['post_id'], $user_id )
		);

		$locked = ( empty( $locked ) );

		return compact( 'type', 'quiz_info', 'locked', 'completed', 'has_preview', 'duration', 'questions' );
	}


	/*RESET ITEMS*/
	public static function reset_lesson( $user_id, $course_id, $lesson_id ) {
		stm_lms_delete_user_lesson( $user_id, $course_id, $lesson_id );
	}

	public static function reset_quiz( $user_id, $course_id, $quiz_id ) {
		stm_lms_delete_user_quiz( $user_id, $course_id, $quiz_id );
	}

	public static function reset_assignment( $user_id, $course_id, $assignment_id ) {
		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'stm-user-assignment',
			'post_status'    => array(
				'pending',
				'publish',
				'draft',
			),
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'course_id',
					'value'   => $course_id,
					'compare' => '=',
				),
				array(
					'key'     => 'assignment_id',
					'value'   => $assignment_id,
					'compare' => '=',
				),
				array(
					'key'     => 'student_id',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				wp_delete_post( get_the_ID() );

			}
		}
	}
}
