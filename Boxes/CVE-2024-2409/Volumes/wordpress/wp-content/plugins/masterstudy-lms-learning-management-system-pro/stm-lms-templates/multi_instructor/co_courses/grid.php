<?php
/**
 * $current_user_id
 */

if ( empty( $current_user_id ) ) {
	$current_user_id = '';
}

if ( ! class_exists( 'STM_LMS_Multi_Instructors' ) ) {
	return false;
}

$args = STM_LMS_Multi_Instructors::getCoCourses( $current_user_id, true );

?>

<div class="stm_lms_instructor_co_courses">
	<div class="stm_lms_instructor_courses__top">
		<h3><?php esc_html_e( 'Co-courses', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
	</div>

	<?php STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) ); ?>
</div>
