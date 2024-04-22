<?php

new STM_LMS_User_Manager_Course();

class STM_LMS_User_Manager_Course {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_dashboard_get_course_students', array( $this, 'students' ) );
		add_action( 'wp_ajax_stm_lms_dashboard_delete_user_from_course', array( $this, 'delete_user' ) );
	}

	public function students() {
		check_ajax_referer( 'stm_lms_dashboard_get_course_students', 'nonce' );

		$course_id = intval( $_GET['course_id'] );

		$data               = array_reverse( array_map( array( $this, 'map_students' ), stm_lms_get_course_users( $course_id ) ) );
		$coming_soon_emails = get_post_meta( $course_id, 'coming_soon_student_emails', true );

		if ( is_ms_lms_addon_enabled( 'coming_soon' ) && ! empty( $coming_soon_emails ) ) {
			$subscribed_user_emails = array_column( $coming_soon_emails, 'email' );

			$course_enrolled_emails  = array_column( array_column( $data, 'student' ), 'email' );
			$subscribed_guest_emails = array_diff( $subscribed_user_emails, $course_enrolled_emails );

			foreach ( $subscribed_guest_emails as $guest_email ) {
				$user       = get_user_by( 'email', $guest_email );
				$avatar_url = get_avatar_url( 'guest@example.com' );

				if ( $user ) {
					$avatar_url = get_avatar_url( $user->ID );
				}
				$avatar_img = "<img src='" . esc_url( $avatar_url ) . "' class='avatar' alt='User Avatar'>";

				if ( $user ) {
					$data[] = array(
						'course_id' => $course_id,
						'student'   => array(
							'id'     => $user->ID,
							'login'  => $user->user_login,
							'email'  => $guest_email,
							'avatar' => $avatar_img,
						),
					);
				} else {
					$data[] = array(
						'course_id' => $course_id,
						'student'   => array(
							'id'     => 0,
							'login'  => esc_html__( 'Guest', 'masterstudy-lms-learning-management-system' ),
							'email'  => $guest_email,
							'avatar' => $avatar_img,
						),
					);
				}
			}

			foreach ( $data as $key => $item ) {
				if ( $coming_soon_emails && is_array( $coming_soon_emails ) ) {
					$email_index = array_search( $item['student']['email'], array_column( $coming_soon_emails, 'email' ), true );

					if ( false !== $email_index ) {
						$data[ $key ]['subscribed']      = 'subscribed';
						$data[ $key ]['subscribed_time'] = $coming_soon_emails[ $email_index ]['time']->format( 'Y-m-d H:i:s' );
					}
				}
			}
		}

		$data['students']     = $data;
		$data['origin_title'] = html_entity_decode( get_the_title( $course_id ) );
		/* translators: %s: Course ID */
		$data['title'] = sprintf( esc_html__( 'Students of %s', 'masterstudy-lms-learning-management-system' ), html_entity_decode( get_the_title( $course_id ) ) );

		wp_send_json( $data );

	}

	public function map_students( $student_course ) {
		$user_id = $student_course['user_id'];

		$student_course['ago'] = stm_lms_time_elapsed_string( gmdate( 'Y-m-d\TH:i:s\Z', $student_course['start_time'] ) );

		$student_course['student'] = STM_LMS_User::get_current_user( $user_id );

		if ( empty( $student_course['student']['login'] ) ) {
			$student_course['student']['login'] = esc_html__( 'Deleted user', 'masterstudy-lms-learning-management-system' );
		}

		return $student_course;
	}

	public function delete_user() {
		check_ajax_referer( 'stm_lms_dashboard_delete_user_from_course', 'nonce' );

		$course_id        = intval( $_GET['course_id'] );
		$user_id          = intval( $_GET['user_id'] );
		$subscribed_email = sanitize_text_field( $_GET['user_email'] );

		if ( ! empty( $subscribed_email ) && is_ms_lms_addon_enabled( 'coming_soon' ) ) {
			$coming_soon_emails      = get_post_meta( $course_id, 'coming_soon_student_emails', true ) ?? array();
			$unsubscribe_email_index = array_search( $subscribed_email, array_column( $coming_soon_emails, 'email' ), true );

			unset( $coming_soon_emails[ $unsubscribe_email_index ] );
			update_post_meta( $course_id, 'coming_soon_student_emails', array_values( $coming_soon_emails ) );
		}

		if ( ! STM_LMS_Course::check_course_author( $course_id, get_current_user_id() ) ) {
			die;
		}

		stm_lms_get_delete_user_course( $user_id, $course_id );
		$meta = STM_LMS_Helpers::parse_meta_field( $course_id );

		if ( ! empty( $meta['current_students'] ) && $meta['current_students'] > 0 ) {
			update_post_meta( $course_id, 'current_students', -- $meta['current_students'] );
		}
	}
}
