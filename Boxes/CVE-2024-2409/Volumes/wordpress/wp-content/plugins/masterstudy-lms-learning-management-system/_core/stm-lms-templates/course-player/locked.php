<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'masterstudy-course-player-locked' );

$is_prerequisite_passed = true;

if ( class_exists( 'STM_LMS_Prerequisites' ) ) {
	$is_prerequisite_passed = STM_LMS_Prerequisites::is_prerequisite( true, $post_id );
}
?>
<div class="masterstudy-course-player-locked<?php echo ( $is_prerequisite_passed ) ? '' : ' prerequisite_preview'; ?>">
	<?php if ( $is_prerequisite_passed ) : ?>
	<h2 class="masterstudy-course-player-locked__title"><?php echo esc_html__( 'Hey there, great course, right? Do you like this course?', 'masterstudy-lms-learning-management-system' ); ?></h2>
	<div class="masterstudy-course-player-locked__description"><?php echo esc_html__( 'All of the most interesting lessons further. In order to continue you just need to purchase it.', 'masterstudy-lms-learning-management-system' ); ?></div>
	<?php endif; ?>
	<div class="masterstudy-course-player-locked__purchase">
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/buy-button/buy-button',
		array(
			'post_id'              => $post_id,
			'item_id'              => $item_id,
			'dark_mode'            => $dark_mode,
			'prerequisite_preview' => true,
			'hide_group_course'    => true,
			'has_access'           => false,
		)
	);
	?>
	</div>
</div>
