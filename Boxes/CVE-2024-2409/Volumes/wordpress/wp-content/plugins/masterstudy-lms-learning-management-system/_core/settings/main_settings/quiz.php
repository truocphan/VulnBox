<?php

use MasterStudy\Lms\Plugin\Addons;

function stm_lms_quiz_types( $single = false ) {

	$types = array(
		'type'    => 'select',
		'label'   => esc_html__( 'Quiz Style', 'masterstudy-lms-learning-management-system' ),
		'options' => array(
			'default'    => esc_html__( 'One page', 'masterstudy-lms-learning-management-system' ),
			'pagination' => esc_html__( 'Pagination', 'masterstudy-lms-learning-management-system' ),
		),
		'value'   => 'default',
	);

	if ( $single ) {
		$types['options'] = array(
			'global' => esc_html__( 'Default style', 'masterstudy-lms-learning-management-system' ),
		) + $types['options'];

		$types['value'] = 'global';

		$types['hint'] = esc_html__( 'Select the style of displaying questions in the quiz.', 'masterstudy-lms-learning-management-system' );

	}

	return $types;
}

function stm_lms_settings_quiz_section() {
	$quiz_fields = array(
		'name'   => esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Quiz Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-question',
		'fields' => array(
			'quiz_style'      => stm_lms_quiz_types(),
			'quiz_media_type' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Media Type', 'masterstudy-lms-learning-management-system' ),
				'description' => esc_html__( 'Select how users can attach media for quizzes.', 'masterstudy-lms-learning-management-system' ),
				'options'     => array(
					'upload' => esc_html__( 'File Upload', 'masterstudy-lms-learning-management-system' ),
					'url'    => esc_html__( 'URL (Text Input)', 'masterstudy-lms-learning-management-system' ),
				),
				'value'       => 'upload',
			),
			'pro_banner'      => array(
				'type'  => 'pro_banner',
				'label' => esc_html__( 'Question Media', 'masterstudy-lms-learning-management-system' ),
				'img'   => STM_LMS_URL . 'assets/img/pro-features/addons/question-media-addon.png',
				'desc'  => esc_html__( 'Make your quizzes more interactive and engaging with the Question Media addon. Let admins and instructors create quiz questions while adding videos, audio, and images.', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Unlock', 'masterstudy-lms-learning-management-system' ),
			),
		),
	);
	if ( ! is_ms_lms_addon_enabled( Addons::QUESTION_MEDIA ) && STM_LMS_Helpers::is_pro() ) {
		$quiz_fields['fields']['question_media_certificate'] = array(
			'type'        => 'pro_banner',
			'label'       => esc_html__( 'Question Media Addon', 'masterstudy-lms-learning-management-system' ),
			'img'         => STM_LMS_URL . 'assets/img/pro-features/addons/question-media-addon.png',
			'desc'        => sprintf( 'Let admins and instructors create interactive quiz questions while adding videos, audio, and images.' ),
			'search'      => esc_html__( 'Question Media', 'masterstudy-lms-learning-management-system' ),
			'is_enable'   => ! is_ms_lms_addon_enabled( Addons::QUESTION_MEDIA ) && STM_LMS_Helpers::is_pro_plus(),
			'is_pro_plus' => true,
			'hint'        => '',
			'utm_url'     => 'https://stylemixthemes.com/wordpress-lms-plugin/pricing/?utm_source=mswpadmin&utm_medium=question-media-addon-button&utm_campaign=masterstudy-plugin',
		);
	}

	return $quiz_fields;
}
