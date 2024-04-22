<?php

/**
 * @var $id
 */

if ( ! empty( $id ) ) {
	STM_LMS_Templates::show_lms_template(
		'global/files',
		array(
			'post_id'   => $id,
			'post_meta' => 'course_files',
		)
	);
}
