<?php
use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;

STM_LMS_Course::init();

class STM_LMS_Course {
	public static function init() {
		add_action( 'rest_api_init', 'STM_LMS_Course::courses_search_endpoint' );

		add_action( 'stm_lms_archive_card_price', 'STM_LMS_Course::archive_card_price' );

		add_action( 'stm_lms_lesson_started', 'STM_LMS_Course::lesson_started', 10, 3 );

		add_filter( 'stm_lms_global/price', 'STM_LMS_Course::global_price', 10, 2 );

		add_action( 'stm_lms_courses_have_posts', 'STM_LMS_Course::save_search', 10, 2 );

		add_filter( 'stm_lms_course_tabs', 'STM_LMS_Course::course_tabs', 10, 2 );

		add_action( 'stm_lms_before_item_template_start', 'STM_LMS_Course::check_course_item', 10, 2 );
	}

	public static function global_price( $content, $vars ) {
		if ( ! empty( $vars['post_id'] ) ) {
			$course_id = $vars['post_id'];
		}
		if ( ! empty( $vars['id'] ) ) {
			$course_id = $vars['id'];
		}

		if ( ! empty( $course_id ) ) {
			$not_salebale = get_post_meta( $course_id, 'not_single_sale', true );
			if ( $not_salebale ) {
				ob_start();
				$subscription_image = STM_LMS_URL . '/assets/img/members_only.svg';
				?>
				<div class="course_available_only_in_subscription">
					<div class="course_available_only_in_subscription__image">
						<img src="<?php echo esc_url( $subscription_image ); ?>" alt="<?php esc_attr( '' ); ?>"/>
					</div>
					<div class="course_available_only_in_subscription__title">
						<?php esc_html_e( 'Members only', 'masterstudy-lms-learning-management-system' ); ?>
					</div>
				</div>
				<?php
				$content = ob_get_clean();
			}
		}

		return $content;
	}

	public static function archive_card_price( $args ) {
		STM_LMS_Templates::show_lms_template( 'global/price', $args );
	}

	public static function courses_in_search( $query ) {
		if ( $query->is_search && $query->is_main_query() ) {
			$query->set( 'post_type', array( 'post', 'page', 'stm-courses' ) );
		}

		return $query;
	}

	public static function courses_search_endpoint() {
		register_rest_route(
			'stm-lms/v1',
			'/courses',
			array(
				'callback'            => 'STM_LMS_Course::courses_search',
				'permission_callback' => '__return_true',
			)
		);
	}

