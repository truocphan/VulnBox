<?php


add_action(
	'wp_ajax_stm_lms_get_google_classroom_courses',
	function () {

		check_ajax_referer( 'stm_lms_get_google_classroom_courses', 'nonce' );

		$google_classroom = STM_LMS_Google_Classroom::getInstance();

		wp_send_json( $google_classroom->courses() );

	}
);


add_action(
	'wp_ajax_stm_lms_get_google_classroom_course',
	function () {

		check_ajax_referer( 'stm_lms_get_google_classroom_course', 'nonce' );

		$course_id = intval( $_GET['course_id'] );

		$course = get_transient( "stm_lms_google_classroom_course_$course_id" );

		$google_classroom   = STM_LMS_Google_Classroom::getInstance();
		$imported_course_id = $google_classroom::imported_course_id( $course );

		do_action( 'stm_lms_google_classroom_course_imported', $course, $imported_course_id );

		$updateble_meta = array(
			'auditory',
			'ownerId',
			'code',
			'alternateLink',
			'courseState',
			'teacherGroupEmail',
			'courseGroupEmail',
			'calendarId',
			'courseMaterialSets',
			'section',
		);

		foreach ( $updateble_meta as $item ) {
			update_post_meta( $imported_course_id, $item, $course[ $item ] );
		}

		wp_send_json(
			array(
				'status'          => get_post_status( $imported_course_id ),
				'course_url'      => get_permalink( $imported_course_id ),
				'course_url_edit' => get_edit_post_link( $imported_course_id, 'normal' ),
			)
		);

	}
);

add_action(
	'wp_ajax_stm_lms_get_google_classroom_publish_course',
	function () {

		check_ajax_referer( 'stm_lms_get_google_classroom_publish_course', 'nonce' );

		$google_classroom = STM_LMS_Google_Classroom::getInstance();
		$course_id        = intval( $_GET['course_id'] );

		$course    = get_transient( "stm_lms_google_classroom_course_$course_id" );
		$course_id = $google_classroom->get_post_by_google_id( $course_id );

		wp_update_post(
			array(
				'ID'          => $course_id,
				'post_status' => 'publish',
				'post_date'   => $course['creationTime'],
			)
		);

		wp_send_json(
			array(
				array(
					'ID'                => $course_id,
					'post_status'       => 'publish',
					'post_date'         => $course['creationTime'],
					'post_date_gmt'     => $course['creationTime'],
					'post_modified'     => $course['updateTime'],
					'post_modified_gmt' => $course['updateTime'],
				),
				'status'          => get_post_status( $course_id ),
				'course_url_edit' => get_edit_post_link( $course_id, 'normal' ),
			)
		);

	}
);
