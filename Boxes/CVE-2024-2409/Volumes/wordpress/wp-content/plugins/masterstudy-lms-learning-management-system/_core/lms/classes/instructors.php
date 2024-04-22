<?php

use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;

STM_LMS_Instructor::init();

class STM_LMS_Instructor extends STM_LMS_User {
	public static function init() {
		add_filter( 'map_meta_cap', 'STM_LMS_Instructor::meta_cap', 10, 4 );

		add_action( 'wp_ajax_stm_lms_get_instructor_courses', 'STM_LMS_Instructor::get_courses' );

		add_filter( 'manage_stm-courses_posts_columns', 'STM_LMS_Instructor::columns' );
		add_action( 'manage_stm-courses_posts_custom_column', 'STM_LMS_Instructor::column_fields', 10, 2 );

		add_filter( 'manage_stm-lessons_posts_columns', 'STM_LMS_Instructor::lesson_columns' );
		add_action( 'manage_stm-lessons_posts_custom_column', 'STM_LMS_Instructor::column_fields', 10, 2 );

		add_filter( 'manage_stm-quizzes_posts_columns', 'STM_LMS_Instructor::quiz_columns' );
		add_action( 'manage_stm-quizzes_posts_custom_column', 'STM_LMS_Instructor::column_fields', 10, 2 );

		add_action( 'admin_enqueue_scripts', 'STM_LMS_Instructor::scripts' );

		add_action( 'wp_ajax_stm_lms_change_lms_author', 'STM_LMS_Instructor::change_author' );

		add_filter( 'pre_get_posts', 'STM_LMS_Instructor::posts_for_current_author' );

		add_action( 'wp_ajax_stm_lms_add_student_manually', 'STM_LMS_Instructor::add_student_manually' );

		add_action( 'wp_ajax_stm_lms_change_course_status', 'STM_LMS_Instructor::change_status' );

		add_action( 'wp_ajax_stm_lms_get_users_submissions', 'STM_LMS_Instructor::get_submissions' );

		add_action( 'wp_ajax_stm_lms_update_user_status', 'STM_LMS_Instructor::update_user_status' );

		add_action( 'wp_ajax_stm_lms_ban_user', 'STM_LMS_Instructor::ban_user' );

		add_action( 'pending_to_publish', 'STM_LMS_Instructor::post_published', 10, 2 );

		add_action( 'admin_menu', 'STM_LMS_Instructor::manage_users', 10000 );

		/*Plug for add student*/
		if ( ! class_exists( 'STM_LMS_Enterprise_Courses' ) ) {
			add_action(
				'wp_ajax_stm_lms_get_enterprise_groups',
				function () {
					return 'ok';
				}
			);
		}

		add_filter(
			'stm_lms_menu_items',
			function ( $menus ) {
				if ( self::instructor_can_add_students() && self::is_instructor() ) {
					$menus[] = array(
						'order'        => 60,
						'id'           => 'add_student',
						'slug'         => 'add-students',
						'lms_template' => 'stm-lms-instructor-add-students',
						'menu_title'   => esc_html__( 'Add Student', 'masterstudy-lms-learning-management-system' ),
						'menu_icon'    => 'fa-user-plus',
						'menu_url'     => STM_LMS_Instructor::instructor_add_students_url(),
						'menu_place'   => 'main',
					);

					return $menus;
				}

				return $menus;
			}
		);
	}

	public static function post_published( $post ) {
		$post_id = $post->ID;
		if ( get_post_type( $post_id ) === 'stm-courses' ) {
			$course_title = get_the_title( $post_id );
			$author_id    = intval( get_post_field( 'post_author', $post_id ) );

			$user    = STM_LMS_User::get_current_user( $author_id );
			$subject = esc_html__( 'Course published', 'masterstudy-lms-learning-management-system' );
			$message = sprintf(
				/* translators: %1$s Course Title, %2$s User Login */
				esc_html__( 'Your course - %1$s was approved, and is now live on the website', 'masterstudy-lms-learning-management-system' ),
				$course_title,
			);

			STM_LMS_Helpers::send_email( $user['email'], $subject, $message, 'stm_lms_course_published', compact( 'course_title' ) );
		}
	}

