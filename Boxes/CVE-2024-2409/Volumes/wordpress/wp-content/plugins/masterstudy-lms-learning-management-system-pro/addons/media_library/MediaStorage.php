<?php

namespace MasterStudy\Lms\Pro\addons\media_library;

use WP_Query;

final class MediaStorage {
	private const FILE_TYPES = array(
		'audio',
		'application',
		'image',
		'video',
	);

	private const SORT_BY = array(
		'date'  => 'post_date',
		'title' => 'post_title',
	);

	/**
	 * Max upload file size in MB
	 */
	private const DEFAULT_MAX_FILE_SIZE = 5;

	private static $settings = null;

	public static function allowed_extensions(): array {
		$settings = self::get_settings();

		if ( isset( $settings['files_ext'] ) && strlen( trim( $settings['files_ext'] ) ) ) {
			return array_map( 'trim', explode( ',', $settings['files_ext'] ) );
		}
		return array(
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
	}

	/**
	 * Returns the maximum upload size allowed in bytes.
	 */
	public static function max_upload_size(): int {
		$settings = self::get_settings();

		$max_file_size = ! empty( $settings['max_file_size'] )
			? (int) $settings['max_file_size']
			: self::DEFAULT_MAX_FILE_SIZE;

		return $max_file_size * 1024 * 1024;
	}

	/**
	 * @return array<\WP_Post>
	 */
	public function get( array $args ): array {
		$sort_by   = $args['sort_by'] ?? '';
		$per_page  = ! empty( $args['per_page'] ) ? (int) $args['per_page'] : '';
		$offset    = ! empty( $args['offset'] ) ? (int) $args['offset'] : '';
		$file_type = $args['filter']['file_type'] ?? '';

		if ( ! in_array( $file_type, self::FILE_TYPES, true ) ) {
			$file_type = implode( ', ', self::FILE_TYPES );
		}

		if ( ! isset( self::SORT_BY[ $sort_by ] ) ) {
			$sort_by = 'title';
		}

		$query_args = array(
			'author'         => $args['author'] ?? '',
			'post_mime_type' => $file_type,
			'order'          => 'date' === $sort_by ? 'DESC' : 'ASC',
			'orderby'        => self::SORT_BY[ $sort_by ],
			'posts_per_page' => $per_page,
			'offset'         => $offset,
			's'              => $args['filter']['search'] ?? '',
		);

		$query_args = wp_parse_args( $query_args, self::default_query() );

		$query = new WP_Query();

		return $query->query( $query_args );
	}

	public function count() {
		$query_args = wp_parse_args( array( 'posts_per_page' => 1 ), self::default_query() );

		$query = new WP_Query( $query_args );

		return $query->found_posts;
	}

	/**
	 * @return \WP_Post|\WP_Error
	 */
	public function upload( array $file ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$upload_file = wp_handle_upload(
			$file,
			array(
				'test_form' => false,
				'test_type' => false,
				'test_size' => false,
			)
		);

		if ( isset( $upload_file['error'] ) ) {
			return new \WP_Error( 'upload_error', $upload_file['error'] );
		}

		// If the uploaded file doesn't have a mime type, fake it based on the file extension.
		if ( empty( $upload_file['type'] ) ) {
			$upload_file['type'] = wp_check_filetype( $upload_file['file'] )['type'];
		}

		$filename      = ! empty( $file['name'] ) ? basename( $file['name'] ) : '';
		$attachment    = array(
			'post_mime_type' => $upload_file['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
			'post_status'    => 'inherit',
		);
		$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );

		do_action( 'stm_lms_media_library_upload_image', $attachment_id );

		return get_post( $attachment_id, );
	}

	public function get_by_id( int $id ) {
		return get_post( $id );
	}

	private static function default_query(): array {
		return array(
			'post_type'      => 'attachment',
			'post_mime_type' => implode( ', ', self::FILE_TYPES ),
			'post_status'    => 'inherit',
			'order'          => 'ASC',
			'orderby'        => 'post_title',
			'posts_per_page' => '',
			'offset'         => '',
		);
	}

	public function delete( int $id ) {
		do_action( 'stm_lms_media_library_delete_image' );

		return wp_delete_attachment( $id, true );
	}

	private static function get_settings() {
		if ( null !== self::$settings ) {
			return self::$settings;
		}

		$settings = get_option( 'stm_lms_media_library_settings', array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		self::$settings = $settings;

		return self::$settings;
	}
}
