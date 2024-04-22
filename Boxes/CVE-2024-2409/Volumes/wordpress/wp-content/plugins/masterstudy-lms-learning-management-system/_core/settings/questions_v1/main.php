<?php
add_filter('wpcfto_field_questions', function () {
	return STM_LMS_PATH . '/settings/questions/fields/questions.php';
});