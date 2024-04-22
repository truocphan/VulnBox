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
	$user_answers = Question::filter_matching_user_answers( $data, 'stm_lms_image_match', true );
} elseif ( empty( $data['last_answers'] ) && $data['is_correct'] ) {
	$user_answers = $data['answers'];
}
?>

<div class="masterstudy-course-player-image-match <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-image-match_not-drag' : '' ); ?> <?php echo esc_attr( 'masterstudy-course-player-image-match_style-' . $data['view_type'] ); ?>">
	<?php
	foreach ( $data['answers'] as $i => $answer ) {
		if ( $show_answers ) {
			$data['correctly']    = isset( $user_answers[ $i ]['text_image']['url'] ) && trim( $user_answers[ $i ]['text_image']['url'] ) === trim( $answer['text_image']['url'] );
			$data['wrongly']      = ! isset( $user_answers[ $i ]['text_image']['url'] ) || trim( $user_answers[ $i ]['text_image']['url'] ) !== trim( $answer['text_image']['url'] );
			$data['answer_class'] = implode(
				' ',
				array_filter(
					array(
						$data['correctly'] ? 'masterstudy-course-player-image-match__question_correct' : '',
						$data['wrongly'] ? 'masterstudy-course-player-image-match__question_wrong' : '',
						'masterstudy-course-player-image-match__question_full',
					)
				)
			);
		}
		?>
		<div class="masterstudy-course-player-image-match__question <?php echo esc_attr( $data['answer_class'] ?? '' ); ?>">
			<div class="masterstudy-course-player-image-match__question-wrapper">
				<div class="masterstudy-course-player-image-match__question-content">
					<img src="<?php echo esc_url( ! empty( $answer['question_image']['url'] ) ? $answer['question_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
					<?php if ( ! empty( $answer['question'] ) ) { ?>
						<div class="masterstudy-course-player-image-match__question-text">
							<?php echo wp_kses_post( $answer['question'] ); ?>
						</div>
					<?php } ?>
				</div>
				<div class="masterstudy-course-player-image-match__question-answer-wrapper">
					<div class="masterstudy-course-player-image-match__question-answer">
						<?php if ( $show_answers && ! empty( $user_answers[ $i ] ) ) { ?>
							<div class="masterstudy-course-player-image-match__answer-item">
								<div class="masterstudy-course-player-image-match__answer-item-content">
									<div class="masterstudy-course-player-image-match__answer-item-image">
										<div class="masterstudy-course-player-image-match__answer-item-status">
											<?php if ( $data['correctly'] ) { ?>
												<span class="masterstudy-correctly"></span>
											<?php } elseif ( $data['wrongly'] ) { ?>
												<span class="masterstudy-wrongly"></span>
											<?php } ?>
										</div>
										<img src="<?php echo esc_url( ! empty( $user_answers[ $i ]['text_image']['url'] ) ? $user_answers[ $i ]['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
									</div>
									<div class="masterstudy-course-player-image-match__answer-item-text-wrapper">
										<?php if ( ! empty( $user_answers[ $i ]['text'] ) ) { ?>
											<div class="masterstudy-course-player-image-match__answer-item-text">
												<?php echo esc_html( $user_answers[ $i ]['text'] ); ?>
											</div>
											<?php
										}
										if ( ! empty( $user_answers[ $i ]['explain'] ) && ! empty( $last_quiz ) ) {
											?>
											<div class="masterstudy-course-player-image-match__answer-item-hint">
												<?php
												STM_LMS_Templates::show_lms_template(
													'components/hint',
													array(
														'content'   => $user_answers[ $i ]['explain'],
														'side'      => 'right',
														'dark_mode' => $dark_mode,
													)
												);
												?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php
						} elseif ( $show_answers && empty( $user_answers[ $i ] ) ) {
							?>
							<span class="masterstudy-course-player-image-match__question-answer-wrongly"></span>
						<?php } ?>
					</div>
					<span class="masterstudy-course-player-image-match__question-answer-drag-text <?php echo esc_attr( ( $show_answers ) ? 'masterstudy-course-player-image-match__question-answer-drag-text_hide' : '' ); ?>">
						<?php echo esc_html__( 'Drag answer here', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
	<input type="text" class="masterstudy-course-player-image-match__input" name="<?php echo esc_attr( $data['id'] ); ?>"/>
	<div class="masterstudy-course-player-image-match__answer <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-image-match__answer_hide' : '' ); ?>">
		<?php
		shuffle( $data['answers'] );
		foreach ( $data['answers'] as $answer ) {
			?>
			<div class="masterstudy-course-player-image-match__answer-item">
				<div class="masterstudy-course-player-image-match__answer-item-content">
					<div class="masterstudy-course-player-image-match__answer-item-image">
						<img src="<?php echo esc_url( ! empty( $answer['text_image']['url'] ) ? $answer['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
					</div>
					<div class="masterstudy-course-player-image-match__answer-item-container <?php echo esc_attr( empty( $answer['text'] ) ? 'masterstudy-course-player-image-match__answer-item-container_hide' : '' ); ?>">
						<div class="masterstudy-course-player-image-match__answer-item-drag"></div>
						<div class="masterstudy-course-player-image-match__question-answer-text-wrapper">
							<div class="masterstudy-course-player-image-match__answer-item-text">
								<?php echo esc_html( ! empty( $answer['text'] ) ? $answer['text'] : '' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
