<?php
/**
 * @var string $item_id
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $question_id
 */

$bank_question_id = $question_id;
$question_id      = get_the_ID();

if ( ! empty( $answers[0] ) && ! empty( $answers[0]['categories'] ) && ! empty( $answers[0]['number'] ) ) :?>
	<div class="stm_lms_question_bank">
		<?php
		$number                 = $answers[0]['number'];
		$categories             = wp_list_pluck( $answers[0]['categories'], 'slug' );
		$questions              = stm_lms_get_user_quizzes( get_current_user_id(), $item_id );
		$questions_last_element = end( $questions );
		$user_questions         = array();

		if ( ! empty( $questions_last_element['sequency'] ) ) {
			$sequency = json_decode( $questions_last_element['sequency'], true );
		}
		if ( ! empty( $sequency ) && ! empty( $sequency[ $bank_question_id ] ) ) {
			$user_questions = $sequency[ $bank_question_id ];
		}

		$args = array(
			'post_type'      => 'stm-questions',
			'posts_per_page' => $number,
			'meta_query'     => array(
				array(
					'key'     => 'type',
					'value'   => 'question_bank',
					'compare' => '!=',
				),
			),
			'tax_query'      => array(
				array(
					'taxonomy' => 'stm_lms_question_taxonomy',
					'field'    => 'slug',
					'terms'    => $categories,
				),
			),
		);

		if ( ! empty( $user_questions ) ) {
			$args['post__in']       = $user_questions;
			$args['posts_per_page'] = count( $user_questions );
			$args['orderby']        = 'post__in';
		}

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$question_id     = get_the_ID();
				$question_itself = get_the_title();
				$question        = STM_LMS_Helpers::parse_meta_field( $question_id );

				$number = $q->found_posts - 1;

				if ( empty( $question['type'] ) ) {
					$question['type'] = 'single_choice';
				}

				$question['user_answer'] = ( ! empty( $last_answers[ $question_id ] ) ) ? $last_answers[ $question_id ] : array();

				if ( ! empty( $question['type'] ) && ! empty( $question['answers'] ) && ! empty( $question_itself ) ) {
					STM_LMS_Templates::show_lms_template( 'questions/wrapper', array_merge( $question, compact( 'item_id', 'last_answers', 'number' ) ) );
				}
			}
		}

		wp_reset_postdata();
		?>
	</div>

	<?php
endif;
