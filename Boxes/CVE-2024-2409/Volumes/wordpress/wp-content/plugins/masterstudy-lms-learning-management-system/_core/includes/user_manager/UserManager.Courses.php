<?php

new STM_LMS_User_Manager_Courses();

class STM_LMS_User_Manager_Courses {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_dashboard_get_courses_list', array( $this, 'course_list' ) );
	}

	public function course_list() {
		check_ajax_referer( 'stm_lms_dashboard_get_courses_list', 'nonce' );

		$args = apply_filters( 'stm_lms_archive_filter_args', array( 'posts_per_page' => 4 ) );

		$courses = STM_LMS_Instructor::get_courses( $args, true, STM_LMS_User_Manager_Interface::isInstructor() );

		$courses['posts'] = array_map(
			function ( $course ) {
				$course_id = $course['id'];

				$course['categories'] = stm_lms_get_terms_array( $course_id, 'stm_lms_course_taxonomy', 'name' );

				return $course;
			},
			$courses['posts']
		);

		wp_send_json( $courses );
	}

	public static function published() {
		$courses = wp_count_posts( 'stm-courses' );

		return $courses->publish ?? 0;
	}
}
