<?php

/**
 * @var int $progress
 * @var int $passing_grade
 * @var int $questions_quantity
 * @var int $answered_quantity
 * @var bool $show_emoji
 * @var string $emoji_name
 */

?>
<div class="masterstudy-course-player-quiz__result-container">
	<div class="masterstudy-course-player-quiz__result <?php echo esc_attr( $progress < $passing_grade ? 'masterstudy-course-player-quiz__result_failed' : '' ); ?>">
		<h2 class="masterstudy-course-player-quiz__result-title"><?php esc_html_e( 'Result', 'masterstudy-lms-learning-management-system' ); ?></h2>
		<div class="masterstudy-course-player-quiz__result-wrapper">
			<span class="masterstudy-course-player-quiz__result-progress">
				<?php echo esc_html( round( $progress, 1 ) . '%' ); ?>
			</span>
			<?php if ( $show_emoji && ! empty( $emoji_name ) ) { ?>
				<p class="masterstudy-course-player-quiz__emoji"><?php echo esc_html( $emoji_name ); ?></p>
			<?php } ?>
			<div class="masterstudy-course-player-quiz__result-info">
				<span class="masterstudy-course-player-quiz__result-answers">
					<?php
					if ( $questions_quantity > 0 ) {
						/* translators: %d: number */
						printf( wp_kses_post( __( '<strong>%1$d</strong> out of <strong>%2$d</strong> questions answered correctly', 'masterstudy-lms-learning-management-system' ) ), esc_html( $answered_quantity ), esc_html( $questions_quantity ) );
					}
					?>
				</span>
				<?php if ( $passing_grade > 0 ) { ?>
					<span class="masterstudy-course-player-quiz__result-passing-grade">
						<?php
						/* translators: %d: number */
						printf( esc_html__( 'Passing grade: %d%%', 'masterstudy-lms-learning-management-system' ), esc_html( $passing_grade ) );
						?>
					</span>
				<?php } ?>
			</div>
			<?php
			if ( $progress < $passing_grade ) {
				?>
				<div class="masterstudy-course-player-quiz__result-retake">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'            => 'quiz-result-retake',
						'title'         => __( 'Retake', 'masterstudy-lms-learning-management-system' ),
						'link'          => '#retake',
						'style'         => 'primary',
						'size'          => 'sm',
						'icon_position' => '',
						'icon_name'     => '',
					)
				);
				?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