	public static function change_status() {
		check_ajax_referer( 'stm_lms_change_course_status', 'nonce' );

		$statuses = array(
			'draft',
			'publish',
		);

		$user = STM_LMS_User::get_current_user();

		if ( empty( $user['id'] ) ) {
			die;
		}

		$user_id = $user['id'];

		if ( empty( $_GET['post_id'] ) || ( empty( $_GET['status'] ) && in_array( $_GET['status'], $statuses, true ) ) ) {
			die;
		}

		$course_id = intval( $_GET['post_id'] );
		$status    = sanitize_text_field( $_GET['status'] );

		if ( ! STM_LMS_Course::check_course_author( $course_id, $user_id ) ) {
			die;
		}

		if ( apply_filters( 'stm_lms_before_change_course_status', false ) ) {
			do_action( 'stm_lms_change_course_status', $status );
			wp_send_json( $status );
		}

		if ( 'publish' === $status ) {
			$premoderation = STM_LMS_Options::get_option( 'course_premoderation', false );
			$status        = ( $premoderation ) ? 'pending' : 'publish';
		}

		wp_update_post(
			array(
				'ID'          => $course_id,
				'post_status' => $status,
			)
		);

		wp_send_json( $status );
	}

	public static function change_author() {
		check_ajax_referer( 'stm_lms_change_lms_author', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$author_id = intval( $_GET['author_id'] );
		$course_id = intval( $_GET['post_id'] );

		$arg = array(
			'ID'          => $course_id,
			'post_author' => $author_id,
		);

		wp_update_post( $arg );

		$material_ids = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );

		/*Change all authors of curriculum*/
		if ( ! empty( $material_ids ) ) {
			foreach ( $material_ids as $material_id ) {
				wp_update_post(
					array(
						'ID'          => $material_id,
						'post_author' => $author_id,
					)
				);
			}
		}

		wp_send_json( $author_id );
	}

	public static function columns( $columns ) {
		$columns['lms_course_students'] = esc_html__( 'Course Students', 'masterstudy-lms-learning-management-system' );

		$columns['lms_author'] = esc_html__( 'Course Author', 'masterstudy-lms-learning-management-system' );

		unset( $columns['author'] );

		return $columns;
	}

	public static function lesson_columns( $columns ) {
		$columns['lms_author'] = esc_html__( 'Lesson Author', 'masterstudy-lms-learning-management-system' );

		unset( $columns['author'] );

		return $columns;
	}

	public static function quiz_columns( $columns ) {
		$columns['lms_author'] = esc_html__( 'Quiz Author', 'masterstudy-lms-learning-management-system' );

		unset( $columns['author'] );

		return $columns;
	}

