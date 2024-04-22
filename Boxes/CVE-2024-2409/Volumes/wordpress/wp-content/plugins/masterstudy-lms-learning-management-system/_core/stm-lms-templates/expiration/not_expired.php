<?php

/**
 * @var $course_id
 * @var $course_end_time
 */

$time_left = $course_end_time - time();
$days_left = floor( $time_left / DAY_IN_SECONDS );
?>

<div class="stm_lms_expired_notice expired_in_progress">
	<i class="far fa-clock"></i>
	<?php
	if ( $days_left < 1 ) {
		printf(
			/* translators: %s Time Left */
			esc_html__( 'Course expires in: %s', 'masterstudy-lms-learning-management-system' ),
			wp_kses_post( "<strong><span data-lms-timer='{$time_left}'></span></strong>" )
		);
	} else {
		printf(
			wp_kses_post(
			/* translators: %s Course available days */
				_n(
					'Course expires in: <strong>%s day</strong>',
					'Course expires in: <strong>%s days</strong>',
					$days_left,
					'masterstudy-lms-learning-management-system'
				),
			),
			esc_html( $days_left )
		);
	}
	?>
</div>
