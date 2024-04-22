<?php
/**
 * @var $id
 */

if ( STM_LMS_Options::get_option( 'enable_sticky_button', false ) ) : ?>

	<div class="stm_lms_course_sticky_panel__button">
		<a href="#" class="btn btn-default sticky-panel-btn">
			<?php esc_html_e( 'Get Course', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
	</div>

	<?php
endif;
