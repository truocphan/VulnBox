<?php

new STM_LMS_Google_Classroom_Auditory();

class STM_LMS_Google_Classroom_Auditory {

	public function __construct() {
		add_filter( 'stm_lms_post_types_array', array( $this, 'auditory_post_type' ), 10, 1 );

		add_action( 'stm_lms_google_classroom_course_imported', array( $this, 'create_auditory' ), 10, 2 );

		add_filter( 'stm_wpcfto_boxes', array( $this, 'boxes' ), 10, 1 );

		add_filter( 'stm_wpcfto_fields', array( $this, 'fields' ), 10, 1 );

		/*google_classroom_auditory*/
		add_filter(
			'wpcfto_field_google_classroom_auditory',
			function () {
				return STM_LMS_PRO_ADDONS . '/google_classrooms/admin_view/google_classroom_auditory.php';
			}
		);
	}

	public function boxes( $data_boxes ) {
		$data_boxes['stm_auditory_settings'] = array(
			'post_type' => array( 'stm-auditory' ),
			'label'     => esc_html__( 'Auditory list', 'masterstudy-lms-learning-management-system-pro' ),
		);

		return $data_boxes;
	}

	public function fields( $fields ) {
		$fields['stm_auditory_settings'] = array(
			'section_curriculum' => array(
				'name'   => esc_html__( 'Settings', 'masterstudy-lms-learning-management-system-pro' ),
				'fields' => array(
					'emails' => array(
						'type'   => 'google_classroom_auditory',
						'label'  => esc_html__( 'Course students', 'masterstudy-lms-learning-management-system-pro' ),
						'fields' => array(
							array(
								'type'  => 'text',
								'label' => esc_html__( 'Student e-mail', 'masterstudy-lms-learning-management-system-pro' ),
							),
						),
					),
				),
			),
		);

		return $fields;
	}

	public function auditory_post_type( $posts ) {
		$posts['stm-g-classrooms'] = array(
			'single' => esc_html__( 'Classrooms', 'masterstudy-lms-learning-management-system-pro' ),
			'plural' => esc_html__( 'Classrooms', 'masterstudy-lms-learning-management-system-pro' ),
			'args'   => array(
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'supports'            => array( 'title', 'editor' ),
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-groups',
			),
		);

		$posts['stm-auditory'] = array(
			'single' => esc_html__( 'Classroom Auditory', 'masterstudy-lms-learning-management-system-pro' ),
			'plural' => esc_html__( 'Classroom Auditory', 'masterstudy-lms-learning-management-system-pro' ),
			'args'   => array(
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'supports'            => array( 'title' ),
				'show_in_menu'        => 'edit.php?post_type=stm-g-classrooms',
				'menu_position'       => 7,
				'menu_icon'           => 'dashicons-groups',
			),
		);

		return $posts;
	}

	public function create_auditory( $course, $course_id ) {
		if ( ! empty( $course['auditory'] ) ) {
			$auditory = sanitize_text_field( $course['auditory'] );

			$auditory_data = array(
				'post_type'   => 'stm-auditory',
				'post_title'  => $auditory,
				'post_status' => 'publish',
			);

			$auditory_id = $this->find_autidory_by_title( $auditory );

			if ( ! empty( $auditory_id ) ) {
				$auditory_data['ID'] = $auditory_id;
			}

			$auditory_id = wp_insert_post( $auditory_data );

			update_post_meta( $course_id, 'stm_lms_auditory_id', $auditory_id );
		}
	}

	public static function find_autidory_by_title( $post_title ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $post_title . "'" );
		return $postid;
	}

}
