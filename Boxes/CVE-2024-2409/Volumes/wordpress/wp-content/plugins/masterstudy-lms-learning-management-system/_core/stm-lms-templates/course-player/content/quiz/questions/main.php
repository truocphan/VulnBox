<?php

/**
 * @var array $data
 * @var array $last_answers
 * @var boolean $show_answers
 * @var boolean $last_quiz
 * @var array $question_banks
 * @var string $quiz_style
 * @var int $item_id
 * @var boolean $dark_mode
 */

use MasterStudy\Lms\Plugin\Addons;
use MasterStudy\Lms\Repositories\QuestionRepository;

wp_enqueue_style( 'masterstudy-course-player-question' );
wp_enqueue_script( 'masterstudy-course-player-question' );

global $ms_question_number;

$data['type']                = empty( $data['type'] ) ? 'single_choice' : $data['type'];
$data['last_answers']        = $last_answers[ $data['id'] ] ?? array();
$data['is_correct']          = ! empty( $data['last_answers']['correct_answer'] );
$data['has_image_question']  = ! empty( $data['view_type'] ) && 'image' === $data['view_type'];
$data['show_correct_answer'] = get_post_meta( $item_id, 'correct_answer', true );
$data['correct_answer']      = ! empty( $data['last_answers']['correct_answer'] )
	? true
	: ( 'question_bank' === $data['type'] ? 'bank' : false );

if ( $data['is_correct'] ) {
	$data['last_answers'] = array();
}

$classes = implode(
	' ',
	array_filter(
		array(
			'pagination' === $quiz_style && 1 !== $ms_question_number ? 'masterstudy-course-player-question_hide' : '',
			( 'question_bank' !== $data['type'] ) ? ( $data['is_correct'] && $show_answers ? 'masterstudy-course-player-question_correct' : '' ) : '',
			( 'question_bank' !== $data['type'] ) ? ( ! $data['is_correct'] && ! empty( $data['last_answers']['user_answer'] ) ? 'masterstudy-course-player-question_wrong' : '' ) : '',
			'question_bank' === $data['type'] ? 'masterstudy-course-player-question_question-bank' : '',
		)
	)
);

$content_classes = implode(
	' ',
	array_filter(
		array(
			in_array( $data['type'], array( 'image_match', 'item_match' ), true ) || ! empty( $data['has_image_question'] ) ? 'masterstudy-course-player-question__content_table-type' : '',
			'question_bank' === $data['type'] ? 'masterstudy-course-player-question__content_bank' : '',
		)
	)
);

if ( ! empty( $data['answers'] ) ) {
	?>
	<div class="masterstudy-course-player-question <?php echo esc_attr( $classes ); ?>"
		data-number-question="<?php echo esc_attr( 'question_bank' !== $data['type'] ? $ms_question_number : '' ); ?>">
		<?php if ( 'question_bank' !== $data['type'] ) { ?>
			<div class="masterstudy-course-player-question__header">
				<h3 class="masterstudy-course-player-question__title">
					<?php echo esc_html( $ms_question_number . '. ' . $data['title'] ); ?>
				</h3>
				<?php
				if ( is_ms_lms_addon_enabled( Addons::QUESTION_MEDIA ) && ! empty( $data['video_type'] ) && isset( $data['image']['type'] ) && 'video' === $data['image']['type'] ) {
					STM_LMS_Templates::show_lms_template(
						'components/video-media',
						array(
							'lesson' => ( new QuestionRepository() )->get( $data['id'] ),
							'id'     => $data['id'],
						)
					);
				}
				if ( ! empty( $data['content'] ) ) {
					?>
					<div class="masterstudy-course-player-question__description">
						<?php echo wp_kses_post( $data['content'] ); ?>
					</div>
					<?php
				} if ( isset( $data['image']['type'] ) && 'video' !== $data['image']['type'] && ! empty( $data['image']['id'] ) ) {
					$image_source = wp_get_attachment_image_src( $data['image']['id'], 'full' );
					if ( ! empty( $image_source[0] ) ) {
						?>
						<img class="masterstudy-course-player-question__image" src="<?php echo esc_url( $image_source[0] ); ?>" />
						<?php
					}
				}
				if ( is_ms_lms_addon_enabled( Addons::QUESTION_MEDIA ) && ! empty( $data['image']['url'] ) && strpos( $data['image']['type'], 'audio' ) !== false ) {
					?>
					<div class="question-audio-player-wrapper">
						<div class="question-audio-player-details">
							<div class="question-audio-player-title"><?php echo esc_html( $data['image']['title'] ); ?></div>
						</div>
						<?php
						STM_LMS_Templates::show_lms_template(
							'components/audio-player',
							array(
								'preloader' => false,
								'src'       => $data['image']['url'],
								'dark_mode' => $dark_mode,
							)
						);
						?>
					</div>
					<?php
				}
				if ( ! empty( $data['question_explanation'] ) && $show_answers ) {
					?>
					<div class="masterstudy-course-player-question__explanation">
						<?php echo esc_html( $data['question_explanation'] ); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="masterstudy-course-player-question__content <?php echo esc_attr( $content_classes ); ?>">
			<?php
			STM_LMS_Templates::show_lms_template(
				'course-player/content/quiz/questions/' . $data['type'],
				array(
					'data'           => $data,
					'show_answers'   => $show_answers,
					'last_answers'   => $last_answers,
					'last_quiz'      => $last_quiz ?? '',
					'quiz_style'     => $quiz_style,
					'question_banks' => ! empty( $question_banks ) ? $question_banks : array(),
					'item_id'        => $item_id,
					'dark_mode'      => $dark_mode,
				)
			);
			?>
		</div>
	</div>
	<?php
	$ms_question_number++;
}
