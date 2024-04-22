<?php
require_once STM_LMS_LIBRARY . '/mailchimp-integration.php';
require_once STM_LMS_LIBRARY . '/paypal/autoload.php';
require_once STM_LMS_LIBRARY . '/db/tables.php';
require_once STM_LMS_LIBRARY . '/mixpanel/init.php';

if ( is_admin() ) {
	require_once STM_LMS_LIBRARY . '/db/fix_rating.php';
	require_once STM_LMS_LIBRARY . '/compatibility/main.php';
	require_once STM_LMS_LIBRARY . '/announcement/main.php';
	require_once STM_LMS_LIBRARY . '/announcement/item-announcements.php';
	require_once STM_LMS_LIBRARY . '/admin-notices/admin-notices.php';
	require_once STM_LMS_LIBRARY . '/admin-notification-popup/admin-notification-popup.php';
	require_once STM_LMS_LIBRARY . '/announcement/survey-notice.php';
	require_once STM_LMS_LIBRARY . '/db/tables_updater.php';
	require_once STM_LMS_PATH . '/settings/page_generator/api/get.php';
	require_once STM_LMS_PATH . '/lms/classes/options.php';
	require_once STM_LMS_LIBRARY . '/compatibility/notices.php';
}
