<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $question_view_type
 */

$question_id = get_the_ID();
$is_image    = (bool) ( ! empty( $question_view_type ) && 'image' == $question_view_type );

foreach ( $answers as $answer ) :
	$full_answer = ( ! empty( $answer['text_image']['url'] ) )
		? $answer['text'] . '|' . $answer['text_image']['url']
		: $answer['text'];
	?>
	<div class="stm-lms-single-answer">
		<label>
			<input type="radio"
					name="<?php echo esc_attr( $question_id ); ?>"
					value="<?php echo esc_attr( $full_answer ); ?>"/>
			<i class="fa fa-check"></i>
			<?php if ( $is_image ) {
				if ( ! empty( $answer['text_image']['url'] ) ) { ?>
					<img src="<?php echo esc_url( $answer['text_image']['url'] ); ?>"/>
				<?php } else { ?>
					<div class="empty-image">
						<i class="fa fa-image"></i>
					</div>
				<?php }
			} else  {
				echo wp_kses( $answer['text'], [] );
			} ?>
		</label>
		<?php if ( $is_image && ! empty( $answer['text'] ) ) { ?>
			<span><?php echo wp_kses( $answer['text'], [] ); ?></span>
		<?php } ?>
	</div>
<?php
endforeach;
