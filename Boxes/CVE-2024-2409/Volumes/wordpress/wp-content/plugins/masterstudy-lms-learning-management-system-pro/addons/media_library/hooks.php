<?php

use MasterStudy\Lms\Plugin\Addons;
use MasterStudy\Lms\Pro\addons\media_library\MediaStorage;

add_filter(
	'masterstudy_lms_course_options',
	function ( $options ) {
		$options[ Addons::MEDIA_LIBRARY ] = array(
			'allowed_extensions' => MediaStorage::allowed_extensions(),
			'max_upload_size'    => MediaStorage::max_upload_size(),
		);

		return $options;
	}
);
