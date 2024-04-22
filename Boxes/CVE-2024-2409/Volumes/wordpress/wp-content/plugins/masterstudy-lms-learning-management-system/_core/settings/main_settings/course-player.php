<?php
function stm_lms_settings_course_player_section() {
	return array(
		'name'   => esc_html__( 'Course Player', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Course Player Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-chalkboard-teacher',
		'fields' => array(
			'course_player_view'                        => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Appearance', 'masterstudy-lms-learning-management-system' ),
				'options' => array(
					'new' => esc_html__( 'New Player', 'masterstudy-lms-learning-management-system' ),
					'old' => esc_html__( 'Legacy Player', 'masterstudy-lms-learning-management-system' ),
				),
				'value'   => 'new',
			),
			'lesson_style'                              => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Lesson Page Style', 'masterstudy-lms-learning-management-system' ),
				'options'    => array(
					'default' => esc_html__( 'Default', 'masterstudy-lms-learning-management-system' ),
					'classic' => esc_html__( 'Classic', 'masterstudy-lms-learning-management-system' ),
				),
				'value'      => 'default',
				'dependency' => array(
					'key'   => 'course_player_view',
					'value' => 'old',
				),
			),
			'course_player_theme_mode'                  => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Default Theme', 'masterstudy-lms-learning-management-system' ),
				'options'    => array(
					''  => esc_html__( 'Light', 'masterstudy-lms-learning-management-system' ),
					'1' => esc_html__( 'Dark', 'masterstudy-lms-learning-management-system' ),
				),
				'value'      => '',
				'hint'       => esc_html__( 'Users can choose the Default theme for the Lesson Page.', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'course_player_view',
					'value' => 'new',
				),
			),
			'course_player_theme_fonts'                 => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Use Theme fonts', 'masterstudy-lms-learning-management-system' ),
				'value'      => false,
				'dependency' => array(
					'key'   => 'course_player_view',
					'value' => 'new',
				),
			),
			'course_player_brand_icon_navigation'       => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Show brand icon in navigation', 'masterstudy-lms-learning-management-system' ),
				'value'      => false,
				'dependency' => array(
					'key'   => 'course_player_view',
					'value' => 'new',
				),
			),
			'course_player_brand_icon_navigation_image' => array(
				'type'         => 'image',
				'label'        => esc_html__( 'Upload an image for navigation', 'masterstudy-lms-learning-management-system' ),
				'hint'         => esc_html__( 'Upload a square image for a brand icon in the navigation bar.', 'masterstudy-lms-learning-management-system' ),
				'dependency'   => array(
					array(
						'key'   => 'course_player_brand_icon_navigation',
						'value' => 'not_empty',
					),
					array(
						'key'   => 'course_player_view',
						'value' => 'new',
					),
				),
				'dependencies' => '&&',
			),
			'course_player_discussions_sidebar'         => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Discussions Board Sidebar', 'masterstudy-lms-learning-management-system' ),
				'value'      => true,
				'dependency' => array(
					'key'   => 'course_player_view',
					'value' => 'new',
				),
			),
		),
	);
}
