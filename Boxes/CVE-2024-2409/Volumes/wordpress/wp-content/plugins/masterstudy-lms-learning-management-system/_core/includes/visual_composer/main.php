<?php
$modules = array(
	'stm_courses_searchbox',
	'stm_lms_courses_carousel',
	'stm_lms_courses_categories',
	'stm_lms_courses_grid',
	'stm_lms_featured_teacher',
	'stm_lms_instructors_carousel',
	'stm_lms_recent_courses',
	'stm_lms_single_course_carousel',
	'stm_lms_membership_levels',
);

foreach ( $modules as $module ) {
	require_once STM_LMS_PATH . '/includes/visual_composer/modules/' . $module . '.php';
}
