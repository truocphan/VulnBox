<div class="ms_lms_courses_card_item_info_progress">
	<div class="ms_lms_courses_card_item_info_progress_bars">
		<span class="ms_lms_courses_card_item_info_progress_bar_empty"></span>
		<span class="ms_lms_courses_card_item_info_progress_bar_filled" style="width:<?php echo esc_html( $course['progress'] ); ?>%"></span>
	</div>
	<div class="ms_lms_courses_card_item_info_progress_title">
		<?php echo esc_html_e( 'Progress', 'masterstudy-lms-learning-management-system' ); ?>:
		<?php echo esc_html( $course['progress'] ); ?>%
	</div>
</div>
