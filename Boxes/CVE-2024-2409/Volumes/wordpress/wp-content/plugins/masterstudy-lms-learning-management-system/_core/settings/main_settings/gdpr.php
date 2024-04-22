<?php

function stm_lms_settings_gdpr_section() {

	$pages = WPCFTO_Settings::stm_get_post_type_array( 'page' );

	return array(
		'name'   => esc_html__( 'Privacy Policy', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Privacy Policy Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-shield-alt',
		'fields' => array(
			'gdpr_warning' => array(
				'type'  => 'text',
				'label' => esc_html__( 'Privacy Policy Label', 'masterstudy-lms-learning-management-system' ),
				'value' => 'I agree with storage and handling of my data by this website.',
			),
			'gdpr_page'    => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Privacy Policy Page', 'masterstudy-lms-learning-management-system' ),
				'options' => $pages,
			),
		),
	);
}
