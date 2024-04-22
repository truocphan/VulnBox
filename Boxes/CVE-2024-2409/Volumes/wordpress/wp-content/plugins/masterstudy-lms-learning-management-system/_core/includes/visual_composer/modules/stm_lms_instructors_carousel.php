<?php

add_action( 'vc_after_init', 'stm_lms_ms_instructors_carousel_vc' );

function stm_lms_ms_instructors_carousel_vc() {
	vc_map(
		array(
			'name'           => esc_html__( 'STM LMS Instructors Carousel', 'masterstudy-lms-learning-management-system' ),
			'base'           => 'stm_lms_instructors_carousel',
			'icon'           => 'stm_lms_instructors_carousel',
			'description'    => esc_html__( 'Display Instructors in Styled Carousel', 'masterstudy-lms-learning-management-system' ),
			'html_template'  => STM_LMS_Templates::vc_locate_template( 'vc_templates/stm_lms_instructors_carousel' ),
			'php_class_name' => 'WPBakeryShortCode_Stm_Lms_Ms_Instructors_Carousel',
			'category'       => array(
				esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
			),
			'params'         => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Limit', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'limit',
					'std'        => 10,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Per row', 'masterstudy-lms-learning-management-system' ),
					'std'        => 6,
					'param_name' => 'per_row',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Per row on Notebook', 'masterstudy-lms-learning-management-system' ),
					'std'        => 4,
					'param_name' => 'per_row_md',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Per row on Tablet', 'masterstudy-lms-learning-management-system' ),
					'std'        => 2,
					'param_name' => 'per_row_sm',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Per row on Mobile', 'masterstudy-lms-learning-management-system' ),
					'std'        => 1,
					'param_name' => 'per_row_xs',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Title color', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'title_color',
				),
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
					'type'       => 'dropdown',
					'heading'    => __( 'Sort By', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'sort',
					'value'      => array(
						'Default' => '',
						'Rating'  => 'rating',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Prev/Next Buttons', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'prev_next',
					'value'      => array(
						'Enable'  => 'enable',
						'Disable' => 'disable',
					),
					'std'        => 'enable',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Pagination', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'pagination',
					'value'      => array(
						'Enable'  => 'enable',
						'Disable' => 'disable',
					),
					'std'        => 'disable',
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
	class WPBakeryShortCode_Stm_Lms_Ms_Instructors_Carousel extends WPBakeryShortCode {
	}
}