	public static function column_fields( $columns, $post_id ) {
		switch ( $columns ) {
			case 'lms_author':
				$args           = array(
					'role__in' => array( 'keymaster', 'administrator', 'stm_lms_instructor' ),
					'order'    => 'ASC',
					'orderby'  => 'display_name',
				);
				$wp_user_query  = new WP_User_Query( $args );
				$authors        = $wp_user_query->get_results();
				$authors        = wp_list_pluck( $authors, 'data' );
				$post_author_id = get_post_field( 'post_author', $post_id );
				?>
				<select name="lms_author" data-post="<?php echo esc_attr( $post_id ); ?>">
					<?php foreach ( $authors as $author ) : ?>
						<option value="<?php echo esc_attr( $author->ID ); ?>" <?php selected( $post_author_id, $author->ID ); ?>>
							<?php echo esc_html( $author->user_login ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<a href="<?php echo esc_url( STM_LMS_Helpers::get_current_url() ); ?>" class="button action">
					<?php esc_html_e( 'Change Author', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<?php
				break;
			case 'lms_course_students':
				?>
				<a href="<?php echo esc_url( admin_url( "?page=stm-lms-dashboard#/course/{$post_id}" ) ); ?>" class="button action">
					<?php esc_html_e( 'Manage students', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<?php
				break;
		}
	}

	public static function posts_for_current_author( $query ) {
		if ( is_admin() ) {
			global $pagenow;
			if ( 'edit.php' !== $pagenow || ! $query->is_admin ) {
				return $query;
			}

			if ( ! function_exists( 'cache_users' ) ) {
				require_once ABSPATH . 'wp-includes/pluggable.php';
			}

			if ( ! current_user_can( 'edit_others_posts' ) ) {
				global $user_ID;
				$query->set( 'author', $user_ID );
			}
		}

		return $query;
	}

	public static function scripts( $hook ) {
		if ( ( 'stm-courses' === get_post_type() || 'stm-lessons' === get_post_type() || 'stm-quizzes' === get_post_type() ) && 'edit.php' === $hook ) {
			stm_lms_register_script( 'admin/change_lms_author', array( 'jquery' ), true );
			wp_localize_script(
				'stm-lms-admin/change_lms_author',
				'stm_lms_change_lms_author',
				array(
					'notice' => esc_html__(
						"After changing the course's author, the author of all lessons, quizzes and assignments related to this course will be changed automatically. Do you really want to change the author of the course?",
						'masterstudy-lms-learning-management-system'
					),
				)
			);
			stm_lms_register_style( 'admin/change_lms_author' );
		}
	}

	public static function meta_cap( $caps, $cap, $user_id, $args ) {
		remove_filter( 'map_meta_cap', 'STM_LMS_Instructor::meta_cap', 10 );

		if ( ! self::is_instructor() ) {
			return $caps;
		}

		if ( 'edit_stm_lms_post' === $cap || 'delete_stm_lms_post' === $cap || 'read_stm_lms_post' === $cap ) {
			$post      = get_post( $args[0] );
			$post_type = get_post_type_object( $post->post_type );

			$caps = array();
		}

		if ( 'edit_stm_lms_post' === $cap ) {
			if ( strval( $user_id ) === strval( $post->post_author ) ) {
				$caps[] = $post_type->cap->edit_posts;
			} else {
				$caps[] = $post_type->cap->edit_others_posts;
			}
		}

		if ( 'delete_stm_lms_post' === $cap ) {
			if ( strval( $user_id ) === strval( $post->post_author ) ) {
				$caps[] = $post_type->cap->delete_posts;
			} else {
				$caps[] = $post_type->cap->delete_others_posts;
			}
		}

		if ( 'read_stm_lms_post' === $cap ) {

			if ( 'private' !== $post->post_status ) {
				$caps[] = 'read';
			} elseif ( $user_id === $post->post_author ) {
				$caps[] = 'read';
			} else {
				$caps[] = $post_type->cap->read_private_posts;
			}
		}

		add_filter( 'map_meta_cap', 'STM_LMS_Instructor::meta_cap', 10, 4 );

		return $caps;
	}

	public static function instructors_enabled() {
		return STM_LMS_Options::get_option( 'enable_instructors', false );
	}

	public static function role() {
		return 'stm_lms_instructor';
	}

	public static function is_instructor( $user_id = null ) {
		$user = parent::get_current_user( $user_id, true, false, true );
		if ( empty( $user['id'] ) ) {
			return false;
		}

		/*If admin*/
		if ( in_array( 'administrator', $user['roles'], true ) ) {
			return true;
		}
		return in_array( self::role(), $user['roles'], true );
	}

	public static function has_instructor_role( $user_id = null ) {
		$user = parent::get_current_user( $user_id, true, false, true );
		if ( empty( $user['id'] ) ) {
			return false;
		}

		return in_array( self::role(), $user['roles'], true );
	}

	public static function instructor_links() {
		return apply_filters(
			'stm_lms_instructor_links',
			array(
				'add_new' => ms_plugin_manage_course_url(),
			)
		);
	}

	public static function get_courses( $args = array(), $return = false, $get_all = false ) {
		if ( ! $return ) {
			check_ajax_referer( 'stm_lms_get_instructor_courses', 'nonce' );
		}

		$user = STM_LMS_User::get_current_user();
		if ( empty( $user['id'] ) ) {
			die;
		}

		$user_id = $user['id'];
		$pp      = ( empty( $_GET['pp'] ) ) ? 8 : sanitize_text_field( $_GET['pp'] );
		$offset  = ( ! empty( $_GET['offset'] ) ) ? intval( $_GET['offset'] ) : 0;

		if ( ! empty( $args['posts_per_page'] ) ) {
			$pp = intval( $args['posts_per_page'] );
		}

		$offset       = $offset * $pp;
		$default_args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => $pp,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'offset'         => $offset,
		);

		if ( ! $get_all ) {
			$default_args['author'] = $user_id;
		}

		$args = wp_parse_args( $args, $default_args );

		if ( empty( $args['s'] ) && ! empty( $_GET['s'] ) ) {
			$args['s'] = sanitize_text_field( $_GET['s'] );
		}

		if ( ! empty( $_GET['status'] ) ) {
			$args['post_status'] = sanitize_text_field( $_GET['status'] );
		}

		if ( $return ) {
			return self::masterstudy_lms_get_instructor_courses( $args, $offset, $pp );
		}

		wp_send_json( self::masterstudy_lms_get_instructor_courses( $args, $offset, $pp ) );
	}

	public static function masterstudy_lms_get_instructor_courses( $args = array(), $offset = 0, $pp = 0 ) {
		$result  = array( 'posts' => array() );
		$get_ids = ( ! empty( $_GET['ids_only'] ) );

		$query = new WP_Query( $args );

		$total              = $query->found_posts;
		$result['total']    = $total <= $offset + $pp;
		$result['found']    = $total;
		$result['per_page'] = (int) $pp;
		$result['pages']    = (int) ceil( $result['found'] / $result['per_page'] );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();

				if ( $get_ids ) {
					$result['posts'][ $id ] = html_entity_decode( get_the_title( $id ) );
					continue;
				}

				$rating       = get_post_meta( $id, 'course_marks', true );
				$rates        = STM_LMS_Course::course_average_rate( $rating );
				$average      = $rates['average'];
				$percent      = $rates['percent'];
				$status       = get_post_status( $id );
				$price        = get_post_meta( $id, 'price', true );
				$availability = get_post_meta( $id, 'coming_soon_status', true );
				$sale_price   = get_post_meta( $id, 'sale_price', true );

				switch ( $status ) {
					case 'publish':
						$status_label = esc_html__( 'Published', 'masterstudy-lms-learning-management-system' );
						break;
					case 'pending':
						$status_label = esc_html__( 'Pending', 'masterstudy-lms-learning-management-system' );
						break;
					default:
						$status_label = esc_html__( 'Draft', 'masterstudy-lms-learning-management-system' );
						break;
				}

				$post_status  = STM_LMS_Course::get_post_status( $id );
				$image        = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '272x161' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$image_small  = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '50x50' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$is_featured  = get_post_meta( $id, 'featured', true );
				$rating_count = ( ! empty( $rating ) ) ? count( $rating ) : '';

				$post = array(
					'id'               => $id,
					'time'             => get_post_time( 'U', true ),
					'title'            => html_entity_decode( get_the_title() ),
					/* translators: %s Last Updated */
					'updated'          => sprintf( esc_html__( 'Last updated: %s', 'masterstudy-lms-learning-management-system' ), stm_lms_time_elapsed_string( get_post( $id )->post_modified ) ),
					'link'             => get_the_permalink(),
					'image'            => $image,
					'image_small'      => $image_small,
					'terms'            => wp_get_post_terms( $id, 'stm_lms_course_taxonomy' ),
					'status'           => $status,
					'status_label'     => $status_label,
					'availability'     => $availability,
					'percent'          => $percent,
					'is_featured'      => $is_featured,
					'average'          => $average,
					'total'            => $rating_count,
					'views'            => STM_LMS_Course::get_course_views( $id ),
					'price'            => STM_LMS_Helpers::display_price( $price ),
					'simple_price'     => $sale_price ? $sale_price : $price,
					'sale_price'       => $sale_price ? STM_LMS_Helpers::display_price( $sale_price ) : 0,
					'edit_link'        => ms_plugin_manage_course_url() . "/$id",
					'coming_soon_link' => ms_plugin_manage_course_url() . "/$id/settings/access",
					'post_status'      => $post_status,
				);

				$post['sale_price'] = ( ! empty( $sale_price ) ) ? STM_LMS_Helpers::display_price( $sale_price ) : '';
				$result['posts'][]  = $post;
			}
		}

		wp_reset_postdata();

		return $result;
	}

