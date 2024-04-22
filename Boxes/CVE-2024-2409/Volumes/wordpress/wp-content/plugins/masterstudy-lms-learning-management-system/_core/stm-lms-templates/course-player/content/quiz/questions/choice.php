<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var array $last_quiz
 * @var boolean $dark_mode
 * @var string $choice
 */

$is_multi_choice = 'multi' === $choice;
$input_type      = $is_multi_choice ? 'checkbox' : 'radio';

foreach ( $data['answers'] as $answer ) {
	$correctly    = false;
	$wrongly      = false;
	$answer_class = '';
	$full_answer  = ! empty( $answer['text_image']['url'] )
		? trim( rawurldecode( $answer['text'] . '|' . $answer['text_image']['url'] ) )
		: trim( rawurldecode( $answer['text'] ) );

	if ( $show_answers ) {
		$user_answer = $data['last_answers']['user_answer'] ?? '';

		if ( $is_multi_choice ) {
			$last_answers = ! empty( $user_answer )
				? array_map( 'rawurldecode', explode( ',', $user_answer ) )
				: array();
		} else {
			$last_answers = ! empty( $user_answer )
				? stripcslashes( $user_answer )
				: '';
		}

		$last_answers = $data['is_correct'] && $answer['isTrue']
			? $is_multi_choice ? array( $full_answer ) : $full_answer
			: $last_answers;

		$is_correct = $is_multi_choice
			? in_array( $full_answer, $last_answers, true )
			: $full_answer === $last_answers;

		$correctly    = $is_correct && $answer['isTrue'];
		$wrongly      = $is_correct && ! $answer['isTrue'];
		$show_correct = ! $is_correct && $answer['isTrue'] && $data['show_correct_answer'];

		$answer_class = implode(
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
	<div class="masterstudy-course-player-answer <?php echo esc_attr( $data['has_image_question'] ? 'masterstudy-course-player-answer_image' : '' ); ?> <?php echo esc_attr( $answer_class ); ?> <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-answer_show-answers' : '' ); ?>">
		<div class="masterstudy-course-player-answer__input">
			<input type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $data['id'] ); ?><?php echo 'multi' === $choice ? '[]' : ''; ?>" value="<?php echo wp_kses_post( htmlspecialchars( $full_answer ?? '' ) ); ?>"/>
			<span class="masterstudy-course-player-answer__<?php echo esc_attr( $input_type ); ?> <?php echo esc_attr( ( $correctly || $wrongly ) ? "masterstudy-course-player-answer__{$input_type}_checked" : '' ); ?>"></span>
			<?php if ( $data['has_image_question'] ) { ?>
				<img src="<?php echo esc_url( ! empty( $answer['text_image']['url'] ) ? $answer['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>" class="masterstudy-course-player-answer__image"/>
				<?php
				if ( ! empty( $answer['explain'] ) && $show_answers && ! empty( $last_quiz ) ) {
					?>
					<div class="masterstudy-course-player-answer__hint">
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
			}
			?>
		</div>
		<div class="masterstudy-course-player-answer__wrapper">
			<?php if ( ! empty( $answer['text'] ) ) { ?>
				<div class="masterstudy-course-player-answer__text">
					<?php echo wp_kses_post( $answer['text'] ); ?>
				</div>
				<?php
			}

			if ( ! empty( $answer['explain'] ) && ! $data['has_image_question'] && $show_answers && ! empty( $last_quiz ) ) {
				?>
				<div class="masterstudy-course-player-answer__hint">
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
				<?php
			}

			if ( $show_answers && ! $data['has_image_question'] ) {
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
