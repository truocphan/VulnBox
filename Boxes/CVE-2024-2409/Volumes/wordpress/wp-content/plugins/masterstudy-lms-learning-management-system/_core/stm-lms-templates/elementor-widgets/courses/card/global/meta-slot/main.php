<div class="ms_lms_courses_card_item_meta_block">
	<?php
	STM_LMS_Templates::show_lms_template(
		"elementor-widgets/courses/card/global/meta-slot/{$meta_slot}",
		array( 'course' => $course ),
	);
	?>
</div>
