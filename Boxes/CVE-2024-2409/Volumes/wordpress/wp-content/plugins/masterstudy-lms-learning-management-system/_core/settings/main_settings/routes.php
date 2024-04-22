<?php

function stm_lms_settings_route_section() {
	$pages                  = WPCFTO_Settings::stm_get_post_type_array( 'page' );
	$page_list              = stm_lms_generate_pages_list();
	$elementor_page_list    = stm_lms_elementor_page_list();
	$elementor_courses_page = stm_lms_get_generated_elementor_pages();
	$settings               = stm_wpcfto_get_options( 'stm_lms_settings' );
	if ( isset( $settings['courses_page_elementor'] ) && $settings['courses_page_elementor'] !== $elementor_courses_page ) {
		$settings['courses_page_elementor'] = ( ! empty( $elementor_courses_page ) ) ? $elementor_courses_page['title'] : esc_html__( 'Page not generated', 'masterstudy-lms-learning-management-system' );
		update_option( 'stm_lms_settings', $settings );
	}

	$data = array(
		'icon'   => 'fas fa-link',
		'name'   => esc_html__( 'LMS Pages', 'masterstudy-lms-learning-management-system' ),
		'fields' => array(

			'user_url'               => array(
				'type'    => 'select',
				'label'   => esc_html__( 'User Account', 'masterstudy-lms-learning-management-system' ),
				'options' => $pages,
			),

			'user_url_profile'       => array(
				'type'    => 'select',
				'label'   => esc_html__( 'User Public Account', 'masterstudy-lms-learning-management-system' ),
				'options' => $pages,
			),

			'wishlist_url'           => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Wishlist', 'masterstudy-lms-learning-management-system' ),
				'options' => $pages,
			),

			'checkout_url'           => array(
				'type'            => 'select',
				'options'         => $pages,
				'label'           => esc_html__( 'Checkout', 'masterstudy-lms-learning-management-system' ),
				'dependency'      => array(
					'key'     => 'wocommerce_checkout',
					'value'   => 'not_empty',
					'section' => 'section_1',
				),
				'dependency_mode' => 'disabled',
			),
			'courses_page_elementor' => array(
				'type'           => 'text',
				'label'          => esc_html__( 'Courses page (for Elementor)', 'masterstudy-lms-learning-management-system' ),
				'value'          => ( ! empty( $elementor_courses_page ) ) ? $elementor_courses_page['title'] : esc_html__( 'Page not generated', 'masterstudy-lms-learning-management-system' ),
				'field_disabled' => 'yes',
			),
		),
	);

	if ( ! stm_lms_has_generated_pages( $page_list ) || ! stm_lms_has_generated_elementor_pages( $elementor_page_list ) ) {
		$data['fields']['lms_pages'] = array(
			'type'    => 'generate_page',
			'options' => $page_list,
			'label'   => esc_html__( 'Generate LMS Pages', 'masterstudy-lms-learning-management-system' ),
		);
	}

	return $data;
}
