<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 */
$question_id = get_the_ID();

$answers['0']['text']  = esc_html__( 'True', 'masterstudy-lms-learning-management-system' );
$answers['1']['text']  = esc_html__( 'False', 'masterstudy-lms-learning-management-system' );
$answers['0']['value'] = esc_html__( 'True', 'masterstudy-lms-learning-management-system' );
$answers['1']['value'] = esc_html__( 'False', 'masterstudy-lms-learning-management-system' );

foreach ( $answers as $answer ) : ?>
	<div class="stm-lms-single-answer">
		<label>
			<input type="radio" name="<?php echo esc_attr( $question_id ); ?>"  value="<?php echo esc_attr( $answer['value'] ); ?>"/>
			<i class="fa fa-check"></i>
				<?php echo esc_html( $answer['text'] ); ?>
		</label>
	</div>
<?php endforeach; ?>