	public static function transient_name( $user_id, $name = '' ) {
		return "stm_lms_instructor_{$user_id}_{$name}";
	}

	public static function my_rating_v2( $user = '' ) {
		$user    = ( ! empty( $user ) ) ? $user : STM_LMS_User::get_current_user();
		$user_id = $user['id'];

		$sum_rating_key    = 'sum_rating';
		$total_reviews_key = 'total_reviews';
		$sum_rating        = ( ! empty( get_user_meta( $user_id, $sum_rating_key, true ) ) ) ? get_user_meta( $user_id, $sum_rating_key, true ) : 0;
		$total_reviews     = ( ! empty( get_user_meta( $user_id, $total_reviews_key, true ) ) ) ? get_user_meta( $user_id, $total_reviews_key, true ) : 0;

		if ( empty( $sum_rating ) || empty( $total_reviews ) ) {
			return array(
				'total'       => 0,
				'average'     => 0,
				'total_marks' => '',
				'percent'     => 0,
			);
		}

		$ratings['total']     = intval( $sum_rating );
		$ratings['average']   = floatval( number_format( $sum_rating / $total_reviews, 2 ) );
		$label                = _n( 'Review', 'Reviews', $total_reviews, 'masterstudy-lms-learning-management-system' );
		$ratings['marks_num'] = intval( $total_reviews );
		/* translators: %s Total Reviews, Label */
		$ratings['total_marks'] = sprintf( _x( '%1$s %2$s', '"1 Review" or "2 Reviews"', 'masterstudy-lms-learning-management-system' ), $total_reviews, $label );

		$ratings['percent'] = intval( ( $ratings['average'] * 100 ) / 5 );

		return $ratings;
	}

