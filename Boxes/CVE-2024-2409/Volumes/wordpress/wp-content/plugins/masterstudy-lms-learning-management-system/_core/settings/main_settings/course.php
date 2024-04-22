<?php
function stm_lms_settings_course_section() {
	$passed_emojis = array(
		''          => esc_html__( 'Select emoji', 'masterstudy-lms-learning-management-system' ),
		'&#128522;' => '😊 ' . esc_html__( 'Blushed smile face', 'masterstudy-lms-learning-management-system' ),
		'&#128512;' => '😀 ' . esc_html__( 'Grinning face', 'masterstudy-lms-learning-management-system' ),
		'&#128579;' => '🙃 ' . esc_html__( 'Upside down face', 'masterstudy-lms-learning-management-system' ),
		'&#128525;' => '😍 ' . esc_html__( 'Smiling face with heart-eyes', 'masterstudy-lms-learning-management-system' ),
		'&#129395;' => '🥳 ' . esc_html__( 'Partying face', 'masterstudy-lms-learning-management-system' ),
	);
	$failed_emojis = array(
		''          => esc_html__( 'Select emoji', 'masterstudy-lms-learning-management-system' ),
		'&#128542;' => '😔 ' . esc_html__( 'Pensive face', 'masterstudy-lms-learning-management-system' ),
		'&#128544;' => '😠 ' . esc_html__( 'Angry face', 'masterstudy-lms-learning-management-system' ),
		'&#128545;' => '😡 ' . esc_html__( 'Rage face', 'masterstudy-lms-learning-management-system' ),
		'&#128549;' => '😥 ' . esc_html__( 'Disappointed face', 'masterstudy-lms-learning-management-system' ),
	);

	$course_settings_fields = array(
		'name'   => esc_html__( 'Course', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Course Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-book',
		'fields' => array(
			'assignments_quiz_result_emoji_show'   => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Show Emoji in Quiz and Assignments results', 'masterstudy-lms-learning-management-system' ),
				'value' => false,
			),
			'assignments_quiz_passed_emoji'        => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Quiz / Assignment Passed Emoji', 'masterstudy-lms-learning-management-system' ),
				'options'    => $passed_emojis,
				'value'      => '',
				'dependency' => array(
					'key'   => 'assignments_quiz_result_emoji_show',
					'value' => 'not_empty',
				),
			),
			'assignments_quiz_failed_emoji'        => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Quiz / Assignment Failed Emoji', 'masterstudy-lms-learning-management-system' ),
				'options'    => $failed_emojis,
				'value'      => '',
				'dependency' => array(
					'key'   => 'assignments_quiz_result_emoji_show',
					'value' => 'not_empty',
				),
			),
			'pro_banner'                           => array(
				'type'  => 'pro_banner',
				'label' => esc_html__( 'All Course Layouts', 'masterstudy-lms-learning-management-system' ),
				'img'   => STM_LMS_URL . 'assets/img/pro-features/course-formats.png',
				'hint'  => 'slider',
				'desc'  => esc_html__( 'Step up to Pro today and dive into a whole new level of course customization within the Course settings.', 'masterstudy-lms-learning-management-system' ),
			),
			'course_tabs'                          => array(
				'group' => 'started',
				'type'  => 'notice',
				'label' => esc_html__( 'Course Tabs', 'masterstudy-lms-learning-management-system' ),
			),
			'course_tab_description'               => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable "Description" tab', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),
			'course_tab_curriculum'                => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable "Curriculum" tab', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),
			'course_tab_faq'                       => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable "FAQ" tab', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),
			'course_tab_announcement'              => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable "Announcement" tab', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),
			'course_tab_reviews'                   => array(
				'group' => 'ended',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable "Reviews" tab', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),

			'course_levels_config'                 => array(
				'type'   => 'repeater',
				'label'  => esc_html__( 'Course levels', 'masterstudy-lms-learning-management-system' ),
				'fields' => array(
					'id'    => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Level ID', 'masterstudy-lms-learning-management-system' ),
						'columns' => '50',
					),
					'label' => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Level Label', 'masterstudy-lms-learning-management-system' ),
						'columns' => '50',
					),
				),
				'value'  => array(
					array(
						'id'    => 'beginner',
						'label' => esc_html__( 'Beginner', 'masterstudy-lms-learning-management-system' ),
					),
					array(
						'id'    => 'intermediate',
						'label' => esc_html__( 'Intermediate', 'masterstudy-lms-learning-management-system' ),
					),
					array(
						'id'    => 'advanced',
						'label' => esc_html__( 'Advanced', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
			'redirect_after_purchase'              => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Redirect to Checkout after adding to Cart', 'masterstudy-lms-learning-management-system' ),
			),
			'course_allow_new_categories'          => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Allow instructors to create new categories', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Allow instructors create new categories for courses.', 'masterstudy-lms-learning-management-system' ),
			),
			'course_allow_new_question_categories' => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Allow instructors to create new question categories', 'masterstudy-lms-learning-management-system' ),
			),
			'course_allow_presto_player'           => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Allow Presto Player Source for Instructors', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Instructors will able to select videos from Presto Payer Media Hub', 'masterstudy-lms-learning-management-system' ),
			),
			'course_user_auto_enroll'              => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Auto-enrollment for free courses', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Students will automatically enroll in free courses when they preview them.', 'masterstudy-lms-learning-management-system' ),
			),
			'course_lesson_video_types'            => array(
				'group' => 'started',
				'type'  => 'notice',
				'label' => esc_html__( 'Preferred Video Source', 'masterstudy-lms-learning-management-system' ),
				'value' => true,
			),
			'course_lesson_video_type_html'        => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'HTML (MP4)', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'value'   => true,
			),
			'course_lesson_video_type_youtube'     => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'YouTube', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'value'   => true,
			),
			'course_lesson_video_type_vimeo'       => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Vimeo', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'value'   => true,
			),
			'course_lesson_video_type_ext_link'    => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'External link', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'value'   => true,
			),
			'course_lesson_video_type_embed'       => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Embed', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'value'   => true,
			),
			'course_lesson_video_type_shortcode'   => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Shortcode', 'masterstudy-lms-learning-management-system' ),
				'toggle'  => false,
				'columns' => '33',
				'group'   => 'ended',
				'value'   => true,
			),
			'enable_sticky'                        => array(
				'group' => 'started',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
			),
			'enable_sticky_title'                  => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Title in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_sticky_rating'                 => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Rating in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_sticky_teacher'                => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Teacher in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_sticky_category'               => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Category in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_sticky_price'                  => array(
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Price in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_sticky_button'                 => array(
				'group'      => 'ended',
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Enable Buy Button in bottom sticky panel', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'enable_sticky',
					'value' => 'not_empty',
				),
				'columns'    => '50',
			),
			'enable_related_courses'               => array(
				'group' => 'started',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable related courses', 'masterstudy-lms-learning-management-system' ),
			),
			'related_option'                       => array(
				'group'      => 'ended',
				'type'       => 'select',
				'label'      => esc_html__( 'Show related courses based on:', 'masterstudy-lms-learning-management-system' ),
				'options'    => array(
					'by_category' => esc_html__( 'Category', 'masterstudy-lms-learning-management-system' ),
					'by_author'   => esc_html__( 'Author', 'masterstudy-lms-learning-management-system' ),
					'by_level'    => esc_html__( 'Level', 'masterstudy-lms-learning-management-system' ),
				),
				'value'      => 'default',
				'dependency' => array(
					'key'   => 'enable_related_courses',
					'value' => 'not_empty',
				),
			),
			'finish_popup_image_disable'           => array(
				'group' => 'started',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Disable default image for course completion notification', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Disable the display of a default image in the course completion notification.', 'masterstudy-lms-learning-management-system' ),
				'value' => false,
			),
			'finish_popup_image_failed'            => array(
				'type'       => 'image',
				'label'      => esc_html__( 'Upload an image for failed courses', 'masterstudy-lms-learning-management-system' ),
				'hint'       => esc_html__( 'Upload an image to show in the notification of failed courses.', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'finish_popup_image_disable',
					'value' => 'empty',
				),
			),
			'finish_popup_image_success'           => array(
				'type'       => 'image',
				'group'      => 'ended',
				'label'      => esc_html__( 'Upload an image for passed courses', 'masterstudy-lms-learning-management-system' ),
				'hint'       => esc_html__( 'Upload an image to show in the notification of passed courses.', 'masterstudy-lms-learning-management-system' ),
				'dependency' => array(
					'key'   => 'finish_popup_image_disable',
					'value' => 'empty',
				),
			),
		),
	);

	if ( STM_LMS_Helpers::is_pro() ) {
		$course_style_field = array(
			'course_style' => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Course Page Style', 'masterstudy-lms-learning-management-system' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'masterstudy-lms-learning-management-system' ),
					'classic' => esc_html__( 'Classic', 'masterstudy-lms-learning-management-system' ),
					'udemy'   => esc_html__( 'Modern', 'masterstudy-lms-learning-management-system' ),
				),
				'value'   => 'default',
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro&source=course-page-style-course-settings' ),
			),
		);

		$course_settings_fields['fields'] = array_merge( $course_style_field, $course_settings_fields['fields'] );
	}

	return $course_settings_fields;
}
