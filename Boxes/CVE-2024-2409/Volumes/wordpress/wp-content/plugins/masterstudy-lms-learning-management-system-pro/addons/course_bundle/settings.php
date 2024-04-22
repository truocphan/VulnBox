<?php

new STM_LMS_Course_Bundle_Settings();

class STM_LMS_Course_Bundle_Settings {

	public function __construct() {
		add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ), 100 );
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Course Bundle Settings',
				'menu_title'  => 'Course Bundle Settings',
				'menu_slug'   => 'course_bundle_settings',
			),
			'fields'      => $this->stm_lms_settings(),
			'option_name' => 'stm_lms_course_bundle_settings',
		);

		return $setups;
	}

	public static function stm_lms_get_settings() {
		return get_option( 'stm_lms_course_bundle_settings', array() );
	}

	public function stm_lms_settings() {
		return apply_filters(
			'stm_lms_course_bundle_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Credentials', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'bundle_limit'         => array(
							'type'  => 'text',
							'label' => esc_html__( 'Bundles quantity limit', 'masterstudy-lms-learning-management-system-pro' ),
						),
						'bundle_courses_limit' => array(
							'type'  => 'text',
							'label' => esc_html__( 'Courses in bundle quantity limit', 'masterstudy-lms-learning-management-system-pro' ),
							'hint'  => esc_html__( 'By default limit is 5. Five courses - fits the best in bundle on frontend.', 'masterstudy-lms-learning-management-system-pro' ),
						),
					),
				),
			)
		);
	}

}
