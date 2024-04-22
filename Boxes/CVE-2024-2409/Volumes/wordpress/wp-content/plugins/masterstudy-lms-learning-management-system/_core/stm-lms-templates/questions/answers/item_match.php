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

stm_lms_register_style( 'item_match_question' );

$user_answers = array();
if ( ! empty( $user_answer['user_answer'] ) ) {
	$user_answers = explode( '[stm_lms_sep]', str_replace( '[stm_lms_item_match]', '', $user_answer['user_answer'] ) );
}

?>
<div class="stm_lms_question_item_match">

	<div class="stm_lms_question_item_match_row">

		<div class="stm_lms_question_item_match_col">
			<div class="stm_lms_question_item_match__questions">
				<?php foreach ( $answers as $answer ) : ?>
					<div class="stm_lms_question_item_match__single">
						<?php echo wp_kses_post( $answer['question'] ?? '' ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="stm_lms_question_item_match_col">
			<div class="stm_lms_question_item_match__answers">
				<?php
				foreach ( $answers as $i => $correct_answer ) :
					if ( empty( $user_answers[ $i ] ) ) {
						?>
						<div class="stm_lms_question_item_match__answer incorrect">

							<div class="stm_lms_question_item_match__match"></div>

							<?php if ( ! empty( $correct_answer['explain'] ) ) : ?>
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

						<?php
						continue;
					}

					$is_correct = ( strtolower( $user_answers[ $i ] ) === strtolower( $correct_answer['text'] ) ) ? 'correct' : 'incorrect';
					?>
					<div class="stm_lms_question_item_match__answer <?php echo esc_attr( $is_correct ); ?>">
						<?php if ( ! empty( $user_answers[ $i ] ) ) : ?>
							<div class="stm_lms_question_item_match__match">
								<?php echo esc_html( stripslashes( $user_answers[ $i ] ) ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $correct_answer['explain'] ) ) : ?>
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
				<?php endforeach; ?>
			</div>
		</div>

	</div>


</div>
