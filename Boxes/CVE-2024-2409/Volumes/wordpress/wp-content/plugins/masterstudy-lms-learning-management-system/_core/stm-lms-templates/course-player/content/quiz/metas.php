<?php
/**
 * @var int $duration_value
 * @var string $duration_measure
 * @var int $passing_grade
 * @var int $questions_quantity
 */
?>

<ul class="masterstudy-course-player-quiz__content-meta">
	<?php if ( $questions_quantity > 0 ) { ?>
		<li class="masterstudy-course-player-quiz__content-meta-item masterstudy-course-player-quiz__content-meta-item_questions">
			<?php echo esc_html__( 'Questions count', 'masterstudy-lms-learning-management-system' ); ?>:
			<span class="masterstudy-course-player-quiz__content-meta-item-title">
				<?php echo esc_html( $questions_quantity ); ?>
			</span>
		</li>
		<?php
	}
	if ( $passing_grade > 0 ) {
		?>
		<li class="masterstudy-course-player-quiz__content-meta-item masterstudy-course-player-quiz__content-meta-item_grade">
			<?php echo esc_html__( 'Passing grade', 'masterstudy-lms-learning-management-system' ); ?>:
			<span class="masterstudy-course-player-quiz__content-meta-item-title">
				<?php echo esc_html( $passing_grade . '%' ); ?>
			</span>
		</li>
		<?php
	}
	if ( $duration_value > 0 ) {
		?>
		<li class="masterstudy-course-player-quiz__content-meta-item masterstudy-course-player-quiz__content-meta-item_duration">
			<?php echo esc_html__( 'Time limit', 'masterstudy-lms-learning-management-system' ); ?>:
			<span class="masterstudy-course-player-quiz__content-meta-item-title">
				<?php echo esc_html( masterstudy_lms_time_elapsed_string_e( $duration_value, $duration_measure ) ); ?>
			</span>
		</li>
	<?php } ?>
</ul>
