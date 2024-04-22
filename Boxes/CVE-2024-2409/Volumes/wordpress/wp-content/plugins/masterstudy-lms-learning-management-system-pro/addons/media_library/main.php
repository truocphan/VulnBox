<?php

new STM_LMS_Media_Library();

/**
 * @deprecated use REST API instead
 */
class STM_LMS_Media_Library {
	public function __construct() {
		add_action( 'wp_ajax_stm_lms_pro_media_library_get_file_by_id', 'STM_LMS_Media_Library::media_library_get_file_by_id' );
		add_action( 'wp_ajax_stm_lms_pro_media_library_get_files', 'STM_LMS_Media_Library::media_library_get_all_files' );
		add_action( 'wp_ajax_stm_lms_pro_media_library_delete_file', 'STM_LMS_Media_Library::media_library_delete_file' );
		add_action( 'wp_ajax_stm_lms_pro_media_library_search_file', 'STM_LMS_Media_Library::media_library_search_file' );
		add_action( 'wp_ajax_stm_lms_upload_media_library_file', array( $this, 'stm_lms_upload_media_library_file' ) );
		add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_media_library_settings_page' ) );
	}

	private static $file_types = array(
		'image'      => array( 'jpeg', 'jpg', 'png', 'gif', 'webp' ),
		'audio'      => array( 'mp3', 'm4a', 'ogg', 'wav' ),
		'video'      => array( 'mov', 'mp4', 'avi', 'wmv', 'mpg', 'ogv', '3pg', '3g2' ),
		'pdf'        => array( 'pdf' ),
		'excel'      => array( 'xml', 'xls', 'xmls', 'xlsx' ),
		'word'       => array( 'doc', 'docx' ),
		'archive'    => array( 'zip', 'rar', '7z' ),
		'powerPoint' => array( 'ppt', 'pptx', 'pps', 'ppsx' ),
	);

	public static function media_library_get_file_by_id( $id ) {
		$attachment = get_post( $id );

		return array(
			'id'       => $attachment->ID,
			'title'    => $attachment->post_title,
			'url'      => wp_get_attachment_url( $id ),
			'type'     => self::check_file_type( wp_get_attachment_url( $id ) ),
			'date'     => gmdate( 'Y-m-d', strtotime( $attachment->post_date ) ),
			'modified' => gmdate( 'Y-m-d', strtotime( $attachment->post_modified ) ),
			'size'     => self::file_size_formatter( $attachment->ID ),
		);
	}

	public static function media_library_get_all_files() {
		check_ajax_referer( 'stm_lms_media_library_get_files', 'nonce' );

		$sort_by      = ! empty( $_GET['filter']['sortBy'] ) ? sanitize_text_field( $_GET['filter']['sortBy'] ) : '';
		$order_by     = 'post_date' === $sort_by ? $sort_by : 'post_title';
		$per_page     = ! empty( $_GET['filesCount']['perPage'] ) ? intval( $_GET['filesCount']['perPage'] ) : '';
		$offset       = ! empty( $_GET['filesCount']['offset'] ) ? intval( $_GET['filesCount']['offset'] ) : '';
		$file_type    = ! empty( $_GET['filter']['fileType'] ) ? sanitize_text_field( $_GET['filter']['fileType'] ) : '';
		$allowed_type = 'all' === $file_type ? 'image, video, audio, application' : $file_type;
		$order        = 'post_date' === $sort_by ? 'DESC' : 'ASC';
		$count        = self::files_count();

		$args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => $allowed_type,
			'post_status'    => 'inherit',
			'order'          => $order,
			'orderby'        => $order_by,
			'posts_per_page' => $per_page,
			'offset'         => $offset,
		);

		if ( ! current_user_can( 'administrator' ) ) {
			$args['author'] = get_current_user_id();
		}

		$attachments = new WP_Query( $args );
		$result      = array();
		$arr         = array(
			'result' => 'files not found',
			'count'  => 0,
		);

		if ( $attachments->have_posts() ) {
			foreach ( $attachments->posts as $attachment ) {
				array_push(
					$result,
					array(
						'id'       => $attachment->ID,
						'title'    => $attachment->post_title,
						'url'      => wp_get_attachment_url( $attachment->ID ),
						'type'     => self::check_file_type( wp_get_attachment_url( $attachment->ID ) ),
						'date'     => gmdate( 'Y-m-d', strtotime( $attachment->post_date ) ),
						'modified' => gmdate( 'Y-m-d', strtotime( $attachment->post_modified ) ),
						'size'     => self::file_size_formatter( $attachment->ID ),
					)
				);
			}

			$arr['result'] = $result;
			$arr['count']  = $count;
		}

