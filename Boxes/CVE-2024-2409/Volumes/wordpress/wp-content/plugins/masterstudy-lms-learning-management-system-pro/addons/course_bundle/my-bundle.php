<?php

new STM_LMS_My_Bundle();

class STM_LMS_My_Bundle {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_save_bundle', array( $this, 'stm_lms_save_bundle' ) );
	}

	public static function bundle_courses_key() {
		return 'stm_lms_bundle_ids';
	}

	public static function bundle_price_key() {
		return 'stm_lms_bundle_price';
	}

	public static function get_bundle_data( $bundle_id ) {
		$bundle = get_post( $bundle_id );

		if ( ! empty( $bundle ) ) {
			$bundle_courses = get_post_meta( $bundle_id, self::bundle_courses_key(), true );

			if ( empty( $bundle_courses ) ) {
				$bundle->bundle_courses = '';
			} else {
				$bundle_courses         = STM_LMS_Instructor::get_courses(
					array(
						'posts_per_page' => count( $bundle_courses ),
						'post__in'       => $bundle_courses,
					),
					true
				);
				$bundle->bundle_courses = $bundle_courses['posts'];
			};

			$bundle->bundle_price    = floatval( get_post_meta( $bundle_id, self::bundle_price_key(), true ) );
			$image_id                = get_post_thumbnail_id( $bundle_id );
			$bundle->bundle_image_id = ( ! empty( $image_id ) ) ? get_the_title( $image_id ) : '';
		}

		return $bundle;
	}

	public function stm_lms_save_bundle() {
		do_action( 'stm_lms_save_bundle' );

		$user = STM_LMS_User::get_current_user();

		if ( empty( $user['id'] ) ) {
			die;
		}

		$user_id = $user['id'];

		$validation = new Validation();

		$validation->validation_rules(
			array(
				'name'        => 'required',
				'courses'     => 'required',
				'description' => 'required',
				'price'       => 'float',
			)
		);

		$validation->filter_rules(
			array(
				'name'        => 'trim|sanitize_string',
				'courses'     => 'trim',
				'price'       => 'sanitize_floats',
				'description' => 'trim',
			)
		);

		$data = $validation->run( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( false === $data ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => $validation->get_readable_errors( true ),
				)
			);
		}

		if ( empty( $data['id'] ) && empty( $_FILES['file'] ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'Please, upload bundle image', 'masterstudy-lms-learning-management-system-pro' ),
				)
			);
		}

		if ( ! empty( $_FILES['file'] ) ) {

			$allowed_extensions = array(
				'jpg',
				'jpeg',
				'png',
			);

			$file = $_FILES['file'];
			$path = $file['name'];
			$ext  = pathinfo( $path, PATHINFO_EXTENSION );

			if ( ! in_array( $ext, $allowed_extensions, true ) ) {
				wp_send_json(
					array(
						'error'   => true,
						'message' => esc_html__( 'Invalid file extension', 'masterstudy-lms-learning-management-system-pro' ),
					)
				);
			}
		}

		do_action( 'stm_lms_bundle_data_validated', $data );

		$post_status = 'publish';
		$quota       = self::get_bundles_limit();
		$published   = self::get_bundles();

		if ( floatval( $quota ) <= floatval( $published ) ) {
			$post_status = 'draft';
		}

		if ( ! empty( $data['id'] ) ) {
			if ( get_post_status( 'publish' === $data['id'] ) ) {
				$post_status = 'publish';
			}
		}

		if ( empty( $data['id'] ) || ! self::check_author( $data['id'], $user_id ) ) {
			$data['id'] = wp_insert_post(
				array(
					'post_status'  => $post_status,
					'post_type'    => 'stm-course-bundles',
					'post_title'   => $data['name'],
					'post_content' => $data['description'],
				)
			);
		} else {
			/*Check if we have an image*/
			$image = get_post_thumbnail_id( $data['id'] );

			if ( empty( $image ) && empty( $_FILES['file'] ) ) {
				wp_send_json(
					array(
						'status'  => 'error',
						'message' => esc_html__( 'Please, upload bundle image', 'masterstudy-lms-learning-management-system-pro' ),
					)
				);
			}

			wp_update_post(
				array(
					'ID'           => $data['id'],
					'post_type'    => 'stm-course-bundles',
					'post_status'  => $post_status,
					'post_title'   => $data['name'],
					'post_content' => $data['description'],
				)
			);
		}

		$limit = self::get_bundle_courses_limit();

		update_post_meta( $data['id'], self::bundle_courses_key(), array_slice( explode( ',', $data['courses'] ), 0, $limit ) );
		update_post_meta( $data['id'], self::bundle_price_key(), $data['price'] );

		if ( ! empty( $_FILES['file'] ) ) {
			$image = self::upload_image( $data['id'] );
			if ( $image['error'] ) {
				wp_send_json( $image );
			}
		}

		if ( class_exists( 'STM_LMS_Woocommerce' ) ) {
			STM_LMS_Woocommerce::create_product( $data['id'] );
		}

		wp_send_json(
			array(
				'status'  => 'success',
				'message' => esc_html__( 'Bundle saved. Redirecting...', 'masterstudy-lms-learning-management-system-pro' ),
				'url'     => ms_plugin_user_account_url( 'bundles' ),
			)
		);

	}

	public static function check_author( $post_id, $user_id ) {
		$author_id = get_post_field( 'post_author', $post_id );

		return intval( $author_id ) === intval( $user_id );
	}

	public static function upload_image( $bundle_id ) {
		if ( empty( $_FILES['file'] ) ) {
			return ( array(
				'error'   => true,
				'message' => esc_html__( 'Invalid File', 'masterstudy-lms-learning-management-system-pro' ),
			) );
		}

		$file = $_FILES['file'];
		$path = $file['name'];

		do_action( 'stm_lms_upload_files' );

		$filename = basename( $path );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file['tmp_name'] ) );

		if ( ! $upload_file['error'] ) {
			$wp_filetype   = wp_check_filetype( $filename, null );
			$attachment    = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent'    => $bundle_id,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_excerpt'   => 'stm_lms_assignment',
				'post_status'    => 'inherit',
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $bundle_id );
			if ( ! is_wp_error( $attachment_id ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
				set_post_thumbnail( $bundle_id, $attachment_id );
			}

			return ( array(
				'error' => false,
				'id'    => $attachment_id,
				'link'  => wp_get_attachment_url( $attachment_id ),
			) );

		} else {
			return ( array(
				'error'   => true,
				'message' => $upload_file['error'],
			) );
		}
	}

	public static function get_bundles_limit() {
		$settings = STM_LMS_Course_Bundle_Settings::stm_lms_get_settings();

		return ( ! empty( $settings['bundle_limit'] ) ) ? $settings['bundle_limit'] : 6;
	}

	public static function get_bundle_courses_limit() {
		$settings = STM_LMS_Course_Bundle_Settings::stm_lms_get_settings();

		return ( ! empty( $settings['bundle_courses_limit'] ) ) ? $settings['bundle_courses_limit'] : 5;
	}

	public static function get_bundles( $args = array() ) {
		$default = array(
			'post_type'      => 'stm-course-bundles',
			'posts_per_page' => 1,
			'post_status'    => array( 'publish' ),
		);

		$args = wp_parse_args( $args, $default );

		$q = new WP_Query( $args );

		return $q->found_posts;
	}

	public static function get_available_quota() {
		$quota     = self::get_bundles_limit();
		$published = self::get_bundles();

		return floatval( $quota ) - floatval( $published );
	}

}
