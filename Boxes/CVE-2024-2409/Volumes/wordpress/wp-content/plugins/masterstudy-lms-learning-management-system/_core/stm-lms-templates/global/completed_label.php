<?php

/**
 * @var $course_id
 */

$total_progress = STM_LMS_Lesson::get_total_progress( get_current_user_id(), $course_id );

if ( ! empty( $total_progress ) && $total_progress['course_completed'] ) :
	stm_lms_register_style( 'lesson/total_progress' );
	?>

	<div class="stm_lms_course_completed_summary__label heading_font">
		<span class="stmlms-check"></span>
		<?php esc_html_e( 'Successfully done', 'masterstudy-lms-learning-management-system' ); ?>
	</div>

	<?php
endif;
