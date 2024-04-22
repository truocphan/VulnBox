<?php

new STM_LMS_My_Bundles();

class STM_LMS_My_Bundles {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_get_user_bundles', array( $this, 'stm_lms_get_user_bundles' ) );
		add_action( 'wp_ajax_stm_lms_change_bundle_status', array( $this, 'stm_lms_change_bundle_status' ) );
		add_action( 'wp_ajax_stm_lms_delete_bundle', array( $this, 'stm_lms_delete_bundle' ) );
	}

	public function stm_lms_delete_bundle() {

		do_action( 'stm_lms_delete_bundle' );

		check_ajax_referer( 'stm_lms_delete_bundle', 'nonce' );

		$bundle_id = intval( $_GET['bundle_id'] );

		if ( ! STM_LMS_My_Bundle::check_author( $bundle_id, get_current_user_id() ) ) {
			die;
		}

		wp_delete_post( $bundle_id, true );

		wp_send_json( 'OK' );

	}

	public function stm_lms_change_bundle_status() {

		do_action( 'stm_lms_change_bundle_status' );

		check_ajax_referer( 'stm_lms_change_bundle_status', 'nonce' );

		$bundle_id = intval( $_GET['bundle_id'] );

		if ( ! STM_LMS_My_Bundle::check_author( $bundle_id, get_current_user_id() ) ) {
			die;
		}

		$bundle_status = get_post_status( $bundle_id );

		$post_status = 'draft';

		if ( 'draft' === $bundle_status && STM_LMS_My_Bundle::get_available_quota() ) {
			$post_status = 'publish';
		}

		if ( 'draft' === $bundle_status && ! STM_LMS_My_Bundle::get_available_quota() ) {
			wp_send_json( esc_html__( 'Quota exceeded', 'masterstudy-lms-learning-management-system-pro' ) );
		}

		wp_update_post(
			array(
				'ID'          => $bundle_id,
				'post_status' => $post_status,
			)
		);

		wp_send_json( 'OK' );
	}

	public function stm_lms_get_user_bundles() {
		wp_send_json( self::get_bundles() );
	}

	public static function per_page() {
		return 6;
	}

	public static function get_bundles( $args = array(), $public = false ) {
		$per_page = self::per_page();
		if ( ! empty( $args['posts_per_page'] ) ) {
			$per_page = $args['posts_per_page'];
		}

		$page = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$page_v = get_query_var( 'paged' );
		if ( ! empty( $page_v ) ) {
			$args['stm_lms_page'] = intval( $page_v );
		}

		if ( ! empty( $args['stm_lms_page'] ) ) {
			$page = $args['stm_lms_page'];
		}
		$offset = ( ! empty( $page ) ) ? ( $page * $per_page ) - $per_page : 0;

		$args = wp_parse_args(
			$args,
			array(
				'post_type'      => 'stm-course-bundles',
				'posts_per_page' => $per_page,
				'post_status'    => array( 'publish', 'draft' ),
				'author'         => get_current_user_id(),
			)
		);

		if ( ! empty( $offset ) ) {
			$args['offset'] = $offset;
		}

		$r = array(
			'posts' => array(),
		);

		if ( ! is_user_logged_in() && ! $public ) {
			return $r;
		}

		$q = new WP_Query( $args );

		$courses = array();

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$id = get_the_ID();

				$bundle_courses = get_post_meta( $id, STM_LMS_My_Bundle::bundle_courses_key(), true );

				if ( ! empty( $bundle_courses ) ) {
					$courses = array_unique( array_merge( $courses, $bundle_courses ) );
				}

				foreach ( $bundle_courses as $course_key => $course ) {
					if ( empty( get_post_type( $course ) ) || ( get_post_status( $course ) !== 'publish' ) ) {
						unset( $bundle_courses[ $course_key ] );
					}
				}

				$price = get_post_meta( $id, STM_LMS_My_Bundle::bundle_price_key(), true );

				$r['posts'][] = array(
					'id'        => $id,
					'title'     => get_the_title(),
					'url'       => get_the_permalink(),
					'edit_url'  => ms_plugin_user_account_url( "bundles/$id" ),
					'raw_price' => $price,
					'price'     => STM_LMS_Helpers::display_price( $price ),
					'courses'   => $bundle_courses,
					'status'    => get_post_status( $id ),
				);
			}

			wp_reset_postdata();
		}

		$found_posts = $q->found_posts;

		/*Get Courses*/
		$args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => count( $courses ),
			'post__in'       => $courses,
		);

		$q            = new WP_Query( $args );
		$r['courses'] = array();

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
					$price = $sale_price;
				}

				$image       = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '272x161' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$image_small = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '50x50' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$is_featured = get_post_meta( $id, 'featured', true );

				$rating_count = ! empty( $rating ) ? count( $rating ) : '';

				$r['courses'][ $id ] = array(
					'id'           => $id,
					'time'         => get_post_time( 'U', true ),
					'title'        => get_the_title(),
					'link'         => get_the_permalink(),
					'image'        => $image,
					'image_small'  => $image_small,
					'terms'        => stm_lms_get_terms_array( $id, 'stm_lms_course_taxonomy', false, true ),
					'status'       => $status,
					'percent'      => $percent,
					'is_featured'  => $is_featured,
					'average'      => $average,
					'total'        => $rating_count,
					'views'        => STM_LMS_Course::get_course_views( $id ),
					'simple_price' => $price,
					'price'        => STM_LMS_Helpers::display_price( floatval( $price ) ),
				);
			}
		}

		$r['pages'] = ceil( $found_posts / $per_page );

		wp_reset_postdata();

		return $r;
	}
}
