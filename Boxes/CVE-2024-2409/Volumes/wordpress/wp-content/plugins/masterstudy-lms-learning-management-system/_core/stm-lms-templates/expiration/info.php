<?php
/**
 * @var $course_expiration_days
 */
?>

<div class="stm_lms_expired_notice__wrapper">
	<div class="stm_lms_expired_notice warning_expired">
		<i class="far fa-clock"></i>
		<?php
		printf(
			wp_kses_post(
				/* translators: %s Course available days */
				_n(
					'Course available for <strong>%s day</strong>',
					'Course available for <strong>%s days</strong>',
					$course_expiration_days,
					'masterstudy-lms-learning-management-system'
				),
			),
			esc_html( $course_expiration_days )
		);
		?>
	</div>
</div>
