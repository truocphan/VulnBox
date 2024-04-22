<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

require_once STM_LMS_LIBRARY . '/db/_names.php';

require_once STM_LMS_LIBRARY . '/db/helpers/user_courses.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_quizzes.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_quizzes.times.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_lessons.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_answers.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_cart.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_chat.php';
require_once STM_LMS_LIBRARY . '/db/helpers/user_searches.php';

if ( is_admin() ) {
	require_once STM_LMS_LIBRARY . '/db/tables_updater.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_courses.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_quizzes.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_quizzes.times.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_lessons.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_answers.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_cart.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_chat.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/user_searches.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/curriculum_sections.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/curriculum_materials.table.php';
	require_once STM_LMS_LIBRARY . '/db/tables/order_items.table.php';
}
