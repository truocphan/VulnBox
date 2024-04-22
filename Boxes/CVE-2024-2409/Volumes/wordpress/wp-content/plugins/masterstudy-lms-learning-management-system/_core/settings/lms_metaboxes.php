<?php

add_filter(
	'stm_wpcfto_boxes',
	function ( $boxes ) {

		$data_boxes = array(
			// TODO Remove Old Course Builder
			// phpcs:ignore
			/*'stm_courses_curriculum' => array(
				'post_type' => array( 'stm-courses' ),
				'label'     => esc_html__( 'Course curriculum', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_courses_settings'   => array(
				'post_type' => array( 'stm-courses' ),
				'label'     => esc_html__( 'Course Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_lesson_settings'    => array(
				'post_type' => array( 'stm-lessons' ),
				'label'     => esc_html__( 'Lesson Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_quiz_questions'     => array(
				'post_type' => array( 'stm-quizzes' ),
				'label'     => esc_html__( 'Quiz Questions', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_quiz_settings'      => array(
				'post_type' => array( 'stm-quizzes' ),
				'label'     => esc_html__( 'Quiz Settings', 'masterstudy-lms-learning-management-system' ),
			),*/
			// TODO Remove Question Settings
			'stm_question_settings' => array(
				'post_type' => array( 'stm-questions' ),
				'label'     => esc_html__( 'Question Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_reviews'           => array(
				'post_type' => array( 'stm-reviews' ),
				'label'     => esc_html__( 'Review info', 'masterstudy-lms-learning-management-system' ),
			),
			'stm_order_info'        => array(
				'post_type'      => array( 'stm-orders' ),
				'label'          => esc_html__( 'Order info', 'masterstudy-lms-learning-management-system' ),
				'skip_post_type' => 1,
			),
		);

		$boxes = array_merge( $data_boxes, $boxes );

		return $boxes;
	}
);

add_filter(
	'stm_wpcfto_fields',
	function ( $fields ) {
		$decimals_num   = STM_LMS_Options::get_option( 'decimals_num', 2 );
		$zeros          = str_repeat( '0', intval( $decimals_num ) - 1 );
		$step           = "0.{$zeros}1";
		$currency       = STM_LMS_Helpers::get_currency();
		$course_levels  = array(
			'' => esc_html__( 'Select level', 'masterstudy-lms-learning-management-system' ),
		);
		$course_levels += STM_LMS_Helpers::get_course_levels();
		$courses        = ( class_exists( 'WPCFTO_Settings' ) ) ? WPCFTO_Settings::stm_get_post_type_array( 'stm-courses' ) : array();
		$certificates   = ( class_exists( 'WPCFTO_Settings' ) ) ? WPCFTO_Settings::stm_get_post_type_array( 'stm-certificates' ) : array();

		$data_fields = array(
			// TODO Remove Old Course Builder
			// Remove items with key: stm_courses_curriculum, stm_courses_settings, stm_lesson_settings, stm_quiz_questions, stm_quiz_settings, stm_question_settings
			'stm_courses_curriculum' => array(
				'section_curriculum' => array(
					'name'   => esc_html__( 'Curriculum', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'curriculum' => array(
							'type'      => 'curriculum',
							'post_type' => apply_filters( 'stm_lms_curriculum_post_types', array( 'stm-lessons', 'stm-quizzes', 'stm-assignments' ) ),
							'sanitize'  => 'wpcfto_sanitize_curriculum',
						),
					),
				),
			),
			'stm_courses_settings'   => array(
				'section_settings'      => array(
					'name'   => esc_html__( 'Settings', 'masterstudy-lms-learning-management-system' ),
					'label'  => esc_html__( 'General Settings', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fa fa-cog',
					'fields' => array(
						'featured'         => array(
							'type'    => 'checkbox',
							'disable' => true,
							'label'   => esc_html__( 'Featured Course', 'masterstudy-lms-learning-management-system' ),
							'hint'    => esc_html__( 'Mark this checkbox to add badge to course "Featured".', 'masterstudy-lms-learning-management-system' ),
						),
						'views'            => array(
							'type'     => 'number',
							'label'    => esc_html__( 'Course Views', 'masterstudy-lms-learning-management-system' ),
							'sanitize' => 'wpcfto_save_number',
							'hint'     => esc_html__( 'Field increments automatically when somebody views the course. But you can set certain amount of views.', 'masterstudy-lms-learning-management-system' ),
						),
						'level'            => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Course Level', 'masterstudy-lms-learning-management-system' ),
							'options' => $course_levels,
						),
						'current_students' => array(
							'type'     => 'number',
							'label'    => esc_html__( 'Current students', 'masterstudy-lms-learning-management-system' ),
							'sanitize' => 'wpcfto_save_number',
						),
						'duration_info'    => array(
							'type'  => 'text',
							'label' => esc_html__( 'Duration info', 'masterstudy-lms-learning-management-system' ),
						),
						'video_duration'   => array(
							'type'  => 'text',
							'label' => esc_html__( 'Video Duration', 'masterstudy-lms-learning-management-system' ),
						),
						'status'           => array(
							'group'   => 'started',
							'type'    => 'radio',
							'label'   => esc_html__( 'Status', 'masterstudy-lms-learning-management-system' ),
							'options' => array(
								''        => esc_html__( 'No status', 'masterstudy-lms-learning-management-system' ),
								'hot'     => esc_html__( 'Hot', 'masterstudy-lms-learning-management-system' ),
								'new'     => esc_html__( 'New', 'masterstudy-lms-learning-management-system' ),
								'special' => esc_html__( 'Special', 'masterstudy-lms-learning-management-system' ),
							),
						),
						'status_dates'     => array(
							'group'      => 'ended',
							'type'       => 'dates',
							'label'      => esc_html__( 'Status Dates', 'masterstudy-lms-learning-management-system' ),
							'sanitize'   => 'wpcfto_save_dates',
							'dependency' => array(
								'key'   => 'status',
								'value' => 'not_empty',
							),
						),
					),
				),
				'section_accessibility' => array(
					'name'   => esc_html__( 'Course Price', 'masterstudy-lms-learning-management-system' ),
					'label'  => esc_html__( 'Accessibility', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-dollar-sign',
					'fields' => array(

						/*GROUP STARTED*/
						'not_single_sale'       => array(
							'group' => 'started',
							'type'  => 'checkbox',
							'label' => esc_html__( 'One-time purchase', 'masterstudy-lms-learning-management-system' ),
							'hint'  => esc_html__( 'Disable one time purchase to make course available only from subscription plans. Also, you can make course free by leaving price field empty', 'masterstudy-lms-learning-management-system' ),
						),
						'price'                 => array(
							'type'        => 'number',
							'label'       => sprintf(
							/* translators: %s: number */
								esc_html__( 'Price (%s)', 'masterstudy-lms-learning-management-system' ),
								$currency
							),
							'placeholder' => sprintf( esc_html__( 'Leave empty if course is free', 'masterstudy-lms-learning-management-system' ), $currency ),
							'sanitize'    => 'wpcfto_save_number',
							'step'        => $step,
							'columns'     => 50,
							'dependency'  => array(
								'key'   => 'not_single_sale',
								'value' => 'empty',
							),
						),
						'sale_price'            => array(
							'type'        => 'number',
							'label'       => sprintf(
							/* translators: %s: number */
								esc_html__( 'Sale Price (%s)', 'masterstudy-lms-learning-management-system' ),
								$currency
							),
							'placeholder' => sprintf( esc_html__( 'Leave empty if no sale price', 'masterstudy-lms-learning-management-system' ), $currency ),
							'sanitize'    => 'wpcfto_save_number',
							'step'        => $step,
							'columns'     => 50,
							'dependency'  => array(
								'key'   => 'not_single_sale',
								'value' => 'empty',
							),
						),
						'sale_price_dates'      => array(
							'group'      => 'ended',
							'type'       => 'dates',
							'label'      => esc_html__( 'Sale Price Dates', 'masterstudy-lms-learning-management-system' ),
							'sanitize'   => 'wpcfto_save_dates',
							'dependency' => array(
								'key'   => 'sale_price',
								'value' => 'not_empty',
							),
							'pro'        => true,
						),
						/*GROUP ENDED*/

						'enterprise_price'      => array(
							'pre_open' => true,
							'type'     => 'number',
							'label'    => sprintf(
							/* translators: %s: dollar */
								esc_html__( 'Enterprise Price (%s)', 'masterstudy-lms-learning-management-system' ),
								$currency
							),
							'hint'     => sprintf( esc_html__( 'Price for group. Leave empty to disable group purchase', 'masterstudy-lms-learning-management-system' ), $currency ),
							'pro'      => true,
							'disabled' => true,
						),

						'not_membership'        => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Not included in membership', 'masterstudy-lms-learning-management-system' ),
						),
						'affiliate_course'      => array(
							'group'   => 'started',
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Affiliate course', 'masterstudy-lms-learning-management-system' ),
							'pro'     => true,
							'pro_url' => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin&utm_medium=ms-udemy&utm_campaign=masterstudy-plugin',
						),
						'affiliate_course_text' => array(
							'type'       => 'text',
							'label'      => esc_html__( 'Button Text', 'masterstudy-lms-learning-management-system' ),
							'dependency' => array(
								'key'   => 'affiliate_course',
								'value' => 'not_empty',
							),
							'columns'    => 50,
							'pro'        => true,
						),
						'affiliate_course_link' => array(
							'group'      => 'ended',
							'type'       => 'text',
							'label'      => esc_html__( 'Button Link', 'masterstudy-lms-learning-management-system' ),
							'dependency' => array(
								'key'   => 'affiliate_course',
								'value' => 'not_empty',
							),
							'columns'    => 50,
							'pro'        => true,
						),
					),
				),
				'section_expiration'    => array(
					'name'   => esc_html__( 'Expiration', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'far fa-clock',
					'fields' => array(
						'expiration_course' => array(
							'group' => 'started',
							'type'  => 'checkbox',
							'label' => esc_html__( 'Time limit', 'masterstudy-lms-learning-management-system' ),
						),
						'end_time'          => array(
							'group'      => 'ended',
							'type'       => 'number',
							'label'      => esc_html__( 'Course expiration (days)', 'masterstudy-lms-learning-management-system' ),
							'value'      => 3,
							'dependency' => array(
								'key'   => 'expiration_course',
								'value' => 'not_empty',
							),
						),
					),
				),
				'section_drip_content'  => array(
					'name'   => esc_html__( 'Content Drip', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-list',
					'fields' => array(
						'drip_content' => array(
							'type'      => 'drip_content',
							'post_type' => array( 'stm-lessons', 'stm-quizzes' ),
							'label'     => esc_html__( 'Sequential Drip Content', 'masterstudy-lms-learning-management-system' ),
							'pro'       => true,
							'pro_url'   => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin-ms&utm_medium=course-settings-backend&utm_campaign=drip-content-pro',
						),
					),
				),
				'section_prereqs'       => array(
					'name'   => esc_html__( 'Prerequisites', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-flag-checkered',
					'fields' => array(
						'prerequisites'              => array(
							'type'      => 'autocomplete',
							'post_type' => array( 'stm-courses' ),
							'label'     => esc_html__( 'Prerequisite Courses', 'masterstudy-lms-learning-management-system' ),
							'pro'       => true,
							'pro_url'   => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin-ms&utm_medium=course-settings-backend&utm_campaign=prerequisites-pro',
						),
						'prerequisite_passing_level' => array(
							'type'        => 'text',
							'classes'     => array( 'short_field' ),
							'placeholder' => esc_html__( 'Percent (%)', 'masterstudy-lms-learning-management-system' ),
							'label'       => esc_html__( 'Prerequisite Passing Percent (%)', 'masterstudy-lms-learning-management-system' ),
							'pro'         => true,
							'pro_url'     => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin-ms&utm_medium=course-settings-backend&utm_campaign=prerequisites-pro',
						),
					),
				),
				'section_announcement'  => array(
					'name'   => esc_html__( 'Announcement', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-bullhorn',
					'fields' => array(
						'announcement' => array(
							'type'     => 'editor',
							'label'    => esc_html__( 'Announcement', 'masterstudy-lms-learning-management-system' ),
							'sanitize' => 'wpcfto_sanitize_editor',
						),
					),
				),
				'section_faq'           => array(
					'name'   => esc_html__( 'FAQ', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-question',
					'fields' => array(
						'faq' => array(
							'type'  => 'faq',
							'label' => esc_html__( 'FAQ', 'masterstudy-lms-learning-management-system' ),
						),
					),
				),
				'section_certificate'   => array(
					'name'   => esc_html__( 'Certificate', 'masterstudy-lms-learning-management-system' ),
					'icon'   => 'fas fa-certificate',
					'fields' => array(
						'course_certificate' => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Select Certificate', 'masterstudy-lms-learning-management-system' ),
							'options' => $certificates,
							'value'   => '',
							'pro'     => true,
							'pro_url' => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin-ms&utm_medium=course-settings-backend&utm_campaign=certificate-pro',
							'classes' => array( 'short_field' ),
						),
					),
				),
			),
			'stm_lesson_settings'    => array(
				'section_lesson_settings' => array(
					'name'   => esc_html__( 'Lesson Settings', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'type'                => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Lesson type', 'masterstudy-lms-learning-management-system' ),
							'options' => array(
								'text'  => esc_html__( 'Text', 'masterstudy-lms-learning-management-system' ),
								'video' => esc_html__( 'Video', 'masterstudy-lms-learning-management-system' ),
							),
							'value'   => 'text',
						),
						'video_type'          => array(
							'type'       => 'select',
							'label'      => esc_html__( 'Source type', 'masterstudy-lms-learning-management-system' ),
							'options'    => ms_plugin_video_sources(),
							'value'      => ms_plugin_get_default_source(),
							'dependency' => array(
								'key'   => 'type',
								'value' => 'video',
							),
						),
						'presto_player_idx'   => array(
							'type'         => 'select',
							'label'        => esc_html__( 'Presto Player videos', 'masterstudy-lms-learning-management-system' ),
							'options'      => ms_plugin_presto_player_post_data( true ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'presto_player',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
							'value'        => ms_plugin_presto_player_default(),
						),
						'lesson_video'        => array(
							'type'         => 'image',
							'label'        => esc_html__( 'Lesson video', 'masterstudy-lms-learning-management-system' ),
							'placeholder'  => esc_html__( 'Upload video', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'html',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_video_poster' => array(
							'type'         => 'image',
							'label'        => esc_html__( 'Lesson video poster', 'masterstudy-lms-learning-management-system' ),
							'placeholder'  => esc_html__( 'Upload video poster', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'html || ext_link',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_video_width'  => array(
							'type'         => 'number',
							'label'        => esc_html__( 'Lesson video width (px)', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'html',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_shortcode'    => array(
							'type'         => 'text',
							'label'        => esc_html__( 'Shortcode', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'shortcode',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_embed_ctx'    => array(
							'type'         => 'editor',
							'label'        => esc_html__( 'Embed Iframe content', 'masterstudy-lms-learning-management-system' ),
							'sanitize'     => 'wpcfto_sanitize_editor',
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'embed',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_youtube_url'  => array(
							'type'         => 'text',
							'label'        => esc_html__( 'YouTube video URL', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'youtube',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_stream_url'   => array(
							'type'       => 'text',
							'label'      => esc_html__( 'YouTube video URL', 'masterstudy-lms-learning-management-system' ),
							'dependency' => array(
								'key'   => 'type',
								'value' => 'stream',
							),
						),
						'lesson_vimeo_url'    => array(
							'type'         => 'text',
							'label'        => esc_html__( 'Vimeo video URL', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'vimeo',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'lesson_ext_link_url' => array(
							'type'         => 'text',
							'label'        => esc_html__( 'External Link', 'masterstudy-lms-learning-management-system' ),
							'dependency'   => array(
								array(
									'key'   => 'video_type',
									'value' => 'ext_link',
								),
								array(
									'key'   => 'type',
									'value' => 'video',
								),
							),
							'dependencies' => '&&',
						),
						'duration'            => array(
							'type'  => 'text',
							'label' => esc_html__( 'Lesson duration', 'masterstudy-lms-learning-management-system' ),
						),
						'preview'             => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Lesson preview (Lesson will be available to everyone)', 'masterstudy-lms-learning-management-system' ),
						),
						'lesson_excerpt'      => array(
							'type'     => 'editor',
							'label'    => esc_html__( 'Lesson Frontend description', 'masterstudy-lms-learning-management-system' ),
							'sanitize' => 'wpcfto_sanitize_editor',
						),
					),
				),
			),
			'stm_quiz_questions'     => array(
				'section_questions' => array(
					'name'   => esc_html__( 'Questions', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'questions' => array(
							'type'      => 'questions_v2',
							'label'     => esc_html__( 'Questions', 'masterstudy-lms-learning-management-system' ),
							'post_type' => array( 'stm-questions' ),
						),
					),
				),
			),
			'stm_quiz_settings'      => array(
				'section_quiz_settings' => array(
					'name'   => esc_html__( 'Quiz Settings', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'quiz_style'       => stm_lms_quiz_types( true ),
						'lesson_excerpt'   => array(
							'type'     => 'editor',
							'label'    => esc_html__( 'Quiz Frontend description', 'masterstudy-lms-learning-management-system' ),
							'sanitize' => 'wpcfto_sanitize_editor',
						),
						'duration'         => array(
							'type'  => 'duration',
							'label' => esc_html__( 'Quiz duration', 'masterstudy-lms-learning-management-system' ),
						),
						'duration_measure' => array(
							'type' => 'not_exist',
						),
						'correct_answer'   => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Show correct answer', 'masterstudy-lms-learning-management-system' ),
						),
						'passing_grade'    => array(
							'type'  => 'number',
							'label' => esc_html__( 'Passing grade (%)', 'masterstudy-lms-learning-management-system' ),
						),
						're_take_cut'      => array(
							'type'  => 'number',
							'label' => esc_html__( 'Points total cut after re-take (%)', 'masterstudy-lms-learning-management-system' ),
						),
						'random_questions' => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Randomize questions', 'masterstudy-lms-learning-management-system' ),
						),
					),
				),
			),
			'stm_question_settings'  => array(
				'section_question_settings' => array(
					'name'   => esc_html__( 'Question Settings', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'type'                 => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Question type', 'masterstudy-lms-learning-management-system' ),
							'options' => array(
								'single_choice' => esc_html__( 'Single choice', 'masterstudy-lms-learning-management-system' ),
								'multi_choice'  => esc_html__( 'Multi choice', 'masterstudy-lms-learning-management-system' ),
								'true_false'    => esc_html__( 'True or False', 'masterstudy-lms-learning-management-system' ),
								'item_match'    => esc_html__( 'Item Match', 'masterstudy-lms-learning-management-system' ),
								'image_match'   => esc_html__( 'Image Match', 'masterstudy-lms-learning-management-system' ),
								'keywords'      => esc_html__( 'Keywords', 'masterstudy-lms-learning-management-system' ),
								'fill_the_gap'  => esc_html__( 'Fill the Gap', 'masterstudy-lms-learning-management-system' ),
							),
							'value'   => 'single_choice',
						),
						'answers'              => array(
							'type'         => 'answers',
							'label'        => esc_html__( 'Answers', 'masterstudy-lms-learning-management-system' ),
							'requirements' => 'type',
						),
						'question_explanation' => array(
							'type'  => 'textarea',
							'label' => esc_html__( 'Question result explanation', 'masterstudy-lms-learning-management-system' ),
						),
						'question_view_type'   => array(
							'type' => 'not_exist',
						),
					),
				),
			),
			'stm_reviews'            => array(
				'section_data' => array(
					'name'   => esc_html__( 'Review info', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'review_course' => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Course Reviewed', 'masterstudy-lms-learning-management-system' ),
							'options' => $courses,
						),
						'review_user'   => array(
							'type'      => 'autocomplete',
							'post_type' => array( 'post' ),
							'label'     => esc_html__( 'User Reviewed', 'masterstudy-lms-learning-management-system' ),
							'limit'     => 1,
						),
						'review_mark'   => array(
							'type'    => 'select',
							'label'   => esc_html__( 'User Review mark', 'masterstudy-lms-learning-management-system' ),
							'options' => array(
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',
							),
						),
					),
				),
			),
			'stm_order_info'         => array(
				'order_info' => array(
					'name'   => esc_html__( 'Order', 'masterstudy-lms-learning-management-system' ),
					'fields' => array(
						'order' => array(
							'type' => 'order',
						),
					),
				),
			),
		);

		$fields = array_merge( $data_fields, $fields );

		return $fields;
	}
);
