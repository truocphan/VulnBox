<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Enums\LessonType;
use MasterStudy\Lms\Http\Serializers\PostSerializer;
use MasterStudy\Lms\Plugin\Addons;
use MasterStudy\Lms\Repositories\CourseRepository;
use WP_REST_Request;

class EditController {
	private CourseRepository $course_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): \WP_REST_Response {
		$course = $this->course_repository->find_post( $course_id );

		// builder settings extracted to separate controller
		// consider to deprecte and remove data from this controller
		$timezones = apply_filters( 'masterstudy_lms_timezones', array() );
		$options   = apply_filters(
			'masterstudy_lms_course_options',
			array(
				'max_upload_size'           => wp_max_upload_size(),
				'is_instructor'             => \STM_LMS_Instructor::has_instructor_role(),
				'create_category_allowed'   => \STM_LMS_Options::get_option( 'course_allow_new_categories', false ),
				'question_category_allowed' => \STM_LMS_Options::get_option( 'course_allow_new_question_categories', false ),
				'course_premoderation'      => \STM_LMS_Options::get_option( 'course_premoderation', false ),
				'course_style'              => \STM_LMS_Options::get_option( 'course_style', false ),
				'currency_symbol'           => \STM_LMS_Options::get_option( 'currency_symbol', '$' ),
				'presto_player_allowed'     => apply_filters( 'ms_plugin_presto_player_allowed', false ),
			)
		);

		return new \WP_REST_Response(
			array(
				'course'              => ( new PostSerializer() )->toArray( $course ),
				'addons'              => Addons::enabled_addons(),
				'plugins'             => array(
					'lms_pro'       => \STM_LMS_Helpers::is_pro(),
					'presto_player' => defined( 'PRESTO_PLAYER_PLUGIN_FILE' ),
					'pmpro'         => defined( 'PMPRO_VERSION' ),
					'eroom'         => defined( 'STM_ZOOM_VERSION' ),
				),
				'options'             => $options,
				'urls'                => array(
					'courses'           => home_url( \STM_LMS_Options::courses_page_slug() ),
					'user_account'      => \STM_LMS_User::user_page_url(),
					'dashboard_courses' => admin_url( 'edit.php?post_type=stm-courses' ),
					'addons'            => \STM_LMS_Helpers::is_pro()
						? admin_url( 'admin.php?page=stm-addons' )
						: admin_url( 'admin.php?page=stm-lms-go-pro' ),
					'plugins'           => admin_url( 'plugins.php' ),
				),
				'lesson_types'        => apply_filters( 'masterstudy_lms_lesson_types', array_map( 'strval', LessonType::cases() ) ),
				'video_sources'       => apply_filters( 'masterstudy_lms_lesson_video_sources', array() ),
				'presto_player_posts' => apply_filters( 'ms_plugin_presto_player_posts', array() ),
				'timezones'           => array_map(
					function ( $label, $id ) {
						return array(
							'id'    => $id,
							'label' => $label,
						);
					},
					$timezones,
					array_keys( $timezones ),
				),
				'current_user_id'     => get_current_user_id(),
			)
		);
	}
}
