<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

if ( ! class_exists( 'STM_Lms_Post_Type' ) ) {

	class STM_Lms_Post_Type {
		public function __construct() {
			add_action( 'init', array( $this, 'post_types_init' ), 10 );
		}

		public function post_types_init() {
			$post_types = $this->post_types();

			foreach ( $post_types as $post_type => $post_type_info ) {
				$current_user = STM_LMS_User::get_current_user( null, true );

				if ( ! empty( $current_user['id'] ) && in_array( STM_LMS_Instructor::role(), $current_user['roles'], true ) ) {
					$post_type_info['args']['show_in_menu'] = true;
				}

				$add_args = ( ! empty( $post_type_info['args'] ) ) ? $post_type_info['args'] : array();
				$args     = $this->post_type_args(
					$this->post_types_labels( $post_type_info['single'], $post_type_info['plural'] ),
					$post_type,
					$add_args
				);

				register_post_type( $post_type, $args );
			}
		}

		public function post_types() {
			$base_course_slug = STM_LMS_Options::courses_page_slug();
			$unlock_lesson    = false;
			$current_user     = get_current_user_id();

			$post_id = ( ! empty( $_GET ) && ! empty( $_GET['vc_post_id'] ) ) ? intval( $_GET['vc_post_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_GET ) && ! empty( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = intval( $_GET['post'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( ! empty( $_GET ) && ! empty( $_GET['preview_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = intval( $_GET['preview_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			if ( ! empty( $post_id ) && ! empty( $current_user ) ) {
				$post        = get_post( $post_id );
				$post_author = ! empty( $post ) ? intval( $post->post_author ) : 0;

				if ( $current_user === $post_author ) {
					$unlock_lesson = true;
				}
			}

			if ( is_admin() || current_user_can( 'administrator' ) ) {
				$unlock_lesson = true;
			}

			return apply_filters(
				'stm_lms_post_types_array',
				array(
					'stm-courses'   => array(
						'single' => esc_html__( 'Course', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Courses', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => true,
							'publicly_queryable'  => true,
							'exclude_from_search' => false,
							'has_archive'         => false,
							'rewrite'             => array(
								'slug' => $base_course_slug,
							),
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'supports'            => array(
								'title',
								'editor',
								'thumbnail',
								'excerpt',
								'revisions',
								'author',
								'custom-fields',
							),
							'capability_type'     => 'stm_lms_post',
							'capabilities'        => array(
								'publish_posts'       => 'publish_stm_lms_posts',
								'edit_posts'          => 'edit_stm_lms_posts',
								'delete_posts'        => 'delete_stm_lms_posts',
								'edit_post'           => 'edit_stm_lms_post',
								'delete_post'         => 'delete_stm_lms_post',
								'read_post'           => 'read_stm_lms_post',
								'edit_others_posts'   => 'edit_others_stm_lms_posts',
								'delete_others_posts' => 'delete_others_stm_lms_posts',
								'read_private_posts'  => 'read_private_stm_lms_posts',
							),
						),
					),
					'stm-lessons'   => array(
						'single' => esc_html__( 'Lesson', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Lessons', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => $unlock_lesson,
							'exclude_from_search' => true,
							'publicly_queryable'  => $unlock_lesson,
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'supports'            => array(
								'title',
								'editor',
								'thumbnail',
								'comments',
								'revisions',
								'author',
							),
							'capability_type'     => 'stm_lms_post',
							'capabilities'        => array(
								'publish_posts'       => 'publish_stm_lms_posts',
								'edit_posts'          => 'edit_stm_lms_posts',
								'delete_posts'        => 'delete_stm_lms_posts',
								'edit_post'           => 'edit_stm_lms_post',
								'delete_post'         => 'delete_stm_lms_post',
								'read_post'           => 'read_stm_lms_posts',
								'edit_others_posts'   => 'edit_others_stm_lms_posts',
								'delete_others_posts' => 'delete_others_stm_lms_posts',
								'read_private_posts'  => 'read_private_stm_lms_posts',
							),
						),
					),
					'stm-quizzes'   => array(
						'single' => esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Quizzes', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => false,
							'exclude_from_search' => true,
							'publicly_queryable'  => false,
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'supports'            => array( 'title', 'thumbnail', 'author' ),
							'capability_type'     => 'stm_lms_post',
							'capabilities'        => array(
								'publish_posts'       => 'publish_stm_lms_posts',
								'edit_posts'          => 'edit_stm_lms_posts',
								'delete_posts'        => 'delete_stm_lms_posts',
								'edit_post'           => 'edit_stm_lms_post',
								'delete_post'         => 'delete_stm_lms_post',
								'read_post'           => 'read_stm_lms_posts',
								'edit_others_posts'   => 'edit_others_stm_lms_posts',
								'delete_others_posts' => 'delete_others_stm_lms_posts',
								'read_private_posts'  => 'read_private_stm_lms_posts',
							),
						),
					),
					'stm-questions' => array(
						'single' => esc_html__( 'Question', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Questions', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => false,
							'exclude_from_search' => true,
							'publicly_queryable'  => false,
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'capability_type'     => 'stm_lms_post',
							'capabilities'        => array(
								'publish_posts'       => 'publish_stm_lms_posts',
								'edit_posts'          => 'edit_stm_lms_posts',
								'delete_posts'        => 'delete_stm_lms_posts',
								'edit_post'           => 'edit_stm_lms_post',
								'delete_post'         => 'delete_stm_lms_post',
								'read_post'           => 'read_stm_lms_posts',
								'edit_others_posts'   => 'edit_others_stm_lms_posts',
								'delete_others_posts' => 'delete_others_stm_lms_posts',
								'read_private_posts'  => 'read_private_stm_lms_posts',
							),
							'supports'            => array( 'title' ),
						),
					),
					'stm-reviews'   => array(
						'single' => esc_html__( 'Review', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Reviews', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => false,
							'exclude_from_search' => true,
							'publicly_queryable'  => false,
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'supports'            => array( 'title', 'editor' ),
						),
					),
					'stm-orders'    => array(
						'single' => esc_html__( 'Order', 'masterstudy-lms-learning-management-system' ),
						'plural' => esc_html__( 'Orders', 'masterstudy-lms-learning-management-system' ),
						'args'   => array(
							'public'              => false,
							'exclude_from_search' => true,
							'publicly_queryable'  => false,
							'show_in_menu'        => 'admin.php?page=stm-lms-settings',
							'supports'            => array( 'title' ),
						),
					),
				)
			);
		}

		public function post_types_labels( $singular, $plural ) {
			$admin_bar_name = ( ! empty( $admin_bar_name ) ) ? $admin_bar_name : $plural;

			return array(
				/* translators: %s: Post Type Label */
				/* phpcs:ignore WordPress.WP.I18n */
				'name'               => _x( sprintf( '%s', $plural ), 'post type general name', 'masterstudy-lms-learning-management-system' ),
				/* translators: %s: Post Type Label */
				/* phpcs:ignore WordPress.WP.I18n */
				'singular_name'      => sprintf( _x( '%s', 'post type singular name', 'masterstudy-lms-learning-management-system' ), $singular ),
				/* translators: %s: Post Type Label */
				/* phpcs:ignore WordPress.WP.I18n */
				'menu_name'          => _x( sprintf( '%s', $plural ), 'admin menu', 'masterstudy-lms-learning-management-system' ),
				/* translators: %s: Post Type Label */
				/* phpcs:ignore WordPress.WP.I18n */
				'name_admin_bar'     => sprintf( _x( '%s', 'Admin bar ' . $singular . ' name', 'masterstudy-lms-learning-management-system' ), $admin_bar_name ),
				/* translators: %s: Post Type Label */
				'add_new_item'       => sprintf( __( 'Add New %s', 'masterstudy-lms-learning-management-system' ), $singular ),
				/* translators: %s: Post Type Label */
				'new_item'           => sprintf( __( 'New %s', 'masterstudy-lms-learning-management-system' ), $singular ),
				/* translators: %s: Post Type Label */
				'edit_item'          => sprintf( __( 'Edit %s', 'masterstudy-lms-learning-management-system' ), $singular ),
				/* translators: %s: Post Type Label */
				'view_item'          => sprintf( esc_html__( 'View %s', 'masterstudy-lms-learning-management-system' ), $singular ),
				/* translators: %s: Post Type Label */
				/* phpcs:ignore WordPress.WP.I18n */
				'all_items'          => sprintf( _x( '%s', 'Admin bar ' . $singular . ' name', 'masterstudy-lms-learning-management-system' ), $admin_bar_name ),
				/* translators: %s: Post Type Label */
				'search_items'       => sprintf( __( 'Search %s', 'masterstudy-lms-learning-management-system' ), $plural ),
				/* translators: %s: Post Type Label */
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'masterstudy-lms-learning-management-system' ), $plural ),
				/* translators: %s: Post Type Label */
				'not_found'          => sprintf( __( 'No %s found.', 'masterstudy-lms-learning-management-system' ), $plural ),
				/* translators: %s: Post Type Label */
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'masterstudy-lms-learning-management-system' ), $plural ),
			);
		}

		public function post_type_args( $labels, $slug, $args = array() ) {
			$can_edit     = ( current_user_can( 'edit_posts' ) );
			$default_args = array(
				'labels'             => $labels,
				'public'             => $can_edit,
				'publicly_queryable' => $can_edit,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $slug ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
			);

			return wp_parse_args( $args, $default_args );
		}

	}

	new STM_Lms_Post_Type();
}
