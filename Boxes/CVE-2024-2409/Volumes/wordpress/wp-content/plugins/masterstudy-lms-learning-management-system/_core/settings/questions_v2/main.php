<?php

require_once STM_LMS_PATH . '/settings/questions_v2/api/get.php';

add_filter('wpcfto_field_questions_v2', function () {
	return STM_LMS_PATH . '/settings/questions_v2/field.php';
});