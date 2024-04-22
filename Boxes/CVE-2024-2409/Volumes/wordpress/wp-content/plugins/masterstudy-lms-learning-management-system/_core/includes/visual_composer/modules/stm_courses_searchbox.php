<?php

add_action( 'vc_after_init', 'stm_lms_courses_searchbox_vc', 100 );

function stm_lms_courses_searchbox_vc() {
	vc_map(
		array(
			'name'           => esc_html__( 'STM Courses Search box', 'masterstudy-lms-learning-management-system' ),
			'base'           => 'stm_courses_searchbox',
			'icon'           => 'stm_courses_searchbox',
			'description'    => esc_html__( 'Search in LMS Courses', 'masterstudy-lms-learning-management-system' ),
			'html_template'  => STM_LMS_Templates::vc_locate_template( 'vc_templates/stm_courses_searchbox' ),
			'category'       => array(
				esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
			),
			'php_class_name' => 'WPBakeryShortCode_Stm_Lms_Courses_Searchbox',
			'params'         => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Style', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'style',
					'value'      => array(
						'Style 1' => 'style_1',
						'Style 2' => 'style_2',
					),
					'std'        => 'style_1',
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
	class WPBakeryShortCode_Stm_Lms_Courses_Searchbox extends WPBakeryShortCode {
	}
}
