<?php
/**
 * @var int $post_id
 * @var int $item_id
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-google' );

$data = apply_filters( 'masterstudy_course_player_lesson_google_data', $item_id, $post_id );
if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-lesson-google-fonts' );
}
?>
<div class="masterstudy-course-player-lesson-google">
	<div class="masterstudy-course-player-lesson-google__wrapper">
		<?php if ( ! $data['meet_started'] && class_exists( 'STM_LMS_Templates' ) ) { ?>
			<div class="masterstudy-course-player-lesson-google__countdown">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/countdown',
					array(
						'id'         => 'countdown_' . $item_id,
						'start_time' => intval( $data['start_time'] ),
						'dark_mode'  => $data['dark_mode'],
						'style'      => 'default',
					)
				);
				?>
			</div>
		<?php } ?>
		<div class="masterstudy-course-player-lesson-google__info">
			<div class="masterstudy-course-player-lesson-google__info-item">
				<span><?php echo esc_html__( 'Starts:', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				<span><?php echo esc_html( $data['start_date'] ); ?></span>
			</div>
			<div class="masterstudy-course-player-lesson-google__info-item">
				<span><?php echo esc_html__( 'Ends:', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				<span><?php echo esc_html( $data['end_date'] ); ?></span>
			</div>
			<div class="masterstudy-course-player-lesson-google__info-item">
				<span><?php echo esc_html__( 'Host e-mail:', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				<span class="masterstudy-course-player-lesson-google__email"><?php echo esc_html( $data['author_email'] ); ?></span>
			</div>
		</div>
		<a href="<?php echo esc_url( $data['meet_url'] ); ?>" target="_blank" class="masterstudy-course-player-lesson-google__button">
			<?php echo esc_html__( 'Join meeting', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
	</div>
	<?php if ( ! empty( $data['description'] ) ) { ?>
		<div class="masterstudy-course-player-lesson-google__description">
			<?php echo esc_html( $data['description'] ); ?>
		</div>
	<?php } ?>
</div>
