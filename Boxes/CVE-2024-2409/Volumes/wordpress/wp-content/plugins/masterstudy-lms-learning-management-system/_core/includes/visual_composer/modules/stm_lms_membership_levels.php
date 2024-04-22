<?php
add_action( 'vc_after_init', 'stm_lms_membership_levels_vc', 100 );

function stm_lms_membership_levels_vc() {
	$levels_select = array();
	if ( function_exists( 'pmpro_getAllLevels' ) ) {
		foreach ( pmpro_getAllLevels( false, true ) as $level_number => $level ) {
			$levels_select[ $level->name ] = $level->name;
		}
	}

	vc_map(
		array(
			'name'           => esc_html__( 'STM Membership plans', 'masterstudy-lms-learning-management-system' ),
			'base'           => 'stm_membership_levels',
			'icon'           => 'stm_membership_levels',
			'description'    => esc_html__( 'Membership Plans', 'masterstudy-lms-learning-management-system' ),
			'html_template'  => STM_LMS_Templates::vc_locate_template( 'vc_templates/stm_membership_levels' ),
			'php_class_name' => 'WPBakeryShortCode_Stm_Lms_Membership_Levels',
			'category'       => array(
				esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
			),
			'params'         => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Button position', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'button_position',
					'value'      => array(
						__( 'Before plan items', 'masterstudy-lms-learning-management-system' ) => 'before_level_items',
						__( 'After plan items', 'masterstudy-lms-learning-management-system' )  => 'after_level_items',
					),
					'std'        => 'before_level_items',
				),
				array(
					'type'       => 'param_group',
					'value'      => '',
					'param_name' => 'plan_label',
					'heading'    => esc_html__( 'Plan label', 'masterstudy-lms-learning-management-system' ),
					'params'     => array(
						array(
							'type'       => 'textfield',
							'value'      => '',
							'heading'    => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
							'param_name' => 'plan_title',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'For plan', 'masterstudy-lms-learning-management-system' ),
							'param_name' => 'plan_label_relation',
							'value'      => $levels_select,
							'std'        => '',
						),
					),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'Plan container', 'masterstudy-lms-learning-management-system' ),
					'param_name' => 'css_plan_container',
					'group'      => esc_html__( 'Design options', 'masterstudy-lms-learning-management-system' ),
				),
			),
		)
	);
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Stm_Lms_Membership_Levels extends WPBakeryShortCode {
	}
}
