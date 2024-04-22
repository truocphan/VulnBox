<?php
/**
 * @var $item_id
 */

if ( ! empty( $item_id ) ) {
	STM_LMS_Templates::show_lms_template(
		'global/files',
		array(
			'post_id'   => $item_id,
			'post_meta' => 'lesson_files',
		)
	);
}
