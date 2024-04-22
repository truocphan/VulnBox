<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

foreach ( $data['answers'] as $answer ) {
	$correctly    = false;
	$wrongly      = false;
	$show_correct = false;

	if ( $show_answers ) {
		$last_answers = $data['is_correct'] && $answer['isTrue'] ? $answer['text'] : $data['last_answers']['user_answer'] ?? '';

		$correctly    = $answer['text'] === $last_answers && $answer['isTrue'];
		$wrongly      = $answer['text'] === $last_answers && ! $answer['isTrue'];
		$show_correct = $answer['text'] !== $last_answers && $answer['isTrue'] && $data['show_correct_answer'];

		$classes = implode(
			' ',
			array_filter(
				array(
					$correctly || $show_correct ? 'masterstudy-course-player-answer_correct' : '',
					$wrongly ? 'masterstudy-course-player-answer_wrong' : '',
				)
			)
		);
	}
	?>
	<div class="masterstudy-course-player-answer <?php echo esc_attr( $classes ?? '' ); ?> <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-answer_show-answers' : '' ); ?>">
		<div class="masterstudy-course-player-answer__input">
			<input type="radio" name="<?php echo esc_attr( $data['id'] ); ?>" value="<?php echo esc_attr( $answer['text'] ); ?>"/>
			<span class="masterstudy-course-player-answer__radio <?php echo esc_attr( ( $correctly || $wrongly ) ? 'masterstudy-course-player-answer__radio_checked' : '' ); ?>"></span>
		</div>
		<div class="masterstudy-course-player-answer__wrapper">
			<?php if ( ! empty( $answer['text'] ) ) { ?>
				<div class="masterstudy-course-player-answer__text">
					<?php echo esc_html( $answer['text'] ); ?>
				</div>
				<?php
			}
			if ( $show_answers ) {
				if ( $correctly ) {
					?>
					<div class="masterstudy-course-player-answer__status-correct">
						<span class="masterstudy-correctly"></span>
					</div>
					<?php
				} elseif ( $wrongly ) {
					?>
					<div class="masterstudy-course-player-answer__status-wrong">
						<span class="masterstudy-wrongly"></span>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<?php
}
