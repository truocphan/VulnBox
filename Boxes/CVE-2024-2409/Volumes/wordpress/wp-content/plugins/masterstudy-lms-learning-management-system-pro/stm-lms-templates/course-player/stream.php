<?php
/**
 * @var int $post_id
 * @var int $item_id
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-stream' );

$data = apply_filters( 'masterstudy_course_player_lesson_stream_data', $item_id );
if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-lesson-stream-fonts' );
}
?>
<div class="masterstudy-course-player-lesson-stream">
	<?php if ( ! $data['stream_started'] && class_exists( 'STM_LMS_Templates' ) ) { ?>
		<div class="masterstudy-course-player-lesson-stream__wrapper">
			<div class="masterstudy-course-player-lesson-stream__countdown">
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
			<div class="masterstudy-course-player-lesson-stream__info">
				<div class="masterstudy-course-player-lesson-stream__info-item">
					<span><?php echo esc_html__( 'Starts:', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					<span><?php echo esc_html( $data['start_date'] ); ?></span>
				</div>
				<div class="masterstudy-course-player-lesson-stream__info-item">
					<span><?php echo esc_html__( 'Ends:', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					<span><?php echo esc_html( $data['end_date'] ); ?></span>
				</div>
			</div>
		</div>
	<?php } else { ?>
	<div class="masterstudy-course-player-lesson-stream__video">
		<iframe src="<?php echo esc_attr( $data['youtube_url'] ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		<?php if ( ! empty( $data['youtube_chat_url'] ) ) { ?>
			<iframe src="<?php echo esc_attr( $data['youtube_chat_url'] ); ?>" frameborder="0" class="masterstudy-course-player-lesson-stream__chat"></iframe>
		<?php } ?>
	</div>
		<?php
	}
	if ( ! empty( $data['content'] ) ) {
		?>
		<div class="masterstudy-course-player-lesson-stream__content">
			<?php echo wp_kses( htmlspecialchars_decode( $data['content'] ), stm_lms_allowed_html() ); ?>
		</div>
		<?php
	}
	?>
</div>
