<?php

/**
 * @var $course_id
 * @var $course_price
 */
$coming_soon_preordering = get_post_meta( $course_id, 'coming_soon_preordering', true );
$coming_soon_status      = get_post_meta( $course_id, 'coming_soon_status', true );
$is_course_coming_soon   = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $course_id );
if ( $is_course_coming_soon && ( $coming_soon_preordering && 0 === $course_price ) ) {
	?>
	<a class="masterstudy-coming-soon-disabled-btn">
		<?php esc_html_e( 'Coming soon', 'masterstudy-lms-learning-management-system' ); ?>
	</a>
	<?php
	return;
}