	public static function my_rating( $user = '' ) {
		$ratings = array();
		$user    = ( ! empty( $user ) ) ? $user : STM_LMS_User::get_current_user();
		$user_id = $user['id'];

		$transient_name = self::transient_name( $user_id, 'rating' );
		if ( ! empty( $transient_name ) ) {
			$ratings = get_transient( $transient_name );
		}
		if ( false === $ratings ) {
			$args = array(
				'post_type'      => 'stm-courses',
				'posts_per_page' => '-1',
				'author'         => $user_id,
			);

			$q = new WP_Query( $args );

			$ratings = array(
				'total_marks' => '',
				'total'       => 0,
				'average'     => 0,
				'percent'     => 0,
			);

			if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					$marks = get_post_meta( get_the_ID(), 'course_marks', true );
					if ( ! empty( $marks ) ) {
						foreach ( $marks as $mark ) {
							++$ratings['total_marks'];
							$ratings['total'] += $mark;
						}
					} else {
						continue;
					}
				}

				$ratings['average'] = ( $ratings['total'] && $ratings['total_marks'] ) ? round( $ratings['total'] / $ratings['total_marks'], 2 ) : 0;

				$ratings['marks_num'] = $ratings['total_marks'];

				$ratings['percent'] = ( $ratings['average'] * 100 ) / 5;
			}

			wp_reset_postdata();

