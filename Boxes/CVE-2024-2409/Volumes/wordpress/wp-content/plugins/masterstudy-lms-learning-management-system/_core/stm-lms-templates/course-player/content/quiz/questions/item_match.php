<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var array $last_quiz
 * @var int $item_id
 * @var boolean $dark_mode
 */

use MasterStudy\Lms\Utility\Question;

if ( ! empty( $data['last_answers']['user_answer'] ) ) {
	$user_answers = Question::filter_matching_user_answers( $data, 'stm_lms_item_match' );
} elseif ( empty( $data['last_answers'] ) && $data['is_correct'] ) {
	$user_answers = $data['answers'];
}
?>

<div class="masterstudy-course-player-item-match <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-item-match_not-drag' : '' ); ?>">
	<?php
	foreach ( $data['answers'] as $i => $answer ) {
		$user_answer = $user_answers[ $i ] ?? null;
		if ( $show_answers ) {
			$data['correctly']    = isset( $user_answer['text'] ) && trim( $user_answer['text'] ) === trim( $answer['text'] );
			$data['wrongly']      = ! isset( $user_answer['text'] ) || trim( $user_answer['text'] ) !== trim( $answer['text'] );
			$data['answer_class'] = implode(
				' ',
				array_filter(
					array(
						$data['correctly'] ? 'masterstudy-course-player-item-match__question_correct' : '',
						$data['wrongly'] ? 'masterstudy-course-player-item-match__question_wrong' : '',
						'masterstudy-course-player-item-match__question_full',
					)
				)
			);
		}
		?>
		<div class="masterstudy-course-player-item-match__question <?php echo esc_attr( isset( $data['answer_class'] ) ? $data['answer_class'] : '' ); ?>">
			<div class="masterstudy-course-player-item-match__question-wrapper">
				<div class="masterstudy-course-player-item-match__question-content">
					<?php
					if ( array_key_exists( 'question', $answer ) ) {
						echo wp_kses_post( $answer['question'] );
					} else {
						echo esc_html__( 'The question was not set', 'masterstudy-lms-learning-management-system' );
					}
					?>
				</div>
				<div class="masterstudy-course-player-item-match__question-answer-wrapper">
					<div class="masterstudy-course-player-item-match__question-answer">
						<?php if ( $show_answers && ! empty( $user_answer ) ) { ?>
							<div class="masterstudy-course-player-item-match__answer-item">
								<div class="masterstudy-course-player-item-match__answer-item-wrapper">
									<div class="masterstudy-course-player-item-match__answer-item-drag">
										<?php if ( $data['correctly'] ) { ?>
											<span class="masterstudy-correctly"></span>
										<?php } elseif ( $data['wrongly'] ) { ?>
											<span class="masterstudy-wrongly"></span>
										<?php } ?>
									</div>
									<div class="masterstudy-course-player-item-match__answer-item-content">
										<?php echo esc_html( trim( $user_answer['text'] ?? '' ) ); ?>
									</div>
									<?php if ( ! empty( $user_answer['explain'] ) && $show_answers && ! empty( $last_quiz ) ) { ?>
										<div class="masterstudy-course-player-item-match__answer-item-hint">
											<?php
											STM_LMS_Templates::show_lms_template(
												'components/hint',
												array(
													'content' => $user_answer['explain'],
													'side' => 'right',
													'dark_mode' => $dark_mode,
												)
											);
											?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<span class="masterstudy-course-player-item-match__question-answer-text <?php echo esc_attr( ( $show_answers ) ? 'masterstudy-course-player-item-match__question-answer-text_hide' : '' ); ?>">
						<?php echo esc_html__( 'Drag answer here', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
	<input type="text" class="masterstudy-course-player-item-match__input" name="<?php echo esc_attr( $data['id'] ); ?>"/>
	<div class="masterstudy-course-player-item-match__answer <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-item-match__answer_hide' : '' ); ?>">
		<?php
		shuffle( $data['answers'] );
		foreach ( $data['answers'] as $answer ) {
			?>
			<div class="masterstudy-course-player-item-match__answer-item">
				<div class="masterstudy-course-player-item-match__answer-item-wrapper">
					<div class="masterstudy-course-player-item-match__answer-item-drag"></div>
					<div class="masterstudy-course-player-item-match__answer-item-content">
						<?php echo esc_html( trim( $answer['text'] ) ); ?>
					</div>
					<?php if ( ! empty( $answer['explain'] ) && $show_answers && ! empty( $last_quiz ) ) { ?>
						<div class="masterstudy-course-player-item-match__answer-item-hint">
							<?php
							STM_LMS_Templates::show_lms_template(
								'components/hint',
								array(
									'content'   => $answer['explain'],
									'side'      => 'right',
									'dark_mode' => $dark_mode,
								)
							);
							?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
