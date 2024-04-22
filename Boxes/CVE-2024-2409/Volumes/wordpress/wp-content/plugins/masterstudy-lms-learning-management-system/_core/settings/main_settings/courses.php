<?php

/**
 * Course section settings.
 */
function stm_lms_settings_courses_section() {
	$pages = WPCFTO_Settings::stm_get_post_type_array( 'page' );

	$courses_settings_fields = array(
		'name'   => esc_html__( 'Courses', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Courses Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-book',
		'fields' => array(
			'demo_import'                       => array(
				'type' => 'demo_import',
			),
			/*GROUP STARTED*/
			'courses_page'                      => array(
				'group'       => 'started',
				'group_title' => esc_html__( 'Page Layout', 'masterstudy-lms-learning-management-system' ),
				'type'        => 'select',
				'label'       => esc_html__( 'Courses Page', 'masterstudy-lms-learning-management-system' ),
				'options'     => $pages,
				'columns'     => '50',
			),
			'courses_view'                      => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Courses Page Layout', 'masterstudy-lms-learning-management-system' ),
				'options' => array(
					'grid'    => esc_html__( 'Grid', 'masterstudy-lms-learning-management-system' ),
					'list'    => esc_html__( 'List', 'masterstudy-lms-learning-management-system' ),
					'masonry' => esc_html__( 'Masonry', 'masterstudy-lms-learning-management-system' ),
				),
				'soon'    => array( 'masonry' => true ),
				'value'   => 'grid',
				'columns' => '50',
			),
			'courses_per_row'                   => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Courses per row', 'masterstudy-lms-learning-management-system' ),
				'options' => array(
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'6' => 6,
				),
				'value'   => '4',
				'columns' => '33',
			),
			'courses_per_page'                  => array(
				'type'    => 'number',
				'label'   => esc_html__( 'Courses per page', 'masterstudy-lms-learning-management-system' ),
				'value'   => '9',
				'columns' => '33',
			),
			'load_more_type'                    => array(
				'group'   => 'ended',
				'type'    => 'select',
				'label'   => esc_html__( 'Load More Type', 'masterstudy-lms-learning-management-system' ),
				'options' => array(
					'button'   => esc_html__( 'Button', 'masterstudy-lms-learning-management-system' ),
					'infinite' => esc_html__( 'Infinite Scrolling', 'masterstudy-lms-learning-management-system' ),
				),
				'value'   => 'button',
				'columns' => '33',
			),
			/*GROUP ENDED*/

			/*GROUP STARTED*/
			'course_card_style'                 => array(
				'group'       => 'started',
				'group_title' => esc_html__( 'Course Card', 'masterstudy-lms-learning-management-system' ),
				'type'        => 'radio',
				'label'       => esc_html__( 'Course Card Style', 'masterstudy-lms-learning-management-system' ),
				'options'     => array(
					'style_1' => esc_html__( 'Default', 'masterstudy-lms-learning-management-system' ),
					'style_2' => esc_html__( 'Price on Hover', 'masterstudy-lms-learning-management-system' ),
					'style_3' => esc_html__( 'Scale on Hover', 'masterstudy-lms-learning-management-system' ),
				),
				'value'       => 'style_1',
				'columns'     => '50',
			),
			'course_card_view'                  => array(
				'type'       => 'radio',
				'label'      => esc_html__( 'Course Card Info', 'masterstudy-lms-learning-management-system' ),
				'options'    => array(
					'center' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
					'right'  => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				),
				'dependency' => array(
					'key'   => 'course_card_style',
					'value' => 'style_1',
				),
				'value'      => 'center',
				'columns'    => '50',
			),
			'courses_image_size'                => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Course image size', 'masterstudy-lms-learning-management-system' ),
				'description' => esc_html__( 'Ex.: 200x100', 'masterstudy-lms-learning-management-system' ),
				'value'       => '480x380',
				'columns'     => '50',
			),
			'course_card_display_info'          => array(
				'group'       => 'ended',
				'type'        => 'notification_message',
				'description' => sprintf(
				// translators: Card display information.
					__( 'These settings apply only to the <a href="%1$s" target="_blank">Archive</a> page (if you use archive page for Course Catalog).<br> If you use WPBakery or Elementor, you should set up course card settings within LMS widgets. <a href="%2$s" target="_blank">Learn more</a>', 'masterstudy-lms-learning-management-system' ),
					esc_url( 'https://stylemixthemes.com/wp/what-is-a-wordpress-archive-page/' ),
					esc_url( 'https://docs.stylemixthemes.com/masterstudy-lms/lms-settings/courses#course-card' )
				),
			),

			/*GROUP ENDED*/

			'disable_lazyload'                  => array(
				'type'        => 'checkbox',
				'toggle'      => true,
				'label'       => esc_html__( 'Lazy loading', 'masterstudy-lms-learning-management-system' ),
				'description' => esc_html__( 'Enable this if you want to load certain parts of a webpage, such as images until users need them. This can speed up page load times.', 'masterstudy-lms-learning-management-system' ),
			),
			'courses_categories_slug'           => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Category slug', 'masterstudy-lms-learning-management-system' ),
				'value'       => 'stm_lms_course_category',
				'description' => sprintf(
				// translators: Card display information.
					__( 'Put a unique slug to show in the URL before the category, <br/> e.g. example.com/blog/course-category/templates (course-category - slug, templates- category)', 'masterstudy-lms-learning-management-system' ),
				),
			),
			'disable_featured_courses'          => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Show featured courses on top of the list page', 'masterstudy-lms-learning-management-system' ),
			),
			'number_featured_in_archive'        => array(
				'type'       => 'number',
				'label'      => esc_html__( 'Number of featured courses on the Archive page', 'masterstudy-lms-learning-management-system' ),
				'value'      => 3,
				'dependency' => array(
					'key'   => 'disable_featured_courses',
					'value' => 'empty',
				),
			),
			'enable_courses_filter'             => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable filters on the Archive page', 'masterstudy-lms-learning-management-system' ),
			),

			/*GROUP STARTED*/
			'enable_courses_filter_category'    => array(
				'group'       => 'started',
				'type'        => 'checkbox',
				'group_title' => esc_html__( 'Course Filters', 'masterstudy-lms-learning-management-system' ),
				'label'       => esc_html__( 'Enable filter - Category', 'masterstudy-lms-learning-management-system' ),
				'toggle'      => false,
				'columns'     => '33',
				'dependency'  => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_subcategory' => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Subcategory', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_levels'      => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Levels', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_rating'      => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Rating', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_status'      => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Status', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_instructor'  => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Instructor', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			'enable_courses_filter_price'       => array(
				'group'      => 'ended',
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Price', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
			),
			/*GROUP ENDED*/
			'pro_banner'                        => array(
				'type'  => 'pro_banner',
				'label' => esc_html__( 'Upcoming Course Status Addon', 'masterstudy-lms-learning-management-system' ),
				'img'   => STM_LMS_URL . 'assets/img/pro-features/upcoming_nuxy_banner.png',
				'desc'  => esc_html__( 'Promote courses that are not ready for enrollment. Give a preview of the upcoming courses and a countdown to the launch date.', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Unlock', 'masterstudy-lms-learning-management-system' ),
			),
		),
	);

	if ( is_ms_lms_addon_enabled( \MasterStudy\Lms\Plugin\Addons::COOMING_SOON ) && STM_LMS_Helpers::is_pro_plus() ) {
		$courses_filter_field              = array(
			'enable_courses_filter_availability' => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable filter - Availability', 'masterstudy-lms-learning-management-system' ),
				'toggle'     => false,
				'columns'    => '33',
				'dependency' => array(
					'key'   => 'enable_courses_filter',
					'value' => 'not_empty',
				),
				'pro'        => true,
				'pro_url'    => admin_url( 'admin.php?page=stm-lms-go-pro' ),
			),
		);
		$courses_settings_fields['fields'] = array_merge( $courses_settings_fields['fields'], $courses_filter_field );
	}

	return $courses_settings_fields;
}
