<?php

use \MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use \MasterStudy\Lms\Repositories\CurriculumSectionRepository;

STM_LMS_Lesson::init();

class STM_LMS_Lesson {

	public static function init() {
		add_action( 'wp_ajax_stm_lms_complete_lesson', 'STM_LMS_Lesson::complete_lesson' );
		add_action( 'wp_ajax_nopriv_stm_lms_complete_lesson', 'STM_LMS_Lesson::complete_lesson' );
		add_action( 'wp_ajax_stm_lms_total_progress', 'STM_LMS_Lesson::total_progress' );
	}

	public static function get_lesson_url( $post_id, $lesson_id ) {
		if ( empty( $lesson_id ) ) {
			$lesson_id = self::get_first_lesson( $post_id );

			if ( empty( $lesson_id ) ) {
				return get_permalink( $post_id );
			}
		}

		if ( 'publish' === get_post_status( $post_id ) ) {
			$course_url = get_permalink( $post_id );
		} else {
			$course_slug = get_post_field( 'post_name', $post_id );
			$course_url  = home_url( STM_LMS_Options::courses_page_slug() . "/{$course_slug}/" );
		}

		return "{$course_url}{$lesson_id}";
	}

	public static function is_lesson_completed( $user_id, $course_id, $lesson_id ) {
		if ( empty( $user_id ) ) {
			$user = STM_LMS_User::get_current_user();
			if ( empty( $user ) ) {
				return false;
			}
			$user_id = $user['id'];
		}

		if ( get_post_type( $lesson_id ) === 'stm-lessons' || get_post_type( $lesson_id ) === 'stm-google-meets' ) {
			$already_completed = stm_lms_get_user_lesson( $user_id, $course_id, $lesson_id, array( 'lesson_id' ) );
		} elseif ( get_post_type( $lesson_id ) === 'stm-assignments' ) {
			/*If addon is disabled we can skip it*/
			if ( ! class_exists( 'STM_LMS_Assignments' ) ) {
				return true;
			}
			$already_completed = STM_LMS_Assignments::has_passed_assignment( $lesson_id );
		} else {
			$already_completed = stm_lms_check_quiz( $user_id, $lesson_id );
		}

		return count( $already_completed ) > 0;
	}

	public static function complete_lesson() {
		check_ajax_referer( 'stm_lms_complete_lesson', 'nonce' );

		$user = STM_LMS_User::get_current_user();
		if ( empty( $user['id'] ) || empty( $_GET['course'] ) || empty( $_GET['lesson'] ) ) {
			die;
		}

		$user_id   = $user['id'];
		$course_id = intval( $_GET['course'] );
		$lesson_id = intval( $_GET['lesson'] );

		/*Check if already passed*/
		if ( self::is_lesson_completed( $user_id, $course_id, $lesson_id ) ) {
			wp_send_json( compact( 'user_id', 'course_id', 'lesson_id' ) );
			die;
		};

		/*Check if lesson in course*/
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );

		if ( empty( $course_materials ) || ! in_array( $lesson_id, $course_materials, true ) ) {
			die;
		}

		$end_time   = time();
		$start_time = get_user_meta( $user_id, "stm_lms_course_started_{$course_id}_{$lesson_id}", true );
		stm_lms_add_user_lesson( compact( 'user_id', 'course_id', 'lesson_id', 'start_time', 'end_time' ) );
		STM_LMS_Course::update_course_progress( $user_id, $course_id );

		do_action( 'stm_lms_lesson_passed', $user_id, $lesson_id, $course_id );

		delete_user_meta( $user_id, "stm_lms_course_started_{$course_id}_{$lesson_id}" );