	public static function courses_search() {
		$results = array();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$search = sanitize_text_field( $_GET['search'] ?? '' );

		$args = array(
			'post_type'      => apply_filters( 'stm_lms_courses_search_endpoint_post_types', 'stm-courses' ),
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			's'              => $search,
		);

		$q = new WP_Query( $args );
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$results[] = array(
					'value' => html_entity_decode( get_the_title() ),
					'url'   => get_the_permalink(),
				);
			}
		}

		return $results;
	}

	public static function add_user_course( $course_id, $user_id, $current_lesson_id, $progress = 0, $is_translate = false, $enterprise = '', $bundle = '', $instructor_id = '', $for_points = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			die;
		}

		$user_course = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user_id, $course_id, array(), $enterprise ) );

		if ( empty( $user_course ) ) {
			$course                     = compact( 'user_id', 'course_id', 'current_lesson_id' );
			$course['status']           = 'enrolled';
			$course['progress_percent'] = $progress;
			$course['start_time']       = time();

			if ( function_exists( 'wpml_get_language_information' ) ) {
				$post_language_information = wpml_get_language_information( null, $course_id );
				$course['lng_code']        = $post_language_information['locale'];
			} else {
				$course['lng_code'] = get_locale();
			}

			$course['enterprise_id'] = $enterprise;
			$course['bundle_id']     = $bundle;
			$course['instructor_id'] = $instructor_id;
			$course['for_points']    = $for_points;

			stm_lms_add_user_course( $course );

			if ( ! $is_translate ) {
				self::add_wpmls_binded_courses( $course_id, $user_id, $current_lesson_id, $progress );
			}

			/*User was Added course*/
			do_action( 'add_user_course', $user_id, $course_id );
		} else {
			stm_lms_update_start_time_in_user_course( $user_id, $course_id );
		}
	}

	public static function get_course_expiration_days( $course_id ) {
		$end_time   = get_post_meta( $course_id, 'end_time', true );
		$expiration = get_post_meta( $course_id, 'expiration_course', true );

		return $expiration ? $end_time : false;
	}

	public static function get_course_duration_time( $course_id ) {
		$expiration_days = self::get_course_expiration_days( $course_id );

		if ( empty( $expiration_days ) ) {
			return 0;
		}

		return intval( $expiration_days ) * DAY_IN_SECONDS;
	}

	public static function get_user_course( $user_id, $course_id ) {
		return STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user_id, $course_id ) );
	}

	public static function is_course_time_expired( $user_id, $course_id ) {
		$course_duration = self::get_course_duration_time( $course_id );
		$user_course     = self::get_user_course( $user_id, $course_id );

		if ( empty( $course_duration ) || empty( $user_course ) ) {
			return false;
		} elseif ( time() > intval( $user_course['start_time'] ) + $course_duration ) {
			return true;
		}

		return false;
	}

	public static function add_wpmls_binded_courses( $course_id, $user_id, $current_lesson_id, $progress ) {
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;
			$trid         = $sitepress->get_element_trid( $course_id );
			$translations = $sitepress->get_element_translations( $trid );
			if ( ! empty( $translations ) ) {
				foreach ( $translations as $translation ) {
					self::add_user_course( $translation->element_id, $user_id, $current_lesson_id, $progress, true );
				}
			}
		}
	}

	public static function update_course_progress( $user_id, $course_id, $reset = false ) {
		$last_progress_time = get_user_meta( $user_id, 'last_progress_time', true );

		if ( empty( $last_progress_time ) ) {
			$last_progress_time = array();
		}
		$last_progress_time[ $course_id ] = time();

		update_user_meta( $user_id, 'last_progress_time', $last_progress_time );

		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( intval( $course_id ) );

		STM_LMS_Helpers::transform_to_wpml_curriculum( $course_materials );

		$total_items    = count( $course_materials );
		$passed_items   = array();
		$passed_quizzes = array_unique( STM_LMS_Helpers::simplify_meta_array( stm_lms_get_user_course_quizzes( $user_id, null, array( 'quiz_id' ), 'passed' ), 'quiz_id' ) );
		$passed_lessons = STM_LMS_Helpers::simplify_meta_array( stm_lms_get_user_course_lessons( $user_id, $course_id, array( 'lesson_id' ) ) );

		foreach ( $passed_lessons as $lesson_id ) {
			// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( in_array( $lesson_id, $course_materials ) ) {
				$passed_items[] = $lesson_id;
			}
		}
		foreach ( $passed_quizzes as $quiz_id ) {
			$quiz_data = STM_LMS_Helpers::simplify_meta_array( stm_lms_get_user_last_quiz( $user_id, $quiz_id, array( 'status' ) ), 'status' );
			if ( ! empty( $quiz_data[0] ) && 'failed' === $quiz_data[0] ) {
				continue;
			}
			// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( in_array( $quiz_id, $course_materials ) ) {
				$passed_items[] = $quiz_id;
			}
		}

		$passed_items = count( array_unique( $passed_items ) );

		$passed_items = apply_filters( 'stm_lms_course_passed_items', $passed_items, $course_materials, $user_id );

		$progress = 0;
		if ( $passed_items > 0 && $total_items > 0 ) {
			$progress = ( 100 * $passed_items ) / $total_items;
		}

		$user_course = stm_lms_get_user_course( $user_id, $course_id, array( 'user_course_id' ) );

		/*
		TODO
		We even add course to user from drip content
		Need some check to this
		Add course if not exist
		if ( empty( $user_course ) ) {
			// STM_LMS_Course::add_user_course($course_id, $user_id, 0, $progress);
			// $user_course = stm_lms_get_user_course($user_id, $course_id, array('user_course_id'));
		}
		*/

		$user_course    = STM_LMS_Helpers::simplify_db_array( $user_course );
		$user_course_id = empty( $user_course['user_course_id'] ) ? 0 : $user_course['user_course_id'];

		do_action( 'stm_lms_progress_updated', $course_id, $user_id, $progress );

		stm_lms_update_user_course_progress( $user_course_id, $progress, $reset );

		if ( 100 === $progress ) {
			stm_lms_update_user_course_endtime( $user_course['user_course_id'], time() );
		}
	}

	public static function courses_page_url() {
		$settings = get_option( 'stm_lms_settings', array() );

		return ( empty( $settings['courses_page'] ) ) ? home_url( '/' ) : get_the_permalink( $settings['courses_page'] );
	}

	public static function certificates_page_url( $course_id = '' ) {
		$pages_config = STM_LMS_Page_Router::pages_config();

		$base_path = STM_LMS_User::login_page_url() . $pages_config['user_url']['sub_pages']['certificate_url']['url'];

		return $base_path . '/' . $course_id;
	}

	public static function add_student( $course_id ) {
		$current_students = get_post_meta( $course_id, 'current_students', true );
		if ( empty( $current_students ) ) {
			$current_students = 0;
		}
		++$current_students;
		update_post_meta( $course_id, 'current_students', $current_students );
	}

	public static function remove_student( $course_id ) {
		$current_students = get_post_meta( $course_id, 'current_students', true );
		if ( empty( $current_students ) ) {
			$current_students = 0;
		}
		if ( $current_students > 0 ) {
			--$current_students;
		}
		update_post_meta( $course_id, 'current_students', $current_students );
	}

	public static function item_url( $course_id, $item_id ) {
		if ( empty( $item_id ) ) {
			$item_id = STM_LMS_Lesson::get_first_lesson( $course_id );
		}
		return esc_url( get_the_permalink( $course_id ) . stm_lms_get_wpml_binded_id( $item_id ) );
	}

	public static function curriculum_info( $course_id ) {
		$section_ids = ( new CurriculumSectionRepository() )->get_course_section_ids( $course_id );
		$materials   = new CurriculumMaterialRepository();

		return array(
			'sections'    => count( $section_ids ),
			'lessons'     => $materials->count_by_type( $section_ids, 'stm-lessons' ),
			'quizzes'     => $materials->count_by_type( $section_ids, 'stm-quizzes' ),
			'assignments' => $materials->count_by_type( $section_ids, 'stm-assignments' ),
		);
	}

	public static function course_average_rate( $reviews ) {
		$r = array(
			'average' => 0,
			'percent' => 0,
		);
		if ( empty( $reviews ) ) {
			return $r;
		}
		$r['average'] = round( array_sum( $reviews ) / count( $reviews ), 1 );
		$r['percent'] = $r['average'] * 100 / 5;
		return $r;
	}

	public static function course_views( $post_id ) {
		if ( get_post_type() === 'stm-courses' ) {
			$views = self::get_course_views( $post_id );

			$cookie_name = 'stm_lms_courses_watched';

			if ( empty( $_COOKIE[ $cookie_name ] ) ) {
				++$views;
				$posts = array( $post_id );
			} else {
				$posts = explode( ',', $_COOKIE[ $cookie_name ] );
				// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				if ( ! in_array( $post_id, $posts ) ) {
					++$views;
					$posts[] = $post_id;
				}
			}

			setcookie( $cookie_name, implode( ',', $posts ), time() + ( 86400 * 30 * 30 ), '/' );
			update_post_meta( $post_id, 'views', $views );
		}
	}

	public static function get_course_views( $course_id ) {
		$views = get_post_meta( $course_id, 'views', true );

		return ( empty( $views ) ) ? 0 : intval( $views );
	}

	public static function get_all_statuses() {
		return array(
			'hot'     => esc_html__( 'Hot', 'masterstudy-lms-learning-management-system' ),
			'new'     => esc_html__( 'New', 'masterstudy-lms-learning-management-system' ),
			'special' => esc_html__( 'Special', 'masterstudy-lms-learning-management-system' ),
		);
	}

	public static function status_label( $status ) {
		$labels = self::get_all_statuses();

		return ( ! empty( $labels[ $status ] ) ) ? $labels[ $status ] : '';
	}

	public static function get_post_status( $course_id ) {
		$post_status = get_post_meta( $course_id, 'status', true );

		if ( empty( $post_status ) ) {
			return '';
		}

		$post_status_label  = self::status_label( $post_status );
		$status_dates_start = get_post_meta( $course_id, 'status_dates_start', true );
		$status_dates_end   = get_post_meta( $course_id, 'status_dates_end', true );

		if ( ! empty( $status_dates_end ) ) {
			$status_dates_start = intval( $status_dates_start );
		}
		if ( ! empty( $status_dates_end ) ) {
			$status_dates_end = intval( $status_dates_end );
		}

		if ( empty( $status_dates_start ) && empty( $status_dates_end ) ) {
			return array(
				'status' => $post_status,
				'label'  => $post_status_label,
			);
		}

		if ( ! empty( intval( $status_dates_start ) ) && ! empty( intval( $status_dates_end ) ) ) {
			$current_time = time() * 1000;
			if ( $current_time > $status_dates_start && $current_time < $status_dates_end ) {
				return array(
					'status' => $post_status,
					'label'  => $post_status_label,
				);
			}
		}

		return '';
	}

	public static function get_course_price( $course_id ) {
		$price      = get_post_meta( $course_id, 'price', true );
		$sale_price = self::get_sale_price( $course_id );

		/*If we have both prices*/
		if ( ! empty( $price ) && ! empty( $sale_price ) ) {
			$price = $sale_price;
		}

		/*If we have only sale price*/
		if ( empty( $price ) && ! empty( $sale_price ) ) {
			$price = $sale_price;
		}

		/*If no prices*/
		if ( empty( $price ) && empty( $sale_price ) ) {
			$price = 0;
		}

		return apply_filters( 'stm_lms_course_price', $price );
	}

	public static function get_sale_price( $post_id ) {
		return apply_filters( 'stm_lms_get_sale_price', get_post_meta( $post_id, 'sale_price', true ), $post_id );
	}

	public static function lesson_started( $post_id, $course_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user = STM_LMS_User::get_current_user();
			if ( empty( $user['id'] ) ) {
				die;
			}

			$user_id = $user['id'];
		}

		$option_name = "stm_lms_course_started_{$post_id}_{$course_id}";

		$started = get_user_meta( $user_id, $option_name, true );

		if ( empty( $started ) ) {
			update_user_meta( $user_id, $option_name, time() );
		}
	}

	public static function course_in_plan( $course_id ) {
		$r      = array();
		$terms  = stm_lms_get_terms_array( $course_id, 'stm_lms_course_taxonomy', 'term_id', false );
		$levels = pmpro_getAllLevels( true, true );

		$simple_levels = array();

		foreach ( $levels as $level ) {
			$level_id = $level->id;

			$level_category = STM_LMS_Subscriptions::get_plan_private_category( $level_id );

			/*Collect simple levels*/
			if ( empty( $level_category ) ) {
				$simple_levels[] = $level;
			}

			/*Category levels*/
			if ( is_array( $level_category ) ) {
				foreach ( $level_category as $cat ) {
					// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					if ( in_array( $cat, $terms ) ) {
						$r[] = $level;
					}
				}
			} else {
				// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				if ( in_array( $level_category, $terms ) ) {
					$r[] = $level;
				}
			}
		}

		/*If we have no plans - so course is in simple plans*/
		if ( empty( $r ) ) {
			$r = $simple_levels;
		}

		return $r;
	}

	public static function save_search( $args, $q ) {
		if ( ! empty( $args['s'] ) ) {
			$user_id = get_current_user_id();
			if ( empty( $user_id ) ) {
				$user_id = 0;
			}

			stm_lms_add_user_search( $user_id, $args['s'] );
		}
	}

	public static function course_tabs( $tabs, $id ) {
		$curriculum   = ( new CurriculumSectionRepository() )->get_course_sections( $id, true );
		$announcement = get_post_meta( $id, 'announcement', true );
		$faq          = get_post_meta( get_the_ID(), 'faq', true );

		if ( empty( $curriculum ) && ! empty( $tabs['curriculum'] ) ) {
			$is_udemy_course = apply_filters( 'stm_lms_is_udemy_course', get_post_meta( $id, 'udemy_course_id', true ) );
			if ( ! $is_udemy_course || empty( get_post_meta( $id, 'udemy_curriculum', true ) ) ) {
				unset( $tabs['curriculum'] );
			}
		}

		if ( ( empty( $faq ) || 0 === count( json_decode( $faq ) ) ) && ! empty( $tabs['faq'] ) ) {
			unset( $tabs['faq'] );
		}

		if ( empty( $announcement ) && ! empty( $tabs['announcement'] ) ) {
			unset( $tabs['announcement'] );
		}

		if ( class_exists( 'STM_LMS_Scorm_Packages' ) && STM_LMS_Scorm_Packages::is_scorm_course( $id ) ) {
			unset( $tabs['curriculum'] );
		}

		foreach ( $tabs as $tab_key => $tab_name ) {
			if ( ! STM_LMS_Options::get_option( "course_tab_{$tab_key}", 'on' ) ) {
				unset( $tabs[ $tab_key ] );
			}
		}

		return $tabs;
	}

	public static function check_course_author( $course_id, $user_id ) {
		if ( empty( $user_id ) ) {
			return false;
		}

		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		/*Check author*/
		if ( ! empty( $course_id ) ) {
			$authors   = array();
			$authors[] = intval( get_post_field( 'post_author', $course_id ) );
			$authors[] = intval( get_post_meta( $course_id, 'co_instructor', true ) );

			// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( ! in_array( $user_id, $authors ) ) {
				return false;
			}

			return true;
		}

		return false;
	}

	public static function check_course_item( $course_id, $item_id ) {
		$course_id = intval( apply_filters( 'wpml_object_id', $course_id, 'stm-courses' ) ?? $course_id );
		$materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );
		$is_scorm  = ( '0' == $item_id ); // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

		if ( empty( $materials ) && ! $is_scorm ) {
			STM_LMS_User::js_redirect( get_permalink( $course_id ) );
		}

		if ( ! in_array( intval( $item_id ), $materials, true ) && ! $is_scorm ) {
			STM_LMS_User::js_redirect( STM_LMS_Lesson::get_lesson_url( $course_id, ( STM_LMS_Lesson::get_first_lesson( $course_id ) ) ) );
		}
	}
}
