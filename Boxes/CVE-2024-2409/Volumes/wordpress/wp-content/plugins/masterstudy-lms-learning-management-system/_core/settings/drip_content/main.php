<?php
add_filter('wpcfto_field_drip_content', function () {
	return STM_LMS_PATH . '/settings/drip_content/fields/drip_content.php';
});