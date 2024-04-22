<?php

require_once STM_LMS_PRO_ADDONS . '/course_bundle/settings.php';
require_once STM_LMS_PRO_ADDONS . '/course_bundle/my-bundles.php';
require_once STM_LMS_PRO_ADDONS . '/course_bundle/my-bundle.php';
require_once STM_LMS_PRO_ADDONS . '/course_bundle/cart.php';
require_once STM_LMS_PRO_ADDONS . '/course_bundle/woocommerce.php';
require_once STM_LMS_PRO_ADDONS . '/course_bundle/vc_module.php';

new STM_LMS_Course_Bundle();

class STM_LMS_Course_Bundle {


	public function __construct() {
		add_filter(
			'stm_lms_menu_items',
			function ( $menus ) {
				if ( STM_LMS_Instructor::is_instructor() ) {
					$menus[] = array(
						'order'        => 50,
						'id'           => 'bundles',
						'slug'         => 'bundles',
						'lms_template' => 'stm-lms-user-bundles',
						'menu_title'   => esc_html__( 'Bundles', 'masterstudy-lms-learning-management-system-pro' ),
						'menu_icon'    => 'fa-layer-group',
						'menu_url'     => ms_plugin_user_account_url( 'bundles' ),
						'menu_place'   => 'main',
					);
				}

				return $menus;
			}
		);
		add_filter( 'stm_lms_post_types_array', array( $this, 'assignment_post_type' ), 10, 1 );
		add_filter( 'stm_lms_post_types', array( $this, 'bundles_stm_lms_post_types' ), 5, 1 );

		add_action( 'stm_lms_after_wishlist_list', array( $this, 'wishlist_list' ), 10, 1 );

		add_shortcode( 'stm_lms_course_bundles', array( $this, 'add_shortcode' ) );
	}

	/*FILTERS*/
	public function assignment_post_type( $posts ) {
		$posts['stm-course-bundles'] = array(
			'single' => esc_html__( 'Course Bundles', 'masterstudy-lms-learning-management-system-pro' ),
			'plural' => esc_html__( 'Course Bundles', 'masterstudy-lms-learning-management-system-pro' ),
			'args'   => array(
				'public'              => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'show_in_menu'        => false,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'author' ),
			),
		);

		return $posts;
	}

	public function add_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'          => '',
				'columns'        => '',
				'posts_per_page' => '',
				'select_bundles' => '',
			),
			$atts
		);

		return STM_LMS_Templates::load_lms_template( 'shortcodes/stm_lms_course_bundles', $atts );
	}

	public function bundles_stm_lms_post_types( $post_types ) {
		$post_types[] = 'stm-course-bundles';

		return $post_types;
	}


	/*ACTIONS*/
	public function wishlist_list( $wishlist ) {
		$columns = 3;
		$title   = esc_html__( 'Bundles', 'masterstudy-lms-learning-management-system-pro' );
		$args    = "author=''";
		if ( ! empty( $wishlist ) ) {
			STM_LMS_Templates::show_lms_template(
				'bundles/card/php/list',
				compact( 'wishlist', 'columns', 'title', 'args' )
			);
		}
	}

	public static function get_bundle_courses_price( $bundle_id ) {
		$price   = 0;
		$courses = get_post_meta( $bundle_id, STM_LMS_My_Bundle::bundle_courses_key(), true );

		if ( empty( $courses ) ) {
			return $price;
		}

		foreach ( $courses as $course_id ) {
			$price += STM_LMS_Course::get_course_price( $course_id );
		}

		return $price;
	}

	public static function get_bundle_price( $bundle_id ) {
		return get_post_meta( $bundle_id, STM_LMS_My_Bundle::bundle_price_key(), true );
	}

	public static function get_bundle_rating( $bundle_id ) {
		$r = array(
			'count'   => 0,
			'average' => 0,
			'percent' => 0,
		);

		$courses = get_post_meta( $bundle_id, STM_LMS_My_Bundle::bundle_courses_key(), true );

		if ( empty( $courses ) ) {
			return $r;
		}

		foreach ( $courses as $course_id ) {
			$reviews = get_post_meta( $course_id, 'course_marks', true );
			if ( ! empty( $reviews ) ) {
				$rates = STM_LMS_Course::course_average_rate( $reviews );
				$r['count'] ++;
				$r['average'] += $rates['average'];
				$r['percent'] += $rates['percent'];
			}
		}

		return $r;
	}

}
