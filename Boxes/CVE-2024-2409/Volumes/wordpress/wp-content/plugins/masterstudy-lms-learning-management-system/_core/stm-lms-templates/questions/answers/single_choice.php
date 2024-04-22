<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $item_id
 * @var string $question_view_type
 */

$question_id         = get_the_ID();
$is_correct          = ( ! empty( $user_answer['correct_answer'] ) ) ? true : false;
$user_answer         = ( ! empty( $user_answer['user_answer'] ) ) ? stripcslashes( $user_answer['user_answer'] ) : '';
$show_correct_answer = get_post_meta( $item_id, 'correct_answer', true );
$is_image            = (bool) ( ! empty( $question_view_type ) && 'image' === $question_view_type );

if ( $is_correct ) {
	$user_answer = array();
}

foreach ( $answers as $answer ) :
	$answer_class     = array();
	$answer['isTrue'] = rest_sanitize_boolean( $answer['isTrue'] );
	$answer['isTrue'] = $answer['isTrue'] ?? false;
	$full_answer      = ( ! empty( $answer['text_image']['url'] ) )
		? $answer['text'] . '|' . $answer['text_image']['url']
		: $answer['text'];

	/*Get Right Answers*/
	if ( $is_correct ) {
		if ( $answer['isTrue'] ) {
			$user_answer = $full_answer;
		}
	}

	if ( $full_answer === $user_answer && $answer['isTrue'] ) {
		$answer_class[] = 'correctly_answered';
	}
	if ( $full_answer === $user_answer && ! $answer['isTrue'] ) {
		$answer_class[] = 'wrongly_answered';
	}

	if ( $full_answer !== $user_answer && $answer['isTrue'] && $show_correct_answer ) {
		$answer_class[] = 'correct_answer';
	}

	$answered = ! empty(
		array_intersect(
			array(
				'correctly_answered',
				'wrongly_answered',
			),
			$answer_class
		)
	);

	$is_image = (bool) ( ! empty( $question_view_type ) && 'image' === $question_view_type );
	?>
	<div class="stm-lms-single-answer <?php echo esc_attr( implode( ' ', $answer_class ) ); ?>">
		<label>
			<input
			<?php
			if ( $answered ) {
				echo esc_attr( 'checked' ); }
			?>
				type="radio"
				disabled
				name="<?php echo esc_attr( $question_id ); ?>"
				value="<?php echo wp_kses_post( $full_answer ); ?>"/>
			<i class="fa fa-check"></i>

			<?php
			if ( $is_image ) {
				if ( ! empty( $answer['text_image']['url'] ) ) {
					?>
					<img src="<?php echo esc_url( $answer['text_image']['url'] ); ?>"/>
				<?php } else { ?>
					<div class="empty-image">
						<i class="fa fa-image"></i>
					</div>
					<?php
				}
			} else {
				echo wp_kses( $answer['text'], array() );
			}

			if ( ! empty( $answer['explain'] ) ) :
				?>
				<div class="stm-lms-single-answer__hint">
					<i class="fa fa-info"></i>
					<div class="stm-lms-single-answer__hint_text">
						<div class="inner">
							<?php echo wp_kses_post( $answer['explain'] ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</label>
		<?php if ( $is_image && ! empty( $answer['text'] ) ) { ?>
			<span><?php echo wp_kses( $answer['text'], array() ); ?></span>
		<?php } ?>
	</div>
<?php endforeach; ?>
