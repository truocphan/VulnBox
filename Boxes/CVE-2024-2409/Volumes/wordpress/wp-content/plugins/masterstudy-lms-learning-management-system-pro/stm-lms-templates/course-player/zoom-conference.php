<?php
/**
 * @var int $post_id
 * @var int $item_id
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-zoom' );

$data = apply_filters( 'masterstudy_course_player_lesson_zoom_data', $item_id );
if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-lesson-zoom-fonts' );
}
?>
<div class="masterstudy-course-player-lesson-zoom">
	<?php
	if ( ! empty( $data['meeting_id'] ) ) {
		echo do_shortcode( '[stm_zoom_conference post_id="' . $data['meeting_id'] . '"]' );
	};
	if ( ! empty( $data['content'] ) ) {
		?>
		<div class="masterstudy-course-player-lesson-zoom__content">
			<?php echo wp_kses( htmlspecialchars_decode( $data['content'] ), stm_lms_allowed_html() ); ?>
		</div>
	<?php } ?>
</div>
