<?php

new STM_LMS_User_Manager_User_Assignments();

class STM_LMS_User_Manager_User_Assignments {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_dashboard_get_student_assignments', array( $this, 'student_assignments' ) );
	}

	public function student_assignments() {
		check_ajax_referer( 'stm_lms_dashboard_get_student_assignments', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['student_id'] ) || empty( $data['assignment_id'] ) ) {
			die;
		}

		$course_id     = intval( $data['course_id'] );
		$student_id    = intval( $data['student_id'] );
		$assignment_id = intval( $data['assignment_id'] );

		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'stm-user-assignment',
			'post_status'    => array(
				'pending',
				'publish',
			),
			'meta_key'       => 'try_num',
			'orderby'        => 'meta_value title',
			'order'          => 'ASC',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'assignment_id',
					'value'   => $assignment_id,
					'compare' => '=',
				),
				array(
					'key'     => 'student_id',
					'value'   => $student_id,
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		$posts = array();

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$id                  = get_the_ID();
				$post                = array();
				$post['id']          = $id;
				$post['post_status'] = get_post_status( $id );
				$post['title']       = get_the_title();
				$post['meta']        = STM_LMS_Helpers::simplify_meta_array( get_post_meta( get_the_ID() ) );
				$post['content']     = get_the_content();

				$posts[] = $post;
			}
		}

		wp_send_json(
			array(
				'assignments' => $posts,
				'title'       => get_the_title( $course_id ),
				'user'        => STM_LMS_User::get_current_user( $student_id ),
				'instructor'  => STM_LMS_User::get_current_user( get_post_field( 'post_author', $course_id ), null, true ),
			)
		);

	}
}
