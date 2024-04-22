<?php
add_filter('wpcfto_field_faq', function () {
	return STM_LMS_PATH . '/settings/faq/fields/faq.php';
});