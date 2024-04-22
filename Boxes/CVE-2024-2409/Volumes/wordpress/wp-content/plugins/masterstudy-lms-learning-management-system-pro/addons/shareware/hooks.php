<?php

use MasterStudy\Lms\Models\Course;

add_filter(
	'masterstudy_lms_course_access_validation_rules',
	function ( $rules ) {
		$rules['shareware'] = 'boolean';
		return $rules;
	}
);

add_filter(
	'masterstudy_lms_course_hydrate',
	function ( Course $course, $meta ) {
		$course->shareware = ( $meta['shareware'][0] ?? 'off' ) === 'on';
		return $course;
	},
	10,
	2
);

add_action(
	'masterstudy_lms_course_update_access',
	function ( $course_id, $data ) {
		if ( isset( $data['shareware'] ) ) {
			update_post_meta( $course_id, 'shareware', $data['shareware'] ? 'on' : 'off' );
		}
	},
	10,
	2
);
