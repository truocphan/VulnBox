<?php

new STM_LMS_Multi_Instructors();

class STM_LMS_Multi_Instructors {

	public function __construct() {
		add_action( 'stm_lms_manage_course_after_teacher', array( $this, 'add_instructor' ) );

		add_action( 'stm_lms_pro_course_added', array( $this, 'add_co_instructor' ), 10, 2 );

		add_action( 'stm_lms_after_teacher_end', array( $this, 'front_co_instructor' ) );

		add_action( 'stm_lms_instructor_courses_end', array( $this, 'co_courses' ) );

		add_action( 'wp_ajax_stm_lms_get_co_courses', array( $this, 'get_co_courses' ) );
	}

	public static function add_instructor() {
		STM_LMS_Templates::show_lms_template( 'multi_instructor/add_teacher' );
	}

	public static function add_co_instructor( $data, $course_id ) {

		if ( ! empty( $data['co_instructor'] ) && STM_LMS_Instructor::is_instructor( $data['co_instructor'] ) ) {
			update_post_meta( $course_id, 'co_instructor', $data['co_instructor'] );
		}

	}

	public static function front_co_instructor() {
		STM_LMS_Templates::show_lms_template( 'multi_instructor/front/main' );
	}

	public function co_courses() {
		STM_LMS_Templates::show_lms_template( 'multi_instructor/co_courses/main' );
	}


	/*Co Courses*/
	public static function per_page() {
		return 6;
	}

	public static function getCoCourses( $user_id = '', $return_args = false ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		$r        = array( 'posts' => array() );
		$per_page = self::per_page();

		$page   = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$offset = ( ! empty( $page ) ) ? ( $page * $per_page ) - $per_page : 0;

		$args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => $per_page,
			'post_status'    => array( 'any' ),
			'meta_query'     => array(
				array(
					'key'     => 'co_instructor',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		if ( ! empty( $offset ) ) {
			$args['offset'] = $offset;
		}

		if ( $return_args ) {
			return $args;
		}

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$id = get_the_ID();

				$rating  = get_post_meta( $id, 'course_marks', true );
				$rates   = STM_LMS_Course::course_average_rate( $rating );
				$average = $rates['average'];
				$percent = $rates['percent'];

				$status = get_post_status( $id );

				$price      = get_post_meta( $id, 'price', true );
				$sale_price = STM_LMS_Course::get_sale_price( $id );

				if ( empty( $price ) && ! empty( $sale_price ) ) {
					$price      = $sale_price;
					$sale_price = '';
				}

				switch ( $status ) {
					case 'publish':
						$status_label = esc_html__( 'Published', 'masterstudy-lms-learning-management-system-pro' );
						break;
					case 'pending':
						$status_label = esc_html__( 'Pending', 'masterstudy-lms-learning-management-system-pro' );
						break;
					default:
						$status_label = esc_html__( 'Draft', 'masterstudy-lms-learning-management-system-pro' );
						break;
				}

				$post_status = STM_LMS_Course::get_post_status( $id );

				$image       = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '272x161' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$image_small = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '50x50' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$is_featured = get_post_meta( $id, 'featured', true );

				$post = array(
					'id'           => $id,
					'time'         => get_post_time( 'U', true ),
					'title'        => get_the_title(),
					'link'         => get_the_permalink(),
					'image'        => $image,
					'image_small'  => $image_small,
					'terms'        => stm_lms_get_terms_array( $id, 'stm_lms_course_taxonomy', false, true ),
					'status'       => $status,
					'status_label' => $status_label,
					'percent'      => $percent,
					'is_featured'  => $is_featured,
					'average'      => $average,
					'total'        => ( ! empty( $rating ) ) ? count( $rating ) : 0,
					'views'        => STM_LMS_Course::get_course_views( $id ),
					'simple_price' => $price,
					'price'        => STM_LMS_Helpers::display_price( $price ),
					'edit_link'    => ms_plugin_manage_course_url( $id ),
					'post_status'  => $post_status,
				);

				$post['sale_price'] = ( ! empty( $sale_price ) ) ? STM_LMS_Helpers::display_price( $sale_price ) : '';

				$r['posts'][] = $post;
			}
		}

		$r['pages'] = ceil( $q->found_posts / $per_page );

		return $r;
	}

	public function get_co_courses() {
		check_ajax_referer( 'stm_lms_get_co_courses', 'nonce' );

		wp_send_json( self::getCoCourses() );
	}

}
