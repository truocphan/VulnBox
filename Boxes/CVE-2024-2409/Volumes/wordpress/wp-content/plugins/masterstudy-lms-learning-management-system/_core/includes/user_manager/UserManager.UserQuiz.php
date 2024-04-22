<?php

new STM_LMS_User_Manager_User_Quiz();

class STM_LMS_User_Manager_User_Quiz {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_dashboard_get_student_quizzes', array( $this, 'student_quizzes' ) );
		add_action( 'wp_ajax_stm_lms_dashboard_get_student_quiz', array( $this, 'student_quiz' ) );
	}

	public function student_quizzes() {
		check_ajax_referer( 'stm_lms_dashboard_get_student_quizzes', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['student_id'] ) || empty( $data['quiz_id'] ) ) {
			die;
		}

		$course_id  = intval( $data['course_id'] );
		$student_id = intval( $data['student_id'] );
		$quiz_id    = intval( $data['quiz_id'] );

		$quizzes = stm_lms_get_user_all_course_quizzes( $student_id, $course_id, $quiz_id );

		$quizzes = array_map(
			function ( $quiz ) {
				$quiz['title'] = get_the_title( $quiz['quiz_id'] );

				return $quiz;
			},
			$quizzes
		);

		wp_send_json( $quizzes );

	}

	public function student_quiz() {
		check_ajax_referer( 'stm_lms_dashboard_get_student_quiz', 'nonce' );

		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}

		$request_body = file_get_contents( 'php://input' );

		$data = json_decode( $request_body, true );

		if ( empty( $data['student_id'] ) || empty( $data['quiz_id'] ) ) {
			die;
		}

		$course_id    = intval( $data['course_id'] );
		$student_id   = intval( $data['student_id'] );
		$quiz_id      = intval( $data['quiz_id'] );
		$user_quiz_id = intval( $data['user_quiz_id'] );
		$attempt      = intval( $data['attempt'] );

		$questions = get_post_meta( $quiz_id, 'questions', true );

		$args = array(
			'post_type'      => 'stm-questions',
			'posts_per_page' => - 1,
			'post__in'       => explode( ',', $questions ),
			'orderby'        => 'post__in',
		);

		$item_id = $quiz_id;

		$q = new WP_Query( $args );

		ob_start(); ?>

		<link rel="stylesheet" href="<?php echo esc_url( STM_LMS_URL . '/assets/css/parts/quiz.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>"/>
		<link rel="stylesheet" href="<?php echo esc_url( STM_LMS_URL . '/assets/css/parts/keywords_question.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>"/>
		<link rel="stylesheet" href="<?php echo esc_url( STM_LMS_URL . '/assets/css/parts/item_match_question.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>"/>
		<link rel="stylesheet" href="<?php echo esc_url( STM_LMS_URL . '/assets/css/parts/image_match_question.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>"/>
		<link rel="stylesheet" href="<?php echo esc_url( STM_LMS_URL . '/assets/css/parts/fill_the_gap.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>"/>

		<?php
		if ( $q->have_posts() ) {
			$last_answers = stm_lms_get_quiz_attempt_answers(
				$student_id,
				$quiz_id,
				array(
					'question_id',
					'user_answer',
					'correct_answer',
				),
				$attempt
			);

			$last_answers = STM_LMS_Helpers::set_value_as_key( $last_answers, 'question_id' );
			?>

			<?php if ( empty( $last_answers ) ) { ?>
				<h4 class="empty_quiz"><?php esc_html_e( 'User has no answers on this quiz.', 'masterstudy-lms-learning-management-system' ); ?></h4>
				<?php
				wp_send_json( ob_get_clean() );
			}

			$question_index = 0;

			while ( $q->have_posts() ) {

				$q->the_post();
				$question_index ++;
				$show_answers = true;
				?>
				<span class="stm-lms-single_quiz__label"></span>
				<?php
				STM_LMS_Templates::show_lms_template(
					'quiz/question',
					compact( 'item_id', 'last_answers', 'question_index', 'show_answers' )
				);
			}
		}

		wp_send_json( ob_get_clean() );
	}
}