			set_transient( $transient_name, $ratings, 7 * 24 * 60 * 60 );
		}
		if ( empty( $ratings['marks_num'] ) ) {
			$ratings['marks_num'] = 0;
		}
		// phpcs:disable
		$label = _n( __( 'Review', 'masterstudy-lms-learning-management-system' ), __( 'Reviews', 'masterstudy-lms-learning-management-system' ), $ratings['marks_num'], 'masterstudy-lms-learning-management-system' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralPlural
		$ratings['total_marks'] = sprintf( // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralPlural
		/* translators: %s: Review  %s: Reviews */
			__(
				'%1$s %2$s.',
				'masterstudy-lms-learning-management-system'
			),
			$ratings['marks_num'],
			$label
		);
		// phpcs:enable

		return $ratings;
	}

	public static function become_instructor( $data, $user_id ) {
		if ( ! empty( $data['become_instructor'] ) && $data['become_instructor'] ) {

			if ( ! empty( $data['fields_type'] ) && 'custom' === $data['fields_type'] ) {
				if ( ! empty( $data['fields'] ) ) {

					$subject = esc_html__( 'New Instructor Application', 'masterstudy-lms-learning-management-system' );
					$user    = STM_LMS_User::get_current_user( $user_id );

					$user_login = $user['login'];
					$email_data = array(
						'user_login' => $user_login,
						'user_id'    => $user_id,
					);

					foreach ( $data['fields'] as $field ) {
						$label = '';
						if ( ! empty( $field['label'] ) ) {
							$label = $field['label'];
						} elseif ( ! empty( $field['slug'] ) ) {
							$label = $field['slug'];
						} elseif ( ! empty( $field['field_name'] ) ) {
							$label = $field['field_name'];
						}
						if ( ! empty( $field['slug'] ) ) {
							$email_data[ $field['slug'] ] = $field['value'];
						}

						$message .= $label . ' - ' . $field['value'] . ';<br>';
					}

					update_user_meta( $user_id, 'become_instructor', $data );
					update_user_meta( $user_id, 'submission_date', time() );
					update_user_meta( $user_id, 'submission_status', 'pending' );

					$instructor_premoderation = STM_LMS_Options::get_option( 'instructor_premoderation', true );

					$message = sprintf(
						/* translators: %s User Login, User ID */
						__( 'User %1$s with id - %2$s, wants to become an Instructor.', 'masterstudy-lms-learning-management-system' ),
						$user_login,
						$user_id
					);

					if ( ! $instructor_premoderation ) {
						$update_user = wp_update_user(
							array(
								'ID'   => $user_id,
								'role' => 'stm_lms_instructor',
							)
						);
						$message     = sprintf(
							/* translators: %s User Login, User ID */
							__( 'User %1$s with id - %2$s, registered as Instructor.', 'masterstudy-lms-learning-management-system' ),
							$user_login,
							$user_id
						);
						update_user_meta( $user_id, 'submission_status', 'approved' );
					}

					STM_LMS_Helpers::send_email(
						'',
						$subject,
						$message,
						'stm_lms_become_instructor_email',
						$email_data
					);

				}
			} else {
				$degree    = ( ! empty( $data['degree'] ) ) ? sanitize_text_field( $data['degree'] ) : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
				$expertize = ( ! empty( $data['expertize'] ) ) ? sanitize_text_field( $data['expertize'] ) : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );

				$subject = esc_html__( 'New Instructor Application', 'masterstudy-lms-learning-management-system' );
				$user    = STM_LMS_User::get_current_user( $user_id );

				update_user_meta( $user_id, 'become_instructor', $data );
				update_user_meta( $user_id, 'submission_date', time() );
				update_user_meta( $user_id, 'submission_status', 'pending' );

				$user_login = $user['login'];

				$instructor_premoderation = STM_LMS_Options::get_option( 'instructor_premoderation', false );

				$message = sprintf(
					/* translators: %s User Login, User ID, Degree, Expertize */
					__( 'User %1$s with id - %2$s, wants to become an Instructor. Degree - %3$s. Expertize - %4$s', 'masterstudy-lms-learning-management-system' ),
					$user_login,
					$user_id,
					$degree,
					$expertize
				);
				if ( ! $instructor_premoderation ) {
					$update_user = wp_update_user(
						array(
							'ID'   => $user_id,
							'role' => 'stm_lms_instructor',
						)
					);
					$message     = sprintf(
						/* translators: %s User Login, User ID, Degree, Expertize */
						__( 'User %1$s with id - %2$s, registered as Instructor. Degree - %3$s. Expertize - %4$s', 'masterstudy-lms-learning-management-system' ),
						$user_login,
						$user_id,
						$degree,
						$expertize
					);
					update_user_meta( $user_id, 'submission_status', 'approved' );
				}
				STM_LMS_Helpers::send_email(
					'',
					$subject,
					$message,
					'stm_lms_become_instructor_email',
					compact( 'user_login', 'user_id', 'degree', 'expertize' )
				);
			}
		}
	}

	public static function update_rating( $user_id, $mark ) {
		$sum_rating_key    = 'sum_rating';
		$total_reviews_key = 'total_reviews';
		$average_key       = 'average_rating';

		$sum_rating    = ( ! empty( get_user_meta( $user_id, $sum_rating_key, true ) ) ) ? get_user_meta( $user_id, $sum_rating_key, true ) : 0;
		$total_reviews = ( ! empty( get_user_meta( $user_id, $total_reviews_key, true ) ) ) ? get_user_meta( $user_id, $total_reviews_key, true ) : 0;
		update_user_meta( $user_id, $sum_rating_key, $sum_rating + $mark );
		update_user_meta( $user_id, $total_reviews_key, $total_reviews + 1 );
		update_user_meta( $user_id, $average_key, round( ( $sum_rating + $mark ) / ( $total_reviews + 1 ), 2 ) );
	}

	public static function get_instructors_url() {
		$page_id = STM_LMS_Options::instructors_page();

		return ( ! empty( $page_id ) ) ? get_permalink( $page_id ) : '';
	}

	public static function instructor_can_add_students() {
		return STM_LMS_Options::get_option( 'instructor_can_add_students', false );
	}

	public static function instructor_add_students_url() {
		return STM_LMS_User::user_page_url() . 'add-students';
	}

	public static function add_student_to_course( $raw_courses, $raw_emails ) {
		$courses = array();
		$emails  = array();

		$data = array(
			'error'   => false,
			'message' => esc_html__( 'Student added to course', 'masterstudy-lms-learning-management-system' ),
		);

		$instructor_id = get_current_user_id();

		foreach ( $raw_emails as $email ) {
			if ( is_email( $email ) ) {
				$emails[] = $email;
			}
		}

		if ( empty( $emails ) ) {
			die;
		}

		foreach ( $raw_courses as $course ) {
			$course = intval( $course );
			if ( STM_LMS_Course::check_course_author( $course, $instructor_id ) ) {
				$courses[] = $course;
			}
		}

		/*Now we checked all courses and emails, we can add users to site*/
		$user_ids = self::create_users_from_emails( $emails );

		foreach ( $courses as $course_id ) {
			foreach ( $user_ids as $user_id ) {
				$user_course = stm_lms_get_user_course( $user_id, $course_id, array() );

				if ( ! empty( $user_course ) ) {
					continue;
				}

				STM_LMS_Course::add_user_course(
					$course_id,
					$user_id,
					STM_LMS_Lesson::get_first_lesson( $course_id ),
					0,
					false,
					false,
					false,
					$instructor_id
				);

				STM_LMS_Course::add_student( $course_id );
			}
		}

		if ( count( $courses ) > 1 || count( $emails ) > 1 ) {
			$courses_n  = _n( 'Course', 'Courses', count( $courses ), 'masterstudy-lms-learning-management-system' );
			$students_n = _n( 'Student', 'Students', count( $emails ), 'masterstudy-lms-learning-management-system' );
			/* translators: %s Students Count, Courses Count */
			$data['message'] = sprintf( esc_html__( '%1$s added to %2$s', 'masterstudy-lms-learning-management-system' ), $students_n, $courses_n );
		}

		return $data;
	}

	public static function add_student_manually() {
		check_ajax_referer( 'stm_lms_add_student_manually', 'nonce' );

		$raw_courses = $_POST['courses'];
		$raw_emails  = $_POST['emails'];

		$data = self::add_student_to_course( $raw_courses, $raw_emails );

		wp_send_json( $data );
	}

	public static function create_users_from_emails( $emails ) {
		$users = array();

		foreach ( $emails as $email ) {
			$user = get_user_by( 'email', $email );

			if ( $user ) {
				$users[] = $user->ID;
				continue;
			}

			/*Create User*/
			$username = sanitize_title( $email );
			$password = wp_generate_password();

			$user_id = wp_create_user( $username, $password, $email );

			$subject = esc_html__( 'You have been registered on the website', 'masterstudy-lms-learning-management-system' );

			$site_url = get_bloginfo( 'url' );
			$message  = sprintf(
				/* translators: %s Username, Password, Site URL */
				esc_html__( 'Login: %1$s Password: %2$s Site URL: %3$s', 'masterstudy-lms-learning-management-system' ),
				$username,
				$password,
				$site_url
			);

			STM_LMS_Helpers::send_email( $email, $subject, $message, 'stm_lms_user_added_via_manage_students', compact( 'username', 'password', 'site_url' ) );

			if ( ! is_wp_error( $user_id ) ) {
				$users[] = $user_id;
			}
		}

		return $users;
	}

	public static function manage_users() {
		add_submenu_page(
			'stm-lms-settings',
			esc_html__( 'Instructor Requests', 'masterstudy-lms-learning-management-system' ),
			esc_html__( 'Instructor Requests', 'masterstudy-lms-learning-management-system' ),
			'manage_options',
			'manage_users',
			'STM_LMS_Instructor::manage_users_template',
			stm_lms_addons_menu_position()
		);
	}

	public static function manage_users_template() {
		require_once STM_LMS_PATH . '/settings/manage_users/main.php';
	}

	public static function get_submissions() {
		check_ajax_referer( 'stm_lms_get_users_submissions', 'nonce' );
		$page        = ! empty( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
		$args        = array(
			'role__in'   => array( 'subscriber', 'stm_lms_instructor' ),
			'paged'      => $page,
			'number'     => 20,
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'meta_query' => array(
				array(
					'key'     => 'submission_date',
					'compare' => 'EXISTS',
				),
			),
		);
		$users       = new WP_User_Query( $args );
		$date_format = 'M j, Y - H:i';

		$r = array(
			'total' => 0,
			'users' => array(),
		);
		if ( ! empty( $users->get_results() ) ) {
			foreach ( $users->get_results() as $user ) {
				$user_id         = $user->ID;
				$submission_data = get_user_meta( $user_id, 'become_instructor', true );

				$status             = get_user_meta( $user_id, 'submission_status', true );
				$submission_date    = get_user_meta( $user_id, 'submission_date', true );
				$banned             = get_user_meta( $user_id, 'stm_lms_user_banned', true );
				$degree             = ( ! empty( $submission_data['degree'] ) ) ? $submission_data['degree'] : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
				$custom_fields      = ! empty( $submission_data['fields'] ) ? $submission_data['fields'] : array();
				$expertize          = ( ! empty( $submission_data['expertize'] ) ) ? $submission_data['expertize'] : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
				$submission_history = get_user_meta( $user_id, 'submission_history', true );
				if ( empty( $submission_history ) || ! is_array( $submission_history ) ) {
					$submission_history = array();
				}
				$user_data    = array(
					'id'                 => $user_id,
					'edit_link'          => get_edit_user_link( $user_id ),
					'display_name'       => $user->display_name,
					'user_email'         => $user->user_email,
					'degree'             => $degree,
					'status'             => $status,
					'expertize'          => $expertize,
					'submission_date'    => gmdate( $date_format, $submission_date ),
					'submission_time'    => $submission_date,
					'submission_history' => $submission_history,
					'message'            => '',
					'banned'             => $banned,
					'custom_fields'      => $custom_fields,
				);
				$r['users'][] = $user_data;
			}
			$r['total'] = $users->get_total();
		}
		wp_send_json( $r );
	}

	public static function update_user_status() {
		check_ajax_referer( 'stm_lms_update_user_status', 'nonce' );
		$r = array();
		if ( ! empty( $_GET['user_id'] ) && ! empty( $_GET['status'] ) ) {
			$user_id       = intval( $_GET['user_id'] );
			$status        = esc_html( $_GET['status'] );
			$admin_message = ! empty( $_GET['message'] ) ? esc_html( $_GET['message'] ) : '';

			$submission_history = get_user_meta( $user_id, 'submission_history', true );
			if ( empty( $submission_history ) || ! is_array( $submission_history ) ) {
				$submission_history = array();
			}
			$user            = get_user_by( 'ID', $user_id );
			$submission_date = get_user_meta( $user_id, 'submission_date', true );
			$user_email      = $user->user_email;
			$user_login      = $user->user_login;
			$submission_data = get_user_meta( $user_id, 'become_instructor', true );
			$degree          = ( ! empty( $submission_data['degree'] ) ) ? $submission_data['degree'] : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
			$expertize       = ( ! empty( $submission_data['expertize'] ) ) ? $submission_data['expertize'] : esc_html__( 'N/A', 'masterstudy-lms-learning-management-system' );
			$custom_fields   = ! empty( $submission_data['fields'] ) ? $submission_data['fields'] : array();
			update_user_meta( $user_id, 'submission_status', sanitize_text_field( $status ) );
			$date_format        = 'M j, Y - H:i';
			$email_data         = array(
				'user_login'    => $user_login,
				'user_id'       => $user_id,
				'admin_message' => $admin_message,
			);
			$additional_message = '';
			foreach ( $custom_fields as $field ) {
				if ( empty( $field['value'] ) ) {
					continue;
				}

				$label = '';
				if ( ! empty( $field['label'] ) ) {
					$label = $field['label'];
				} elseif ( ! empty( $field['slug'] ) ) {
					$label = $field['slug'];
				} elseif ( ! empty( $field['field_name'] ) ) {
					$label = $field['field_name'];
				}
				$email_data[ $label ] = $field['value'];
			}
			if ( 'approved' === $status ) {
				$update_user = wp_update_user(
					array(
						'ID'   => $user_id,
						'role' => 'stm_lms_instructor',
					)
				);
				$message     = esc_html__( 'Your submission has been approved', 'masterstudy-lms-learning-management-system' );
				$subject     = esc_html__( 'Instructor submission', 'masterstudy-lms-learning-management-system' );
				if ( ! empty( $admin_message ) ) {
					$message .= '<br>' . sanitize_text_field( $admin_message );
				}

				STM_LMS_Helpers::send_email(
					$user_email,
					$subject,
					$message,
					'stm_lms_update_user_status_approved',
					$email_data
				);
			} else {
				$message = esc_html__( 'Your submission has been rejected', 'masterstudy-lms-learning-management-system' );
				$subject = esc_html__( 'Instructor submission', 'masterstudy-lms-learning-management-system' );
				if ( ! empty( $admin_message ) ) {
					$message .= '<br>' . sanitize_text_field( $admin_message );
				}
				STM_LMS_Helpers::send_email(
					$user_email,
					$subject,
					$message,
					'stm_lms_update_user_status_rejected',
					$email_data
				);
			}
			$submission_info = array(
				'request_date'         => $submission_date,
				'request_display_date' => gmdate( $date_format, $submission_date ),
				'status'               => $status,
				'message'              => $admin_message,
				'answer_date'          => time(),
				'answer_display_date'  => gmdate( $date_format, time() ),
				'viewed'               => '',
			);
			array_unshift( $submission_history, $submission_info );
			update_user_meta( $user_id, 'submission_history', $submission_history );
			$r = $submission_history;
		}

		wp_send_json( $r );
	}

	public static function ban_user() {
		check_ajax_referer( 'stm_lms_ban_user', 'nonce' );
		if ( ! empty( $_GET['user_id'] ) ) {
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$banned = ! empty( $_GET['banned'] ) && 'true' == $_GET['banned'];
			update_user_meta( intval( $_GET['user_id'] ), 'stm_lms_user_banned', $banned );
		}
		wp_send_json( 'saved' );
	}

	public static function get_instructors( $sort_args ) {
		$args = array(
			'role' => self::role(),
		);
		if ( ! empty( $sort_args ) ) {
			$args = array_merge( $args, $sort_args );
		}
		$instructors_query = new WP_User_Query( $args );
		return $instructors_query->get_results();
	}

	public static function get_course_quantity( $user_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$wpdb->posts` WHERE `post_type`= 'stm-courses' AND `post_author` = %d", $user_id ), ARRAY_A );
		if ( is_array( $result ) ) {
			return count( $result );
		}
		return 0;
	}
}
