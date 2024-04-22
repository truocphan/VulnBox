<?php
/**
 * @var string $type
 * @var array $answers
 * @var array $user_answer
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 */

$question_id = get_the_ID();

stm_lms_register_style( 'image_match_question' );

$user_answers = array();
if ( ! empty( $user_answer['user_answer'] ) ) {
	$user_answers = explode( '[stm_lms_sep]', str_replace( '[stm_lms_image_match]', '', $user_answer['user_answer'] ) );
}

?>

<div class="stm_lms_question_image_match <?php echo ! empty( $question_view_type ) ? esc_attr( $question_view_type ) : ''; ?>">

	<div class="stm_lms_question_image_match__questions">
		<?php foreach ( $answers as $i => $correct_answer ):
			$correct_url = ( ! empty( $correct_answer['text_image']['url'] ) ) ? '|' . esc_url( $correct_answer['text_image']['url'] ) : '';
			$is_not_empty = ! empty( $user_answers[ $i ] );
			$is_correct = ( isset( $user_answers[ $i ] ) && strtolower( $user_answers[ $i ] ) === strtolower( $correct_answer['text'] . $correct_url ) ) ? 'correct' : 'incorrect';
			?>
			<div class="stm_lms_question_image_match__row">
				<div class="stm_lms_question_image_match__single">
					<div class="image_match_answer">
						<div class="image_box <?php echo empty( $correct_answer['question_image']['url'] ) ? 'empty' : ''; ?>">
							<?php if ( ! empty( $correct_answer['question_image']['url'] ) ) { ?>
								<img src="<?php echo esc_url( $correct_answer['question_image']['url'] ); ?>"/>
							<?php } ?>
						</div>
						<?php if ( ! empty( $correct_answer['question'] ) ) { ?>
							<span><?php echo wp_kses_post( $correct_answer['question'] ); ?></span>
						<?php } ?>
					</div>
				</div>
				<div class="stm_lms_question_image_match__answer <?php echo $is_not_empty ? ' ' : 'empty '; echo esc_attr( $is_correct ); ?>">
					<?php if ( ! empty( $user_answers[ $i ] ) ):
						$answer = explode('|', $user_answers[ $i ]); ?>
						<div class="stm_lms_question_image_match__match">
							<div class="image_match_answer">
								<div class="image_box <?php echo empty( $answer[1] ) ? 'empty' : ''; ?>">
									<?php if ( ! empty( $answer[1] ) ) { ?>
										<img src="<?php echo esc_url( $answer[1] ); ?>"/>
									<?php } ?>
								</div>
								<?php if ( ! empty( $answer[0] ) ) { ?>
									<span><?php echo wp_kses_post( $answer[0] ); ?></span>
								<?php } ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $correct_answer['explain'] ) ): ?>
						<div class="stm-lms-single-answer__hint">
							<i class="fa fa-info"></i>
							<div class="stm-lms-single-answer__hint_text">
								<div class="inner">
									<?php echo wp_kses_post( $correct_answer['explain'] ); ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

</div>