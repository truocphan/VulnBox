<?php

new STM_LMS_Point_System_Settings();

class STM_LMS_Point_System_Settings {

	public function __construct() {
		add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ), 100 );
		add_filter( 'stm_wpcfto_fields', array( $this, 'ps_stm_lms_fields' ), 10, 1 );
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Point System Settings',
				'menu_title'  => 'Point System Settings',
				'menu_slug'   => 'point_system_settings',
			),
			'fields'      => $this->stm_lms_settings(),
			'option_name' => 'stm_lms_point_system_settings',
		);

		return $setups;
	}

	public function stm_lms_settings() {
		$points = array();

		$points_dist = stm_lms_point_system();

		foreach ( $points_dist as $point_slug => $point_data ) {
			if ( ! isset( $point_data['score'] ) ) {
				continue;
			}

			$point_data_rebuild = array(
				'type'  => 'number',
				'label' => $point_data['label'],
				'value' => $point_data['score'],
			);

			if ( ! empty( $point_data['description'] ) ) {
				$point_data_rebuild['hint'] = $point_data['description'];
			}

			$points[ $point_slug ] = $point_data_rebuild;
		}

		return apply_filters(
			'stm_lms_point_system_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Interface', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'point_image'           => array(
							'type'  => 'image',
							'label' => esc_html__( 'Point Image', 'masterstudy-lms-learning-management-system-pro' ),
						),
						'point_label'           => array(
							'type'  => 'text',
							'label' => esc_html__( 'Point Label', 'masterstudy-lms-learning-management-system-pro' ),
						),
						'point_rate'            => array(
							'type'  => 'text',
							'label' => esc_html__( 'Point Rate', 'masterstudy-lms-learning-management-system-pro' ),
							'hint'  => esc_html__( 'Point rate relative to price (Ex.: 100 - means 100 points equal 1$)', 'masterstudy-lms-learning-management-system-pro' ),
							'value' => 10,
						),
						'affiliate_points'      => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Enable Affiliate Points', 'masterstudy-lms-learning-management-system-pro' ),
							'hint'  => esc_html__( 'Your users can share their affiliate link and get points for the activity of users they invited.', 'masterstudy-lms-learning-management-system-pro' ),
						),
						'affiliate_points_rate' => array(
							'type'  => 'number',
							'label' => esc_html__( 'Affiliate Points percent (%)', 'masterstudy-lms-learning-management-system-pro' ),
							'hint'  => esc_html__( 'Percentage users will get for affiliates', 'masterstudy-lms-learning-management-system-pro' ),
							'value' => 10,
						),
					),
				),
				'points'      => array(
					'name'   => esc_html__( 'Points Distribution', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => $points,
				),
			)
		);
	}

	public function ps_stm_lms_fields( $fields ) {
		$fields['stm_courses_settings']['section_accessibility']['fields']['points_price'] = array(
			'type'  => 'number',
			'label' => esc_html__( 'Points Price', 'masterstudy-lms-learning-management-system-pro' ),
		);

		return $fields;
	}

}
