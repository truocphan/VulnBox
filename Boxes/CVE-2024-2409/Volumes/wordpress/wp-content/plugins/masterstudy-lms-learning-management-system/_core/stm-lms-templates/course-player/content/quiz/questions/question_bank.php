<?php
/**
 * @var array $data
 * @var array $last_answers
 * @var boolean $show_answers
 * @var string $quiz_style
 * @var array $question_banks
 * @var int $item_id
 * @var boolean $dark_mode
 */

$question_bank = $question_banks[ $data['id'] ] ?? null;

if ( ! empty( $question_bank ) && $question_bank->have_posts() ) { ?>
	<div class="masterstudy-course-player-question-bank">
		<?php
		while ( $question_bank->have_posts() ) {
			$question_bank->the_post();

			$question_data = array(
				'id'      => get_the_ID(),
				'title'   => get_the_title(),
				'content' => str_replace( '../../', site_url() . '/', stm_lms_filtered_output( get_the_content() ) ),
			);
			$question      = array_merge( $question_data, STM_LMS_Helpers::parse_meta_field( $question_data['id'] ) );

			wp_reset_postdata();
			?>
			<input type="hidden" name="questions_sequency[<?php echo esc_attr( $data['id'] ); ?>][]" value="<?php echo esc_attr( $question['id'] ); ?>" />
			<?php
			if ( ! empty( $question['type'] ) && ! empty( $question['answers'] ) ) {
				STM_LMS_Templates::show_lms_template(
					'course-player/content/quiz/questions/main',
					array(
						'data'         => $question,
						'last_answers' => $last_answers,
						'show_answers' => $show_answers,
						'quiz_style'   => $quiz_style,
						'item_id'      => $item_id,
						'dark_mode'    => $dark_mode,
					)
				);
			}
		}
		?>
	</div>
	<?php
	global $ms_question_number;
	$ms_question_number--;
}
