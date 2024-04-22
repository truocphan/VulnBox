<?php
add_action( 'vc_after_init', 'stm_lms_course_bundles' );

function stm_lms_course_bundles() {
	$bundles = '';

	if ( function_exists( 'stm_lms_autocomplete_bundles_terms' ) ) {
		$bundles = stm_lms_autocomplete_bundles_terms();
	}

	vc_map(
		array(
			'name'          => esc_html__( 'STM Courses Bundle', 'masterstudy' ),
			'base'          => 'stm_lms_course_bundles',
			'icon'          => 'stm_lms_course_bundles',
			'description'   => esc_html__( 'Course Bundles', 'masterstudy' ),
			'html_template' => STM_LMS_PRO_PATH . '/stm-lms-templates/bundles/card/php/vc_list.php',
			'category'      => array(
				esc_html__( 'Content', 'masterstudy' ),
			),
			'params'        => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'masterstudy' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'autocomplete',
					'heading'    => esc_html__( 'Select bundles', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'select_bundles',
					'settings'   => array(
						'multiple'       => true,
						'sortable'       => true,
						'min_length'     => 1,
						'no_hide'        => true,
						'unique_values'  => true,
						'display_inline' => true,
						'values'         => $bundles,
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Columns', 'masterstudy' ),
					'param_name' => 'columns',
					'value'      => array(
						'2' => '2',
						'3' => '3',
					),
					'std'        => '3',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Posts per page', 'masterstudy' ),
					'param_name' => 'posts_per_page',
				),
			),
		)
	);
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Stm_Lms_Course_Bundles extends WPBakeryShortCode {
	}
}
