<?php


new STM_LMS_Multi_Instructor_Settings();

class STM_LMS_Multi_Instructor_Settings {

	public function __construct() {
		add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ), 100 );
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Multi Instructor Settings',
				'menu_title'  => 'Multi Instructor Settings',
				'menu_slug'   => 'multi_instructors_settings',
			),
			'fields'      => $this->stm_lms_settings(),
			'option_name' => 'stm_lms_multi_instructor_settings',
		);

		return $setups;
	}

	public function stm_lms_settings() {
		return apply_filters(
			'stm_lms_multi_instructor_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Interface', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'point_label' => array(
							'type'  => 'text',
							'label' => esc_html__( 'Point Label', 'masterstudy-lms-learning-management-system-pro' ),
						),
					),
				),
			)
		);
	}

}
