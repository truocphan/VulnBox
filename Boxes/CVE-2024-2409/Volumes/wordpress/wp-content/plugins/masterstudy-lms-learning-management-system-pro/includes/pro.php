<?php
require_once STM_LMS_PRO_INCLUDES . '/helpers.php';

require_once STM_LMS_PRO_INCLUDES . '/hooks/templates.php';
require_once STM_LMS_PRO_INCLUDES . '/hooks/sale-price.php';
require_once STM_LMS_PRO_INCLUDES . '/hooks/routes.php';
require_once STM_LMS_PRO_INCLUDES . '/hooks/course-player.php';

if ( class_exists( 'SitePress' ) ) {
	require_once STM_LMS_PRO_INCLUDES . '/hooks/multilingual.php';
}

require_once STM_LMS_PRO_INCLUDES . '/classes/class-woocommerce-admin.php';
require_once STM_LMS_PRO_INCLUDES . '/hooks/woocommerce.php';

if ( STM_LMS_Cart::woocommerce_checkout_enabled() ) {
	require_once STM_LMS_PRO_INCLUDES . '/classes/class-woocommerce.php';
	require_once STM_LMS_PRO_INCLUDES . '/hooks/woocommerce-orders.php';
}

require_once STM_LMS_PRO_INCLUDES . '/classes/class-announcements.php';
require_once STM_LMS_PRO_INCLUDES . '/classes/class-courses.php';
require_once STM_LMS_PRO_PATH . '/vendor/autoload.php';
require_once STM_LMS_PRO_INCLUDES . '/classes/class-addons.php';
require_once STM_LMS_PRO_INCLUDES . '/classes/class-certificates.php';

if ( is_admin() ) {
	require_once STM_LMS_PRO_INCLUDES . '/libraries/plugin-installer/plugin_installer.php';
	require_once STM_LMS_PRO_INCLUDES . '/libraries/announcement/item-announcements.php';
	require_once STM_LMS_PRO_INCLUDES . '/libraries/compatibility/main.php';
}

add_filter(
	'masterstudy_lms_plugin_addons',
	function ( $addons ) {
		return array_merge(
			$addons,
			array(
				new \MasterStudy\Lms\Pro\addons\assignments\Assignments(),
				new \MasterStudy\Lms\Pro\addons\certificate_builder\CertificateBuilder(),
				new \MasterStudy\Lms\Pro\addons\sequential_drip_content\DripContent(),
				new \MasterStudy\Lms\Pro\addons\email_manager\EmailManager(),
				new \MasterStudy\Lms\Pro\addons\gradebook\Gradebook(),
				new \MasterStudy\Lms\Pro\addons\live_streams\LiveStreams(),
				new \MasterStudy\Lms\Pro\addons\media_library\MediaLibrary(),
				new \MasterStudy\Lms\Pro\addons\prerequisite\Prerequisite(),
				new \MasterStudy\Lms\Pro\addons\scorm\Scorm(),
				new \MasterStudy\Lms\Pro\addons\shareware\Shareware(),
				new \MasterStudy\Lms\Pro\addons\zoom_conference\ZoomConference(),
			)
		);
	}
);
