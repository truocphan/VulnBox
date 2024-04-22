<?php
/*Point system*/
function stm_lms_point_system() {
	$points = array(
		'user_registered'             => array(
			'label'       => esc_html__( 'Registration', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get for registration.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Once only', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 50,
		),
		'course_purchased'            => array(
			'label'       => esc_html__( 'Course purchase', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get for buying a course', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 50,
		),
		'assignment_passed'           => array(
			'label'       => esc_html__( 'Passing assignment', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when an instructor approves the assignment', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 25,
		),
		'certificate_received'        => array(
			'label'       => esc_html__( 'Course completion', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they complete the course and get a certificate', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 25,
		),
		'quiz_passed'                 => array(
			'label'       => esc_html__( 'Passing quiz', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they pass the quiz', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 15,
		),
		'perfect_quiz'                => array(
			'label'       => esc_html__( 'Passing quiz with 100%', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they pass the quiz with a 100% grade.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 15,
		),
		'lesson_passed'               => array(
			'label'       => esc_html__( 'Lesson completion', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they complete each lesson.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 10,
		),
		'group_joined'                => array(
			'label'       => esc_html__( 'Joining group', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they join a group.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 5,
		),
		'friends_friendship_accepted' => array(
			'label'       => esc_html__( 'Making friends', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'The points users will get when they accept friendship from other students.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => 5,
		),
		'course_bought'               => array(
			'label' => esc_html__( 'Course Bought', 'masterstudy-lms-learning-management-system-pro' ),
		),
	);

	$points_settings = get_option( 'stm_lms_point_system_settings', array() );

	foreach ( $points as $point_slug => &$point_data ) {
		if ( empty( $point_data['score'] ) ) {
			continue;
		}

		$value = ( isset( $points_settings[ $point_slug ] ) ) ? $points_settings[ $point_slug ] : $point_data['score'];

		$point_data['score'] = $value;
	}

	if ( ! empty( $points_settings['affiliate_points'] ) && $points_settings['affiliate_points'] ) {
		$rate = STM_LMS_Point_System_Affiliate::affiliate_rate();

		$points['user_registered_affiliate'] = array(
			'label'       => esc_html__( 'User registered (Affiliate)', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'Share your affiliate link ang get points for users, using your link.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Once only', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => $points['user_registered']['score'] * $rate,
		);

		$points['course_purchased_affiliate'] = array(
			'label'       => esc_html__( 'Course purchased (Affiliate)', 'masterstudy-lms-learning-management-system-pro' ),
			'description' => esc_html__( 'Share your affiliate link ang get points for users, using your link.', 'masterstudy-lms-learning-management-system-pro' ),
			'repeat'      => esc_html__( 'Repeated', 'masterstudy-lms-learning-management-system-pro' ),
			'score'       => $points['course_purchased']['score'] * $rate,
		);
	}

	return $points;
}
