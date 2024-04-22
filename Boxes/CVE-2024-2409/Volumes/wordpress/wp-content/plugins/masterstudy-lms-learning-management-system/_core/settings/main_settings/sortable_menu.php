<?php
function stm_lms_settings_sorting_the_menu_section() {
	$submenu_sortable    = esc_html__( 'Profile Menu Reordering', 'masterstudy-lms-learning-management-system' );
	$menu_items          = apply_filters( 'stm_lms_settings_menu_items', STM_LMS_User_Menu::float_menu_items() );
	$float_main_menu     = apply_filters( 'stm_lms_float_menu_placed_items', $menu_items, 'main' );
	$float_learning_menu = apply_filters( 'stm_lms_float_menu_placed_items', $menu_items, 'learning' );
	$student_menu        = apply_filters( 'stm_lms_sorted_student_menu', $menu_items );
	$instructor_menu     = array_merge( $float_main_menu, $float_learning_menu );

	return array(
		'select_user_role'            => array(
			'type'        => 'button_list',
			'submenu'     => $submenu_sortable,
			'label'       => esc_html__( 'Select user role', 'masterstudy-lms-learning-management-system' ),
			'buttons'     => array(
				array(
					'url'  => '#',
					'text' => esc_html__( 'Instructor', 'masterstudy-lms-learning-management-system' ),
				),
				array(
					'url'  => '#',
					'text' => esc_html__( 'Student', 'masterstudy-lms-learning-management-system' ),
				),
			),
			'description' => esc_html__( 'Select the role in which you want to change the order of menu items', 'masterstudy-lms-learning-management-system' ),
		),
		'sorting_the_menu'            => array(
			'type'        => 'sorter',
			'label'       => esc_html__( 'Profile menu for instructor', 'masterstudy-lms-learning-management-system' ),
			'submenu'     => $submenu_sortable,
			'options'     => array(
				array(
					'id'      => 'sorting_list_1',
					'name'    => esc_html__( 'Enabled', 'masterstudy-lms-learning-management-system' ),
					'options' => $instructor_menu,
				),
				array(
					'id'      => 'sorting_list_2',
					'name'    => esc_html__( 'Disabled', 'masterstudy-lms-learning-management-system' ),
					'options' => array(),
				),
			),
			'description' => '<ul><li><i class="fa fa-lock"></i><p>' . esc_html__( 'There are restrictions for dragging and/or disabling the option.', 'masterstudy-lms-learning-management-system' ) . '</p><li>
								<li><i class="fa fa-exclamation-triangle"></i><p>' . esc_html__( 'The option is important and is not recommended to be disabled.', 'masterstudy-lms-learning-management-system' ) . '</p><li>',
		),
		'sorting_the_menu_student'    => array(
			'type'        => 'sorter',
			'label'       => esc_html__( 'Profile menu for student', 'masterstudy-lms-learning-management-system' ),
			'submenu'     => $submenu_sortable,
			'options'     => array(
				array(
					'id'      => 'student_list_1',
					'name'    => esc_html__( 'Enabled', 'masterstudy-lms-learning-management-system' ),
					'options' => $student_menu,
				),
				array(
					'id'      => 'student_list_2',
					'name'    => esc_html__( 'Disabled', 'masterstudy-lms-learning-management-system' ),
					'options' => array(),
				),
			),
			'description' => '<ul><li><i class="fa fa-exclamation-triangle"></i><p>' . esc_html__( 'The option is important and is not recommended to be disabled.', 'masterstudy-lms-learning-management-system' ) . '</p><li>',
		),
		'sorting_float_menu_main'     => array(
			'type'        => 'sorter',
			'label'       => esc_html__( 'Main section', 'masterstudy-lms-learning-management-system' ),
			'submenu'     => $submenu_sortable,
			'options'     => array(
				array(
					'id'      => 'float_main_list_1',
					'name'    => esc_html__( 'Enabled', 'masterstudy-lms-learning-management-system' ),
					'options' => $float_main_menu,
				),
				array(
					'id'      => 'float_main_list_2',
					'name'    => esc_html__( 'Disabled', 'masterstudy-lms-learning-management-system' ),
					'options' => array(),
				),
			),
			'preview'     => STM_LMS_URL . 'assets/img/sorting_the_menu/main.png',
			'description' => '<ul><li><i class="fa fa-lock"></i><p>' . esc_html__( 'There are restrictions for dragging and/or disabling the option.', 'masterstudy-lms-learning-management-system' ) . '</p><li>
								<li><i class="fa fa-exclamation-triangle"></i><p>' . esc_html__( 'The option is important and is not recommended to be disabled.', 'masterstudy-lms-learning-management-system' ) . '</p><li>',
		),
		'sorting_float_menu_learning' => array(
			'type'        => 'sorter',
			'label'       => esc_html__( 'Learning area', 'masterstudy-lms-learning-management-system' ),
			'submenu'     => $submenu_sortable,
			'options'     => array(
				array(
					'id'      => 'float_learning_list_1',
					'name'    => esc_html__( 'Enabled', 'masterstudy-lms-learning-management-system' ),
					'options' => $float_learning_menu,
				),
				array(
					'id'      => 'float_learning_list_2',
					'name'    => esc_html__( 'Disabled', 'masterstudy-lms-learning-management-system' ),
					'options' => array(),
				),
			),
			'preview'     => STM_LMS_URL . 'assets/img/sorting_the_menu/learning.png',
			'description' => '<ul><li><i class="fa fa-exclamation-triangle"></i><p>' . esc_html__( 'The option is important and is not recommended to be disabled.', 'masterstudy-lms-learning-management-system' ) . '</p><li>',
		),
	);
}
