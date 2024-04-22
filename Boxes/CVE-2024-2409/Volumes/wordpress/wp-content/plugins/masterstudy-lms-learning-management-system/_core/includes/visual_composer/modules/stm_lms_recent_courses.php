<?php

add_action( 'vc_after_init', 'stm_lms_ms_recent_courses_vc' );
/**
 * Callback function to register the STM LMS Courses Grid shortcode with Visual Composer.
 */
function stm_lms_ms_recent_courses_vc() {
	vc_map(
		array(
			'name'           => esc_html__( 'STM LMS Recent Courses', 'masterstudy-lms-learning-management-system' ),
			'base'           => 'stm_lms_recent_courses',
			'icon'           => 'stm_lms_recent_courses',
			'description'    => esc_html__( 'Show Recent Courses', 'masterstudy-lms-learning-management-system' ),
			'html_template'  => STM_LMS_Templates::vc_locate_template( 'vc_templates/stm_lms_recent_courses' ),
			'php_class_name' => 'WPBakeryShortCode_Stm_Lms_Ms_Recent_Courses',
			'category'       => array(
				esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
			),
			'params'         => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Course Card Style', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'course_card_style',
					'value'      => array(
						__( 'Default', 'masterstudy-lms-learning-management-system' )        => 'style_1',
						__( 'Price on Hover', 'masterstudy-lms-learning-management-system' ) => 'style_2',
						__( 'Scale on Hover', 'masterstudy-lms-learning-management-system' ) => 'style_3',
					),
					'std'        => 'style_1',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Course Card Info', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'course_card_info',
					'value'      => array(
						'Center' => 'center',
						'Right'  => 'right',
					),
					'std'        => 'center',
					'dependency' => array(
						'element' => 'course_card_style',
						'value'   => array( 'style_1' ),
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Image Container Height (Ex. : 200px)', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'img_container_height',
					'std'        => '160px',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Image size (Ex. : 200x100)', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'image_size',
					'std'        => '300x225',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number of courses to show', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'posts_per_page',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Per row', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'per_row',
					'value'      => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'std'        => '6',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'Css', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design options', 'masterstudy-lms-learning-management-system' ),
				),
			),
		)
	);
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * WPBakeryShortCode_Stm_Lms_Ms_Recent_Courses class definition.
	 */
	class WPBakeryShortCode_Stm_Lms_Ms_Recent_Courses extends WPBakeryShortCode {
	}
}
