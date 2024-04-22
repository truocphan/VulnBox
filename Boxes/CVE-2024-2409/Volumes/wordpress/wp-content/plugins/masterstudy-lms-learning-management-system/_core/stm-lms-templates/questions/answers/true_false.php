<?php
/**
 * @var string $type
 * @var array $answers
 * @var array $user_answer
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $item_id
 */


$question_id         = get_the_ID();
$show_correct_answer = get_post_meta( $item_id, 'correct_answer', true );
$is_correct          = ( ! empty( $user_answer['correct_answer'] ) ) ? true : false;
$user_answer         = ( isset( $user_answer['user_answer'] ) ) ? $user_answer['user_answer'] : '';

if ( $is_correct ) {
	$user_answer = array();
}

foreach ( $answers as $answer ) :
	$answer_class = array();

	/*Get Right Answers*/
	if ( $is_correct ) {
		if ( $answer['isTrue'] ) {
			$user_answer = $answer['text'];
		}
	}

	if ( $answer['text'] == $user_answer && $answer['isTrue'] ) {
		$answer_class[] = 'correctly_answered';
	}
	if ( $answer['text'] == $user_answer && ! $answer['isTrue'] ) {
		$answer_class[] = 'wrongly_answered';
	}
	if ( $answer['text'] != $user_answer && $answer['isTrue'] && $show_correct_answer ) {
		$answer_class[] = 'correct_answer';
	}

	$answered = ! empty( array_intersect( array( 'correctly_answered', 'wrongly_answered' ), $answer_class ) ) ? true : false;

	$answer['label'] = ( esc_html__( 'True', 'masterstudy-lms-learning-management-system' ) === $answer['text'] ) ?
		esc_html__( 'True', 'masterstudy-lms-learning-management-system' ) :
		esc_html__( 'False', 'masterstudy-lms-learning-management-system' );
	?>
	<div class="stm-lms-single-answer <?php echo esc_attr( implode( ' ', $answer_class ) ); ?>">
		<label>
			<input 
			<?php
			if ( $answered ) {
				echo esc_attr( 'checked' );}
			?>
					type="radio"
					disabled
					name="<?php echo esc_attr( $question_id ); ?>"
					value="<?php echo esc_attr( $answer['text'] ); ?>"/>
			<i class="fa fa-check"></i>
			<?php echo esc_html( $answer['label'] ); ?>
		</label>
	</div>
<?php endforeach; ?>
