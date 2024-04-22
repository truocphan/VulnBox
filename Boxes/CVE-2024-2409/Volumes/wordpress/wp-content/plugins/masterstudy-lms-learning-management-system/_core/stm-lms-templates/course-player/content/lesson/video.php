<?php

/**
 * @var int $id
 */

use MasterStudy\Lms\Repositories\LessonRepository;

$lesson = ( new LessonRepository() )->get( $id );
STM_LMS_Templates::show_lms_template(
	'components/video-media',
	array(
		'lesson' => $lesson,
		'id'     => $id,
	)
);