		wp_reset_postdata();
		wp_send_json( $arr );
	}

	public static function stm_lms_upload_media_library_file( $return = false ) {
		do_action( 'stm_lms_upload_files', $return );
		$allowed_extensions = array(
			'jpg',
			'jpeg',
			'png',
			'pdf',
			'doc',
			'docx',
			'ppt',
			'pptx',
			'pps',
			'ppsx',
			'xls',
			'xlsx',
			'psd',
			'mp3',
			'ogg',
			'wav',
			'mp4',
			'm4v',
			'mov',
			'wmv',
			'avi',
			'mpg',
			'zip',
		);

		$settings = self::stm_lms_get_settings();
		$count    = isset( $settings['files_ext'] ) ? strlen( $settings['files_ext'] ) : null;

		if ( $count ) {
			$allowed_extensions = explode( ',', $settings['files_ext'] );
		}

		$file = ! empty( $_FILES['file'] ) ? $_FILES['file'] : '';
		$path = ! empty( $file['name'] ) ? $file['name'] : '';

		$max_file_size = ! empty( $settings['max_file_size'] ) ? $settings['max_file_size'] : 5;
		$max_file_size = $max_file_size * 1024 * 1024;
		$filesize      = filesize( $file['tmp_name'] );

		$ext = pathinfo( $path, PATHINFO_EXTENSION );

		if ( ! in_array( $ext, $allowed_extensions, true ) ) {
			wp_send_json(
				array(
					'error'   => true,
					'message' => esc_html__( 'Invalid file extension', 'masterstudy-lms-learning-management-system-pro' ),
				)
			);
		}

		if ( $filesize > $max_file_size ) {
			wp_send_json(
				array(
					'error'   => true,
					'message' => esc_html__( 'File is too large.', 'masterstudy-lms-learning-management-system-pro' ),
				)
			);
		}

		$filename    = basename( $path );
		$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file['tmp_name'] ) ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( ! $upload_file['error'] ) {
			$wp_filetype   = wp_check_filetype( $filename, null );
			$attachment    = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_excerpt'   => 'stm_lms_assignment',
				'post_status'    => 'inherit',
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
			if ( ! is_wp_error( $attachment_id ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
				do_action( 'stm_lms_media_library_upload_image', $attachment_id );
				wp_send_json( array( 'file' => self::media_library_get_file_by_id( $attachment_id ) ) );
			}
		} else {
			wp_send_json(
				array(
					'error'   => true,
					'message' => $upload_file['error'],
				)
			);
		}
	}

	public static function media_library_delete_file() {
		check_ajax_referer( 'stm_lms_media_library_delete_file', 'nonce' );

		do_action( 'stm_lms_media_library_delete_image' );
		$attachment_id = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		wp_delete_attachment( $attachment_id );

		wp_send_json_success( array( 'status' => 200 ) );
	}

	public static function media_library_search_file() {
		check_ajax_referer( 'stm_lms_media_library_search_file', 'nonce' );

		global $wpdb;
		$title           = ! empty( $_GET['text'] ) ? sanitize_text_field( $_GET['text'] ) : '';
		$request_sort_by = ! empty( $_GET['filter']['sortBy'] ) ? sanitize_text_field( $_GET['filter']['sortBy'] ) : '';
		$sor_by          = 'post_date' === $request_sort_by ? 'post_date ' : 'post_title ';
		$sorting         = 'post_date' === $request_sort_by ? 'DESC' : 'ASC';

		$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = '%d' AND post_title LIKE '%s'" . 'ORDER BY ' . $sor_by . $sorting;

		$user_id = get_current_user_id();
		$myposts = $wpdb->get_results( $wpdb->prepare( $sql, array( $user_id, '%' . $wpdb->esc_like( $title ) . '%' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$result = array();

		foreach ( $myposts as $attachment ) {
			array_push(
				$result,
				array(
					'id'       => $attachment->ID,
					'title'    => $attachment->post_title,
					'url'      => wp_get_attachment_url( $attachment->ID ),
					'type'     => self::check_file_type( wp_get_attachment_url( $attachment->ID ) ),
					'date'     => gmdate( 'Y-m-d', strtotime( $attachment->post_date ) ),
					'modified' => gmdate( 'Y-m-d', strtotime( $attachment->post_modified ) ),
					'size'     => self::file_size_formatter( $attachment->ID ),
				)
			);
		}
		wp_reset_postdata();
		wp_send_json( array( 'result' => $result ) );
	}

	public static function check_file_type( $file_name ) {
		$result    = 'unknown';
		$file_name = explode( '.', $file_name );
		$file_name = $file_name[ count( $file_name ) - 1 ];

		foreach ( self::$file_types as $key => $value ) {
			if ( in_array( $file_name, $value, true ) ) {
				$result = $key;
				break;
			}
		}

		return $result;
	}

	public static function file_size_formatter( $attachment_id ) {
		$file_size = filesize( get_attached_file( $attachment_id ) );

		return size_format( $file_size );
	}

	public static function files_count() {
		$files = new WP_Query(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => - 1,
			)
		);

		wp_reset_postdata();

		return $files->post_count;
	}

	public static function stm_lms_get_settings() {
		return get_option( 'stm_lms_media_library_settings', array() );
	}

	public function stm_lms_media_library_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => esc_html__( 'Media File Manager', 'masterstudy-lms-learning-management-system-pro' ),
				'menu_title'  => esc_html__( 'Media File Manager', 'masterstudy-lms-learning-management-system-pro' ),
				'menu_slug'   => 'media_library_settings',
			),
			'fields'      => $this->stm_lms_media_library_settings(),
			'option_name' => 'stm_lms_media_library_settings',
		);

		return $setups;
	}

	public function stm_lms_media_library_settings() {
		return apply_filters(
			'stm_lms_media_library_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Credentials', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'max_file_size' => array(
							'type'  => 'number',
							'label' => esc_html__( 'Max file size (Mb)', 'masterstudy-lms-learning-management-system-pro' ),
							'value' => false,
						),
						'files_ext'     => array(
							'type'  => 'textarea',
							'label' => esc_html__( 'Allowed file extensions', 'masterstudy-lms-learning-management-system-pro' ),
							'value' => 'jpg,jpeg,png,pdf,doc,docx,ppt,pptx,pps,ppsx,xls,xlsx,psd,mp3,ogg,wav,mp4,m4v,mov,wmv,avi,mpg,zip',
							'hint'  => esc_html__( 'Separate extensions with comma without spaces', 'masterstudy-lms-learning-management-system-pro' ),
						),
					),
				),
			)
		);
	}
}
