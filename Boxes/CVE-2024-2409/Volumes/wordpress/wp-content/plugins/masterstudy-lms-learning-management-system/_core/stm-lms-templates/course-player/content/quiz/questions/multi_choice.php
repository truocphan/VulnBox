<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

STM_LMS_Templates::show_lms_template(
	'course-player/content/quiz/questions/choice',
	array(
		'data'         => $data,
		'show_answers' => $show_answers,
		'item_id'      => $item_id,
		'dark_mode'    => $dark_mode,
		'choice'       => 'multi',
	)
);