		wp_send_json( compact( 'user_id', 'course_id', 'lesson_id' ) );
	}

	public static function lesson_has_preview( $lesson_id ) {
		return ! empty( get_post_meta( $lesson_id, 'preview', true ) );
	}

	public static function is_previewed( $course_id, $lesson_id ) {
		return ( self::lesson_has_preview( $lesson_id ) && ! STM_LMS_User::has_course_access( $course_id ) );
	}

	public static function get_lesson_info( $course_id, $lesson_id ) {
		$material = ( new CurriculumMaterialRepository() )->find_by_course_lesson( $course_id, $lesson_id );
		$response = array(
			'section' => esc_html__( 'Section 1', 'masterstudy-lms-learning-management-system' ),
			'lesson'  => '',
		);

		if ( empty( $material ) ) {
			return $response;
		}

		$section             = ( new CurriculumSectionRepository() )->find( $material->section_id );
		$response['section'] = $section->title;

		switch ( $material->post_type ) {
			case 'stm-lessons':
			case 'stm-google-meets':
				$response['type']   = 'lesson';
				$response['lesson'] = sprintf( esc_html__( 'Lecture %s', 'masterstudy-lms-learning-management-system' ), $material->order );
				break;
			case 'stm-assignments':
				$response['type']   = 'assignment';
				$response['lesson'] = sprintf( esc_html__( 'Assignment %s', 'masterstudy-lms-learning-management-system' ), $material->order );
				break;
			default:
				$response['type']   = 'quiz';
				$response['lesson'] = sprintf( esc_html__( 'Quiz %s', 'masterstudy-lms-learning-management-system' ), $material->order );
				break;
		}

		return $response;
	}

	public static function aio_front_scripts() {
		$js_path = UAVC_URL . 'assets/min-js/';
		$ext     = '.min';

		$ultimate_smooth_scroll_compatible = get_option( 'ultimate_smooth_scroll_compatible' );

		// register js
		wp_register_script(
			'ultimate-script',
			UAVC_URL . 'assets/min-js/ultimate.min.js',
			array(
				'jquery',
				'jquery-ui-core',
			),
			ULTIMATE_VERSION,
			true
		);
		wp_register_script( 'ultimate-appear', $js_path . 'jquery-appear' . $ext . '.js', array( 'jquery' ), ULTIMATE_VERSION, true );
		wp_register_script( 'ultimate-custom', $js_path . 'custom' . $ext . '.js', array( 'jquery' ), ULTIMATE_VERSION, true );
		wp_register_script( 'ultimate-vc-params', $js_path . 'ultimate-params' . $ext . '.js', array( 'jquery' ), ULTIMATE_VERSION, true );
		if ( 'enable' === $ultimate_smooth_scroll_compatible ) {
			$smoothScroll = 'SmoothScroll-compatible.min.js';
		} else {
			$smoothScroll = 'SmoothScroll.min.js';
		}
		wp_register_script( 'ultimate-smooth-scroll', UAVC_URL . 'assets/min-js/' . $smoothScroll, array( 'jquery' ), ULTIMATE_VERSION, true );
		wp_register_script( 'ultimate-modernizr', $js_path . 'modernizr-custom' . $ext . '.js', array( 'jquery' ), ULTIMATE_VERSION, true );
		wp_register_script( 'ultimate-tooltip', $js_path . 'tooltip' . $ext . '.js', array( 'jquery' ), ULTIMATE_VERSION, true );

		// register css

		if ( is_rtl() ) {
			$cssext = '-rtl';
		} else {
			$cssext = '';
		}

		Ultimate_VC_Addons::ultimate_register_style( 'ultimate-animate', 'animate' );
		Ultimate_VC_Addons::ultimate_register_style( 'ult_hotspot_rtl_css', UAVC_URL . 'assets/min-css/rtl-common' . $ext . '.css', true );
		Ultimate_VC_Addons::ultimate_register_style( 'ultimate-style', 'style' );
		Ultimate_VC_Addons::ultimate_register_style( 'ultimate-style-min', UAVC_URL . 'assets/min-css/ultimate.min' . $cssext . '.css', true );
		Ultimate_VC_Addons::ultimate_register_style( 'ultimate-tooltip', 'tooltip' );

		$ultimate_smooth_scroll = get_option( 'ultimate_smooth_scroll' );
		if ( 'enable' === $ultimate_smooth_scroll || 'enable' === $ultimate_smooth_scroll_compatible ) {
			$ultimate_smooth_scroll_options = get_option( 'ultimate_smooth_scroll_options' );
			$options                        = array(
				'step'  => ( isset( $ultimate_smooth_scroll_options['step'] ) && '' !== $ultimate_smooth_scroll_options['step'] ) ? (int) $ultimate_smooth_scroll_options['step'] : 80,
				'speed' => ( isset( $ultimate_smooth_scroll_options['speed'] ) && '' !== $ultimate_smooth_scroll_options['speed'] ) ? (int) $ultimate_smooth_scroll_options['speed'] : 480,
			);
			wp_enqueue_script( 'ultimate-smooth-scroll' );
			if ( 'enable' === $ultimate_smooth_scroll ) {
				wp_localize_script( 'ultimate-smooth-scroll', 'php_vars', $options );
			}
		}

		if ( function_exists( 'vc_is_editor' ) ) {
			if ( vc_is_editor() ) {
				wp_enqueue_style( 'vc-fronteditor', UAVC_URL . 'assets/min-css/vc-fronteditor.min.css', array(), ULTIMATE_VERSION );
			}
		}
		$fonts = get_option( 'smile_fonts' );
		if ( is_array( $fonts ) ) {
			foreach ( $fonts as $font => $info ) {
				$style_url = $info['style'] ?? '';
				if ( strpos( $style_url, 'http://' ) !== false ) {
					wp_enqueue_style( 'bsf-' . $font, $info['style'], array(), ULTIMATE_VERSION );
				}
			}
		}

		wp_enqueue_script( 'ultimate-modernizr' );
		wp_enqueue_script( 'jquery_ui' );
		wp_enqueue_script( 'masonry' );
		if ( defined( 'DISABLE_ULTIMATE_GOOGLE_MAP_API' ) && ( true === DISABLE_ULTIMATE_GOOGLE_MAP_API || 'true' === DISABLE_ULTIMATE_GOOGLE_MAP_API ) ) {
			$load_map_api = false;
		} else {
			$load_map_api = true;
		}
		if ( $load_map_api ) {
			wp_enqueue_script( 'googleapis' );
		}
		/* Range Slider Dependecy */
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'ult_range_tick' );
		/* Range Slider Dependecy */
		wp_enqueue_script( 'ultimate-script' );
		wp_enqueue_script( 'ultimate-modal-all' );
		wp_enqueue_script( 'jquery.shake', $js_path . 'jparallax' . $ext . '.js', array(), ULTIMATE_VERSION, true );
		wp_enqueue_script( 'jquery.vhparallax', $js_path . 'vhparallax' . $ext . '.js', array(), ULTIMATE_VERSION, true );

		wp_enqueue_style( 'ultimate-style-min' );
		wp_enqueue_style( 'ult-icons' );
		wp_enqueue_style( 'ultimate-vidcons', UAVC_URL . 'assets/fonts/vidcons.css', array(), ULTIMATE_VERSION );
		wp_enqueue_script( 'jquery.ytplayer', $js_path . 'mb-YTPlayer' . $ext . '.js', array(), ULTIMATE_VERSION, true );

		$ultimate_google_font_manager = new Ultimate_VC_Addons_Google_Font_Manager();
		$ultimate_google_font_manager->enqueue_selected_ultimate_google_fonts();
	}

	public static function get_first_lesson( $course_id ) {
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );

		return ! empty( $course_materials ) ? reset( $course_materials ) : 0;
	}

	public static function get_last_lesson( $post_id ) {
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $post_id );

		return ! empty( $course_materials ) ? end( $course_materials ) : 0;
	}

	public static function total_progress() {
		check_ajax_referer( 'stm_lms_total_progress', 'nonce' );

		wp_send_json(
			self::get_total_progress( get_current_user_id(), intval( $_GET['course_id'] ?? 0 ) )
		);
	}

	public static function get_total_progress( $user_id, $course_id ) {
		if ( empty( $user_id ) ) {
			return null;
		}

		$data = array(
			'course'           => STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user_id, $course_id ) ),
			'curriculum'       => array(),
			'course_completed' => false,
		);

		if ( ( ! empty( $data['course']['progress_percent'] ) ) && $data['course']['progress_percent'] > 100 ) {
			$data['course']['progress_percent'] = 100;
		}

		/*Curriculum*/
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );
		$curriculum_data  = array();

		foreach ( $course_materials as $item_id ) {
			$type = get_post_meta( $item_id, 'type', true );
			if ( empty( $type ) ) {
				$type = 'text';
			}
			$lesson              = self::get_lesson_info( $course_id, $item_id );
			$lesson['completed'] = self::is_lesson_completed( $user_id, $course_id, $item_id );
			if ( 'lesson' === $lesson['type'] ) {
				$lesson_type = get_post_meta( $item_id, 'type', true );
				if ( empty( $lesson_type ) ) {
					$lesson_type = 'text';
				}
				$lesson['lesson_type'] = $lesson_type;
			}
			$curriculum_data[] = $lesson;
		}

		foreach ( $curriculum_data as $item_data ) {
			$type = ( 'lesson' === $item_data['type'] && 'text' !== $item_data['lesson_type'] )
				? 'multimedia'
				: $item_data['type'];
			if ( empty( $data['curriculum'][ $type ] ) ) {
				$data['curriculum'][ $type ] = array(
					'total'     => 0,
					'completed' => 0,
				);
			}

			$data['curriculum'][ $type ]['total']++;

			if ( $item_data['completed'] ) {
				$data['curriculum'][ $type ]['completed']++;
			}
		}

		$data['title'] = get_the_title( $course_id );
		$data['url']   = get_permalink( $course_id );

		if ( empty( $data['course'] ) ) {
			$data['course'] = array(
				'progress_percent' => 0,
			);

			return $data;
		}

		/*Completed label*/
		$threshold                = STM_LMS_Options::get_option( 'certificate_threshold', 70 );
		$data['course_completed'] = intval( $threshold ) <= intval( $data['course']['progress_percent'] );
		$data['certificate_url']  = STM_LMS_Course::certificates_page_url( $course_id );

		return $data;
	}
}
