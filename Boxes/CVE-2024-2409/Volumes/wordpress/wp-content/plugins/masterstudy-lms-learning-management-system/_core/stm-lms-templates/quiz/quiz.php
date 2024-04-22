<?php
/***
 * @var $questions
 * @var $last_quiz
 * @var $passed
 * @var $post_id
 * @var $item_id
 * @var $last_quiz
 * @var $source
 */

if ( ! empty( $questions ) ) :

	$quiz_style      = STM_LMS_Quiz::get_style( $item_id );
	$questions_array = explode( ',', $questions );

	$args          = array(
		'post_type'      => 'stm-questions',
		'posts_per_page' => - 1,
		'post__in'       => $questions_array,
		'orderby'        => 'post__in',
	);
	$random        = get_post_meta( $item_id, 'random_questions', true );
	$passing_grade = get_post_meta( $item_id, 'passing_grade', true );

	if ( ! empty( $random ) && 'on' === $random ) {
		$args['orderby'] = 'rand';
	}

	$q = new WP_Query( $args );
	if ( $q->have_posts() ) :
		$user = apply_filters( 'user_answers__user_id', STM_LMS_User::get_current_user(), $source );

		/* get quantity of quiz's questions */
		$questions_quantity = count( $questions_array );

		/* get quantity of quiz's questions if quiz has bank of questions */
		$quiz_info = stm_lms_get_user_quizzes( $user['id'], $item_id );
		if ( ! empty( $quiz_info ) ) {
			$quiz_info_last_element = end( $quiz_info );
			$sequency               = json_decode( $quiz_info_last_element['sequency'], true );

			foreach ( $questions_array as $question_id ) {
				if ( ! empty( $sequency[ $question_id ] ) ) {
					--$questions_quantity;
					$questions_quantity += count( $sequency[ $question_id ] );
				}
			}
		}

		/* get user's last answers */
		$last_answers = stm_lms_get_quiz_latest_answers(
			$user['id'],
			$item_id,
			$questions_quantity,
			array(
				'question_id',
				'user_answer',
				'correct_answer',
			)
		);
		$last_answers = STM_LMS_Helpers::set_value_as_key( $last_answers, 'question_id' ); ?>

		<?php
		if ( STM_LMS_Quiz::show_answers( $item_id ) ) :
			STM_LMS_Templates::show_lms_template( 'quiz/circle_result', compact( 'last_quiz', 'passing_grade' ) );
		endif;
		?>

		<?php if ( ! STM_LMS_Quiz::show_answers( $item_id ) && empty( $last_quiz ) ) : ?>
		<a href="#" class="btn btn-default stm_lms_start_quiz">
			<?php esc_html_e( 'Start Quiz', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
			<?php
		endif;

		STM_LMS_Templates::show_lms_template( "quiz/style/{$quiz_style}", compact( 'post_id', 'item_id', 'last_answers', 'q' ) );

		STM_LMS_Templates::show_lms_template( 'quiz/timer', compact( 'q', 'item_id' ) );

		wp_reset_postdata();

	endif;
endif;
