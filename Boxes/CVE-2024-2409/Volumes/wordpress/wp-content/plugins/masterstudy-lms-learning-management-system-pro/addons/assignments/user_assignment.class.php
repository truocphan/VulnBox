<?php
new STM_LMS_User_Assignment();

class STM_LMS_User_Assignment {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_get_enrolled_assignments', array( $this, 'enrolled_assignments' ) );
		add_filter( 'stm_lms_course_passed_items', array( $this, 'essay_passed' ), 10, 3 );
		add_filter( 'stm_lms_curriculum_item_status', array( $this, 'item_status' ), 10, 5 );

		add_filter(
			'stm_lms_menu_items',
			function ( $menus ) {
				$menus[] = array(
					'order'        => 135,
					'id'           => 'my_assignments',
					'slug'         => 'enrolled-assignments',
					'lms_template' => 'stm-lms-enrolled-assignments',
					'menu_title'   => esc_html__( 'My Assignments', 'masterstudy-lms-learning-management-system-pro' ),
					'menu_icon'    => 'fa-pen-nib',
					'menu_url'     => $this::my_assignments_url(),
					'badge_count'  => STM_LMS_User_Assignment::my_assignments_statuses( get_current_user_id() ),
					'menu_place'   => 'learning',
				);

				return $menus;
			}
		);
	}

	public static function is_my_assignment( $assignment_id, $author_id ) {
		$editor_id = intval( get_post_field( 'post_author', get_post_meta( $assignment_id, 'assignment_id', true ) ) );
		return $editor_id === $author_id;
	}

	public static function get_assignment( $assignment_id ) {
		$editor_id = STM_LMS_User::get_current_user();

		if ( empty( $editor_id ) ) {
			$answer = array(
				'message' => 'Failed',
			);
			return $answer;
		}
		$editor_id = $editor_id['id'];

		if ( ! self::is_my_assignment( $assignment_id, $editor_id ) ) {
			STM_LMS_User::js_redirect( ms_plugin_user_account_url( 'assignments' ) );
			$answer = array(
				'message' => 'Failed',
			);
			return $answer;
		}

		$args = array(
			'post_type'   => 'stm-user-assignment',
			'post_status' => array( 'pending', 'publish' ),
			'post__in'    => array( $assignment_id ),
		);

		$q = new WP_Query( $args );

		$answer = array();

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$status = get_post_status();
				if ( 'pending' !== $status ) {
					$status = get_post_meta( $assignment_id, 'status', true );
				}

				$answer['title']            = get_the_title();
				$answer['status']           = $status;
				$answer['content']          = get_the_content();
				$answer['assignment_title'] = get_the_title( get_post_meta( $assignment_id, 'assignment_id', true ) );

				$answer['files'] = STM_LMS_Assignments::get_draft_attachments( $assignment_id, 'student_attachments' );
			}
		}

		wp_reset_postdata();

		return $answer;
	}

	public static function get_file_ext( $file ) {
		$file_path = get_attached_file( $file->ID );
		return pathinfo( $file_path, PATHINFO_EXTENSION );
	}

	public static function get_file_icon( $file ) {
		$file_ext = self::get_file_ext( $file );

		switch ( $file_ext ) {
			case 'zip':
				return 'file-archive';
			case 'jpg':
			case 'png':
			case 'gif':
				return 'file-image';
			case 'ppt':
			case 'pptx':
				return 'file-powerpoint';
			case 'xls':
			case 'xlsx':
				return 'file-excel';
			case 'psd':
				return 'file-adobe';
			case 'pdf':
				return 'file-pdf';
			case 'mp3':
			case 'ogg':
			case 'wav':
				return 'file-audio';
			default:
				return 'file';
		}
	}

	private static function per_page() {
		return 6;
	}

	public function essay_passed( $passed_items, $course_materials, $user_id ) {
		foreach ( $course_materials as $material_id ) {
			if ( get_post_type( $material_id ) !== 'stm-assignments' ) {
				continue;
			}
			if ( self::assignment_passed( $user_id, $material_id ) ) {
				++$passed_items;
			}
		}
		return $passed_items;
	}

	public static function user_courses( $user_id ) {
		$data    = array();
		$courses = stm_lms_get_user_courses( $user_id, null, null, array( 'course_id' ) );
		foreach ( $courses as $course ) {
			$data[] = array(
				'id'    => $course['course_id'],
				'title' => get_the_title( $course['course_id'] ),
			);
		}
		return $data;
	}

	public static function my_assignments( $user_id, $page = null ) {
		$args = array(
			'post_type'      => 'stm-user-assignment',
			'posts_per_page' => self::per_page(),
			'offset'         => ( $page * self::per_page() ) - self::per_page(),
			'post_status'    => array( 'pending', 'publish' ),
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'student_id',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		if ( ! empty( $_GET['status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status = sanitize_text_field( $_GET['status'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'pending' === $status ) {
				$args['post_status'] = 'pending';
			}
			if ( 'passed' === $status ) {
				$args['post_status']  = 'publish';
				$args['meta_query'][] = array(
					'key'     => 'status',
					'value'   => 'passed',
					'compare' => '=',
				);
			}
			if ( 'not_passed' === $status ) {
				$args['post_status']  = 'publish';
				$args['meta_query'][] = array(
					'key'     => 'status',
					'value'   => 'not_passed',
					'compare' => '=',
				);
			}
		}

		if ( ! empty( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['s'] = sanitize_text_field( $_GET['s'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$q = new WP_Query( $args );

		$posts = array();
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$id            = get_the_ID();
				$course_id     = get_post_meta( $id, 'course_id', true );
				$assignment_id = get_post_meta( $id, 'assignment_id', true );
				$who_view      = get_post_meta( $id, 'who_view', true );

				$posts[] = array(
					'assignment_title' => get_the_title( $assignment_id ),
					'course_title'     => get_the_title( $course_id ),
					'updated_at'       => stm_lms_time_elapsed_string( gmdate( 'Y-m-d H:i:s', get_post_timestamp() ) ),
					'status'           => self::statuses( get_post_status(), get_post_meta( $id, 'status', true ) ),
					'instructor'       => STM_LMS_User::get_current_user( get_post_field( 'post_author', $course_id ) ),
					'url'              => STM_LMS_Lesson::get_lesson_url( $course_id, $assignment_id ),
					'who_view'         => $who_view,
					'pages'            => ceil( $q->found_posts / self::per_page() ),
				);

			}
		}
		return $posts;
	}

	public static function my_assignments_statuses( $user_id ) {
		$args = array(
			'post_type'      => 'stm-user-assignment',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish' ),
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'student_id',
					'value'   => $user_id,
					'compare' => '=',
				),
				array(
					'key'     => 'who_view',
					'value'   => 0,
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		return $q->found_posts;
	}

	public static function statuses( $post_status, $status ) {
		if ( 'pending' === $post_status ) {
			return array(
				'status' => 'pending',
				'label'  => esc_html__( 'Pending...', 'masterstudy-lms-learning-management-system-pro' ),
			);
		}

		if ( 'draft' === $post_status ) {
			return array(
				'status' => 'draft',
				'label'  => esc_html__( 'Draft', 'masterstudy-lms-learning-management-system-pro' ),
			);
		}

		if ( 'publish' === $post_status && 'passed' === $status ) {
			return array(
				'status' => 'passed',
				'label'  => esc_html__( 'Approved', 'masterstudy-lms-learning-management-system-pro' ),
			);
		}

		if ( 'publish' === $post_status && 'not_passed' === $status ) {
			return array(
				'status' => 'not_passed',
				'label'  => esc_html__( 'Declined', 'masterstudy-lms-learning-management-system-pro' ),
			);
		}
	}

	public function assignment_passed( $user_id, $assignment_id ) {
		$args = array(
			'post_type'      => 'stm-user-assignment',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish' ),
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'assignment_id',
					'value'   => $assignment_id,
					'compare' => '=',
				),
				array(
					'key'     => 'student_id',
					'value'   => $user_id,
					'compare' => '=',
				),
				array(
					'key'     => 'status',
					'value'   => 'passed',
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		return $q->found_posts;
	}

	public function item_status( $html, $prev_status, $post_id, $item_id, $user_id ) {
		if ( get_post_type( $item_id ) !== 'stm-assignments' ) {
			return $html;
		}

		if ( self::assignment_passed( $user_id, $item_id ) ) {
			$html = str_replace( 'item__completed', 'item__completed completed', $html );
		}

		return $html;
	}

	public static function has_preview( $file ) {
		$mimes = array(
			'image/png',
			'image/jpg',
			'image/jpeg',
		);

		return in_array( $file->post_mime_type, $mimes ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	public function enrolled_assignments() {
		check_ajax_referer( 'stm_lms_get_enrolled_assingments', 'nonce' );
		$page = intval( $_GET['page'] );
		$user = STM_LMS_User::get_current_user();
		wp_send_json( self::my_assignments( $user['id'], $page ) );
	}

	public static function my_assignments_url() {
		$pages_config = STM_LMS_Page_Router::pages_config();

		return STM_LMS_User::login_page_url() . $pages_config['user_url']['sub_pages']['enrolled_assignments']['url'];
	}
}
