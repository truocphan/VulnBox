<?php

add_action( 'vc_after_init', 'stm_lms_ms_courses_categories_vc' );

function stm_lms_ms_courses_categories_vc() {
	$terms = stm_lms_autocomplete_terms( 'stm_lms_course_taxonomy' );

	vc_map(
		array(
			'name'           => esc_html__( 'STM LMS Courses Categories', 'masterstudy-lms-learning-management-system' ),
			'base'           => 'stm_lms_courses_categories',
			'icon'           => 'stm_lms_courses_categories',
			'description'    => esc_html__( 'Show Courses Categories', 'masterstudy-lms-learning-management-system' ),
			'html_template'  => STM_LMS_Templates::vc_locate_template( 'vc_templates/stm_lms_courses_categories' ),
			'php_class_name' => 'WPBakeryShortCode_Stm_Lms_Ms_Courses_Categories',
			'category'       => array(
				esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
			),
			'params'         => array(
				array(
					'type'       => 'autocomplete',
					'heading'    => esc_html__( 'Select taxonomy', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'taxonomy',
					'settings'   => array(
						'multiple'       => true,
						'sortable'       => true,
						'min_length'     => 1,
						'no_hide'        => true,
						'unique_values'  => true,
						'display_inline' => true,
						'values'         => $terms,
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number of categories to show', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'number',
					'std'        => 6,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Style', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'style',
					'value'      => array(
						'Style 1' => 'style_1',
						'Style 2' => 'style_2',
						'Style 3' => 'style_3',
						'Style 4' => 'style_4',
						'Style 5' => 'style_5',
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
	class WPBakeryShortCode_Stm_Lms_Ms_Courses_Categories extends WPBakeryShortCode {
	}
}
