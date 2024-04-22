<?php

require_once STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/helpers/helpers.php';

add_filter(
	'wpcfto_field_udemy/search',
	function () {
		return STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/fields/udemy.php';
	}
);

add_filter(
	'wpcfto_field_manage_udemy_posts',
	function () {
		return STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/fields/manage_udemy_posts.php';
	}
);
