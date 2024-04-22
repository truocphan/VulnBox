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
$question_id = get_the_ID();

stm_lms_register_style( 'fill_the_gap' );

$show_correct_answer = get_post_meta( $item_id, 'correct_answer', true );
if ( ! empty( $answers[0] ) && ! empty( $answers[0]['text'] ) ) :

	$user_answer = ( ! empty( $user_answer['user_answer'] ) ) ? explode( ',', $user_answer['user_answer'] ) : array();
	$text        = $answers[0]['text'];
	$matches     = stm_lms_get_string_between( $text, '|', '|' );
	$inputs      = array();

	if ( ! empty( $matches ) ) {
		foreach ( $matches as $match_index => $match ) {

			$match_answer                = stripslashes( rawurldecode( $match['answer'] ) );
			$user_answer[ $match_index ] = ( isset( $user_answer[ $match_index ] ) ) ? stripslashes( rawurldecode( $user_answer[ $match_index ] ) ) : null;

			$width = 'width: ' . ( strlen( $match_answer ) * 8 + 16 ) . 'px';
			$name  = "{$question_id}[{$match_index}]";

			$correct = ( isset( $user_answer[ $match_index ] ) && strtolower( $match_answer ) === strtolower( $user_answer[ $match_index ] ) ) ? 'correct' : 'incorrect';
			if ( ! isset( $user_answer[ $match_index ] ) || empty( $user_answer[ $match_index ] ) ) {
				$correct                     = 'incorrect empty';
				$user_answer[ $match_index ] = '';
			}
			if ( ! $show_correct_answer ) {
				$inputs[ $match_index ] = "<div class='fill_the_gap_check {$correct}'>{$user_answer[$match_index]}</div>";
			} else {
				$inputs[ $match_index ] = "<div class='fill_the_gap_check {$correct}'>{$match_answer}</div>";
			}
		}
	}

	$matches = array_map(
		function( $match ) {
			return "|{$match['answer']}|";
		},
		$matches
	);

	foreach ( $matches as $key => $match ) {
		$text = stm_lms_str_replace_first( $match, $inputs[ $key ], $text );
	}

	?>

	<div class="stm_lms_question_item_fill_the_gap">
		<?php echo wp_kses_post( str_replace( '|', '', $text ) ); ?>
	</div>

<?php endif; ?>
