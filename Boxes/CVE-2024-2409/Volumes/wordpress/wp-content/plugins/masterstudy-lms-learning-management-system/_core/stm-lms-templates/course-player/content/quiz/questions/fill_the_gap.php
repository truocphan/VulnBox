<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

use MasterStudy\Lms\Repositories\QuestionRepository;

if ( ! empty( $data['answers'][0]['text'] ) ) {
	$show_correct_answer = $data['show_correct_answer'];
	$answer_text         = $data['answers'][0]['text'];
	$data                = QuestionRepository::fill_the_gap_output_data( $data, $show_answers );
	?>
	<div class="masterstudy-course-player-fill-the-gap">
		<div class="masterstudy-course-player-fill-the-gap__questions <?php echo esc_html( $show_answers ? 'hidden' : '' ); ?>">
			<?php
			// Get questions
			$question = $answer_text;
			foreach ( $data['matches'] as $index => $match ) {
				$replace_question = '|' . $match['answer'] . '|';
				$question         = preg_replace_callback(
					'/' . preg_quote( $replace_question, '/' ) . '/',
					static function () use ( $data, $index ) {
						return $data['answer_field'][ $index ] ?? '';
					},
					$question,
					1
				);
			}
			$allowed_tags = array(
				'input' => array(
					'type'  => true,
					'name'  => true,
					'style' => true,
				),
			);

			echo wp_kses( $question, $allowed_tags );
			?>
		</div>
		<div class="masterstudy-course-player-fill-the-gap__answers">
		<?php
		if ( $show_answers ) {
			// Get answers
			$data['matches'] = array_map(
				function ( $answer ) {
					return "|{$answer['answer']}|";
				},
				$data['matches']
			);

			$answer = $answer_text;
			foreach ( $data['matches'] as $index => $match ) {
				$answer_key  = ( ! $show_correct_answer || $data['is_correct'] ) ? 'correct_user_answer' : 'show_correct_user_answer';
				$replacement = "<div class='masterstudy-course-player-fill-the-gap__check {$data['correct_answer'][$index]}'>{$data[ $answer_key ][$index]}</div>";
				$answer      = str_replace( $match, $replacement, $answer );
			}

			echo wp_kses_post( $answer );
		}
		?>
		</div>
	</div>
	<?php
}
