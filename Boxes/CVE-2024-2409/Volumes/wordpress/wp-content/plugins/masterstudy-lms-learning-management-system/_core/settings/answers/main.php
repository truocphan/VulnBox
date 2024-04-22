<?php

require_once STM_LMS_PATH . '/settings/answers/api/get.php';

add_filter('wpcfto_field_answers', function () {
	return STM_LMS_PATH . '/settings/answers/fields/answers.php';
});