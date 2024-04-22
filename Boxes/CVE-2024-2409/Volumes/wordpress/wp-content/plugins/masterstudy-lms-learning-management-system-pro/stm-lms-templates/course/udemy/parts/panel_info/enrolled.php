<?php
/**
 *
 * @var $udemy_meta
 */


if ( ! empty( $udemy_meta['current_students'] ) ) :
	$student_num = $udemy_meta['current_students'];
	?>
	<div class="stm_lms_enrolled_num">
		<i class="fa fa-user-circle"></i>
		<span>
			<?php
			printf(
				/* translators: %s Ratings */
				_n( '%s student enrolled', '%s students enrolled', $student_num, 'masterstudy-lms-learning-management-system-pro' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				number_format_i18n( $student_num ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
	</div>
	<?php
endif;
