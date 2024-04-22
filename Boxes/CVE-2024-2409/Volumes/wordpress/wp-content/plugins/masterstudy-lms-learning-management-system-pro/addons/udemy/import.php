<?php

new STM_LMS_Udemy_Import();

class STM_LMS_Udemy_Import {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_pro_udemy_import_courses', 'STM_LMS_Udemy_Import::import_course' );
		add_action( 'wp_ajax_stm_lms_pro_udemy_import_curriculum', 'STM_LMS_Udemy_Import::import_curriculum' );
	}

	public static function udemy_base_api() {
		return 'https://www.udemy.com/api-2.0/';
	}

	public static function course_fields() {
		return array(
			'title',
			'headline',
			'description',
			'primary_category',
			'primary_subcategory',
			'num_reviews',
			'num_subscribers',
			'status_label',
			'url',
			'price',
			'discount_price',
			'avg_rating',
			'image_750x422',
			'num_lectures',
			'num_quizzes',
			'rating_distribution',
			'headline',
			'prerequisites',
			'objectives',
			'objectives_summary',
			'target_audiences',
			'faq',
			'content_info',
			'promo_asset',
			'num_additional_assets',
			'num_article_assets',
			'content_length_video',
			'has_certificate',
			'caption_languages',
			'visible_instructors',
		);
	}

	public static function import_course() {
		if ( empty( $_GET['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			die;
		}

		$update_course   = ! empty( $_GET['update'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$udemy_course_id = intval( $_GET['id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $update_course ) {
			$udemy_course_id = get_post_meta( $udemy_course_id, 'udemy_course_id', true );
		}

		$course_data  = self::get_course_info_from_udemy( $udemy_course_id, $update_course );
		$course_exist = self::is_course_exist( $udemy_course_id );
		$course_id    = self::update_course( $course_exist, $course_data );

		wp_send_json(
			array(
				'course_id' => $course_id,
				'message'   => esc_html__( 'Importing Curriculum', 'masterstudy-lms-learning-management-system-pro' ),
			)
		);
	}

	public static function get_course_info_from_udemy( $udemy_course_id, $update = false ) {
		$transient_name = "stm_lms_search_courses_{$udemy_course_id}";
		$transient      = get_transient( $transient_name );

		if ( $update ) {
			$transient = false;
		}

		if ( false === $transient ) {
			$udemy_base_api_url = self::udemy_base_api();
			$course_fields      = implode( ',', self::course_fields() );

			$course = wp_remote_get( "{$udemy_base_api_url}courses/{$udemy_course_id}?fields[course]={$course_fields}" );

			if ( is_array( $course ) && ! empty( $course['body'] ) ) {
				$course = json_decode( $course['body'], true );

				set_transient( $transient_name, $course );
			}
		} else {
			$course = $transient;
		}

		return ( $course );
	}

	public static function is_course_exist( $udemy_course_id ) {
		$args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'     => 'udemy_course_id',
					'value'   => $udemy_course_id,
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		$course_id = 0;

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$course_id = get_the_ID();
			}
		}

		return $course_id;
	}

	public static function update_course( $course_id, $course_data ) {
		$course_id = self::update_course_generic( $course_id, $course_data );
		self::update_course_meta( $course_id, $course_data );
		self::update_course_category( $course_id, $course_data );
		self::set_post_image( $course_id, $course_data );

		return $course_id;
	}

	public static function update_course_generic( $course_id, $course_data ) {
		$course_post = array(
			'post_type'    => 'stm-courses',
			'post_title'   => wp_strip_all_tags( $course_data['title'] ),
			'post_content' => $course_data['description'],
			'post_status'  => 'draft',
		);

		if ( ! empty( $course_id ) ) {
			$course_post['ID']          = $course_id;
			$course_post['post_status'] = get_post_status( $course_id );
		}

		return wp_insert_post( $course_post );
	}

	public static function update_course_meta( $course_id, $course_data ) {
		$metas = array(
			'udemy_course_id'       => $course_data['id'],
			'affiliate_course'      => 'on',
			'affiliate_course_text' => esc_html__( 'Get on Udemy', 'masterstudy-lms-learning-management-system-pro' ),
			'affiliate_course_link' => "https://www.udemy.com{$course_data['url']}",
		);

		if ( isset( $course_data['price'] ) ) {
			$metas['price']       = preg_replace( '/[^0-9\.]/', '', $course_data['price'] );
			$metas['single_sale'] = 'on';
		}
		if ( isset( $course_data['num_subscribers'] ) ) {
			$metas['current_students'] = intval( $course_data['num_subscribers'] );
		}
		if ( isset( $course_data['avg_rating'] ) ) {
			$metas['udemy_avg_rating'] = round( $course_data['avg_rating'], 2 );
		}
		if ( isset( $course_data['num_reviews'] ) ) {
			$metas['udemy_num_reviews'] = $course_data['num_reviews'];
		}
		if ( isset( $course_data['content_info'] ) ) {
			$metas['duration_info'] = $course_data['content_info'];
		}
		if ( isset( $course_data['rating_distribution'] ) ) {
			$metas['udemy_rating_distribution'] = $course_data['rating_distribution'];
		}
		if ( isset( $course_data['headline'] ) ) {
			$metas['udemy_headline'] = $course_data['headline'];
		}
		if ( isset( $course_data['prerequisites'] ) ) {
			$metas['udemy_prerequisites'] = $course_data['prerequisites'];
		}
		if ( isset( $course_data['objectives'] ) ) {
			$metas['udemy_objectives'] = $course_data['objectives'];
		}
		if ( isset( $course_data['objectives_summary'] ) ) {
			$metas['udemy_objectives_summary'] = $course_data['objectives_summary'];
		}
		if ( isset( $course_data['target_audiences'] ) ) {
			$metas['udemy_target_audiences'] = $course_data['target_audiences'];
		}
		if ( isset( $course_data['objectives_summary'] ) ) {
			$metas['udemy_objectives_summary'] = $course_data['objectives_summary'];
		}
		if ( isset( $course_data['faq'] ) ) {
			$metas['faq'] = wp_json_encode( $course_data['faq'] );
		}
		if ( isset( $course_data['promo_asset'] ) ) {
			$metas['udemy_promo_asset'] = $course_data['promo_asset'];
		}
		if ( isset( $course_data['num_additional_assets'] ) ) {
			$metas['udemy_num_additional_assets'] = $course_data['num_additional_assets'];
		}
		if ( isset( $course_data['num_article_assets'] ) ) {
			$metas['udemy_num_article_assets'] = $course_data['num_article_assets'];
		}
		if ( isset( $course_data['content_length_video'] ) ) {
			$metas['udemy_content_length_video'] = $course_data['content_length_video'];
		}
		if ( isset( $course_data['has_certificate'] ) ) {
			$metas['udemy_has_certificate'] = $course_data['has_certificate'];
		}
		if ( isset( $course_data['caption_languages'] ) ) {
			$metas['udemy_caption_languages'] = $course_data['caption_languages'];
		}
		if ( isset( $course_data['visible_instructors'] ) ) {
			$metas['udemy_visible_instructors'] = $course_data['visible_instructors'];
		}
		if ( isset( $course_data['discount_price'] ) ) {
			$discount = $course_data['discount_price'];
			if ( ! empty( $discount['amount'] ) ) {
				$metas['sale_price'] = intval( $discount['amount'] );
			}
		}

		foreach ( $metas as $meta_key => $meta_value ) {
			update_post_meta( $course_id, $meta_key, $meta_value );
		}
	}

	public static function update_course_category( $course_id, $course_data ) {
		if ( ! empty( $course_data['primary_category'] ) && ! empty( $course_data['primary_subcategory'] ) ) {
			$parent_term = $course_data['primary_category'];
			$child_term  = $course_data['primary_subcategory'];

			$parent_term_title = $parent_term['title'];
			$child_term_title  = $child_term['title'];

			$parent    = wp_insert_term( $parent_term_title, 'stm_lms_course_taxonomy' );
			$parent_id = ( ! is_wp_error( $parent ) ) ? $parent['term_id'] : $parent->get_error_data();

			$child    = wp_insert_term( $child_term_title, 'stm_lms_course_taxonomy', array( 'parent' => $parent_id ) );
			$child_id = ( ! is_wp_error( $child ) ) ? $child['term_id'] : $child->get_error_data();

			wp_set_post_terms( $course_id, array( $parent_id, $child_id ), 'stm_lms_course_taxonomy' );
		}
	}

	public static function upload_course_image( $course_id, $course_data ) {
		if ( ! empty( $course_data['image_750x422'] ) ) {
			$url = $course_data['image_750x422'];

			/*Check if image exist*/
			$image_exist = get_post_thumbnail_id( $course_id );
			if ( ! empty( $image_exist ) ) {
				$alt = get_post_meta( $image_exist, '_wp_attachment_image_alt', true );

				if ( ! empty( $alt ) && md5( $url ) === $alt ) {
					return $image_exist;
				}
			}

			require_once ABSPATH . 'wp-load.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			$tmp        = download_url( $url );
			$desc       = $course_id;
			$file_array = array();

			preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches );
			$file_array['name']     = basename( $matches[0] );
			$file_array['tmp_name'] = $tmp;

			if ( is_wp_error( $tmp ) ) {
				@unlink( $file_array['tmp_name'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				$file_array['tmp_name'] = '';
			}

			$id = media_handle_sideload( $file_array, $course_id, $desc );

			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				return $id;
			}

			update_post_meta( $id, '_wp_attachment_image_alt', md5( $url ) );

			return $id;
		}
	}

	public static function set_post_image( $course_id, $course_data ) {
		$image_id = self::upload_course_image( $course_id, $course_data );
		set_post_thumbnail( $course_id, $image_id );
	}

	public static function import_curriculum() {
		check_ajax_referer( 'stm_lms_pro_udemy_import_curriculum', 'nonce' );

		$course_id        = intval( $_GET['id'] );
		$curriculum_udemy = self::get_curriculum_from_udemy( $course_id );
		update_post_meta( $course_id, 'udemy_curriculum', $curriculum_udemy );

		wp_send_json(
			array(
				'message'         => esc_html__( 'Preview', 'masterstudy-lms-learning-management-system-pro' ),
				'course_url'      => get_permalink( $course_id ),
				'course_url_edit' => ms_plugin_manage_course_url( $course_id ),
			)
		);
	}

	public static function get_curriculum_from_udemy( $course_id ) {
		$udemy_course_id = get_post_meta( $course_id, 'udemy_course_id', true );
		$transient_name  = "stm_lms_course_curriculum_{$udemy_course_id}";
		$curriculum      = get_transient( $transient_name );

		if ( false === $curriculum ) {
			$udemy_base_api_url = self::udemy_base_api();

			$curriculum = wp_remote_get( "{$udemy_base_api_url}courses/{$udemy_course_id}/public-curriculum-items/?page_size=100" );
			if ( is_array( $curriculum ) && ! empty( $curriculum['body'] ) ) {
				$curriculum = json_decode( $curriculum['body'], true );

				set_transient( $transient_name, $curriculum );
			}
		}

		return ( $curriculum );
	}
}
