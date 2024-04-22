<?php

add_filter(
	'masterstudy_lms_course_curriculum',
	function ( $curriculum, $course_id ) {
		$scorm = STM_LMS_Scorm_Packages::get_scorm_meta( $course_id );

		$curriculum['scorm'] = empty( $scorm ) ? null : $scorm;

		return $curriculum;

	},
	10,
	2
);
