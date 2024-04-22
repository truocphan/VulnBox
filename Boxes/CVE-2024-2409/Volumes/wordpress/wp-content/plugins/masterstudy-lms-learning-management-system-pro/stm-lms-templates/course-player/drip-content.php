<?php
/**
 * @var int $post_id
 * @var int $item_id
 */

wp_enqueue_style( 'masterstudy-course-player-drip-content' );

$data = apply_filters( 'masterstudy_course_player_drip_content_data', $item_id );
if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-drip-content-fonts' );
}
?>
<div class="masterstudy-course-player-drip-content">
	<h2 class="masterstudy-course-player-drip-content__title">
		<?php echo esc_html( get_the_title( $item_id ) ); ?>
	</h2>
	<p class="masterstudy-course-player-drip-content__desc">
		<?php echo esc_html__( 'Lesson starts in:', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</p>
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/countdown',
		array(
			'id'         => 'countdown_' . $item_id,
			'start_time' => intval( STM_LMS_Sequential_Drip_Content::lesson_start_time( $item_id, $post_id ) ),
			'dark_mode'  => $data['dark_mode'],
			'style'      => 'gray',
		)
	);
	?>
</div>
