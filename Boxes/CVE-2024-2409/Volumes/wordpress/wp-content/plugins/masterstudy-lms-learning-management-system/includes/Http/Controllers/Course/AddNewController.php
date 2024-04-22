<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\Serializers\CourseCategorySerializer;
use MasterStudy\Lms\Http\Serializers\CourseLevelSerializer;
use MasterStudy\Lms\Plugin\Taxonomy;

class AddNewController {
	public function __invoke(): \WP_REST_Response {
		return new \WP_REST_Response(
			array(
				'categories'              => ( new CourseCategorySerializer() )->collectionToArray( Taxonomy::all_categories() ),
				'levels'                  => ( new CourseLevelSerializer() )->collectionToArray( \STM_LMS_Helpers::get_course_levels() ),
				'courses_url'             => home_url( \STM_LMS_Options::courses_page_slug() ),
				'user_account_url'        => \STM_LMS_User::user_page_url(),
				'dashboard_courses_url'   => admin_url( 'edit.php?post_type=stm-courses' ),
				'max_upload_size'         => wp_max_upload_size(),
				'is_instructor'           => \STM_LMS_Instructor::has_instructor_role(),
				'create_category_allowed' => \STM_LMS_Options::get_option( 'course_allow_new_categories', false ),
			)
		);
	}
}
