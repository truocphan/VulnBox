<?php
namespace JupiterX_Core\Control_Panel_2\Custom_Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Filesystem_Base;
use WP_Query;
use ZipArchive;

/**
 * Handles custom icons functionality in control panel.
 *
 * @suppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * @package JupiterX_Core\Control_Panel_2\Custom_Icons
 *
 * @since 2.5.0
 */
class JupiterX_Core_Control_Panel_Custom_Icons {

	private static $instance = null;

	const POST_TYPE = 'jupiterx-icons';

	const OPTION_NAME = 'jupiterx_custom_icon_sets_config';

	const META_KEY = 'jupiterx_custom_icon_sets_config';

	/**
	 * Instance of class.
	 *
	 * @return object
	 * @since 2.5.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_ajax_jupiterx_custom_icons', [ $this, 'handle_ajax' ] );
		add_action( 'wp_ajax_jupiterx_custom_icons_get_posts', [ $this, 'get_posts' ] );
	}

	/**
	 * Handle ajax requests.
	 * Gets Ajax call sub_action parameter and call a function based on parameter value.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function handle_ajax() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$action = filter_input( INPUT_POST, 'sub_action', FILTER_UNSAFE_RAW );

		if ( ! empty( $action ) && method_exists( $this, $action ) ) {
			call_user_func( [ $this, $action ] );
		}
	}

	/**
	 * Gets Custom icon posts.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function get_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

		/**
		 * Filter List Table query arguments.
		 *
		 * @param array $args The query arguments.
		 *
		 * @since 2.5.0
		 */
		$args = apply_filters( 'jupiterx_custom_icon_list_table_' . self::POST_TYPE . '_args', [
			'post_type'      => self::POST_TYPE,
			'paged'          => $paged,
			'posts_per_page' => 20,
		] );

		$query = new \WP_Query( $args );

		/**
		 * Filter List Table query posts.
		 *
		 * @param array $args The taxonomy arguments.
		 *
		 * @since 2.5.0
		 */
		$posts = apply_filters( 'jupiterx_custom_icon_list_table_' . self::POST_TYPE . '_posts', $query->posts );

		/**
		 * Filter List Table columns.
		 *
		 * @param array $args The columns headings and values.
		 *
		 * @since 2.5.0
		 */
		$columns = apply_filters( 'jupiterx_custom_icon_list_table_' . self::POST_TYPE . '_columns', [
			'labels' => [
				esc_html__( 'Author', 'jupiterx-core' ),
				esc_html__( 'Created on', 'jupiterx-core' ),
			],
			'values' => [ '' ],
		], $posts );

		// columns value.
		foreach ( $posts as $post ) {
			$columns['values'][ "post_{$post->ID}" ] = [
				get_the_author_meta( 'user_login', get_post_field( 'post_author', $post->ID ) ),
				get_the_time( 'Y-m-d', $post->ID ),
			];

			$post->user_url = get_edit_user_link( get_the_author_meta( 'ID', get_post_field( 'post_author', $post->ID ) ) );
		}

		// Send response.
		wp_send_json_success( [
			'posts'         => $posts,
			'max_num_pages' => $query->max_num_pages,
			'columns'       => $columns,
		] );
	}

	/**
	 * Create and update post by ajax.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function save_post() {
		$post = filter_input( INPUT_POST, 'post', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

		if ( empty( $post['custom_icons_post_title'] ) ) {
			wp_send_json_error( esc_html__( 'Name of the custom icon can not be empty.', 'jupiterx-core' ) );
		}

		if ( empty( $post['icon_set_content'] ) ) {
			wp_send_json_error( esc_html__( 'Upload a valid icon set zip file.', 'jupiterx-core' ) );
		}

		//If it is not update, don't let duplicate title.
		$query_args = [
			'post_type'      => self::POST_TYPE,
			's'              => $post['custom_icons_post_title'],
			'posts_per_page' => 1,
		];

		$icon_query       = new WP_Query( $query_args );
		$current_icon_obj = ( is_array( $icon_query->get_posts() ) && count( $icon_query->get_posts() ) > 0 ) ? (object) $icon_query->get_posts()[0] : false;

		if ( empty( $post['custom_icons_submit_mode'] ) && ! empty( $current_icon_obj->ID ) ) {
			wp_send_json_error( esc_html__( 'This icon set title already exists. Please choose another one.', 'jupiterx-core' ) );
		}

		//Escape path slashes to save as json.
		//Add folder icon for icon set in icon library.
		$content            = json_decode( $post['icon_set_content'] );
		$content->path      = addslashes( $content->path );
		$content->labelIcon = 'eicon eicon-folder'; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

		$post['icon_set_content'] = wp_json_encode( $content );

		$post_data = [
			'post_title'   => wp_strip_all_tags( $post['custom_icons_post_title'] ),
			'post_content' => $post['icon_set_content'],
			'post_status'  => 'publish',
			'post_type'    => self::POST_TYPE,
			'meta_input'   => [
				'jupiterx_custom_icon_sets_config' => $post['icon_set_config'],
				'_jupiterx_icon_set_path' => $post['icon_set_path'],
			],
		];

		// Check if it's update query.
		if ( '' !== $post['custom_icons_submit_mode'] ) {
			$post_data['ID'] = $post['custom_icons_submit_mode'];
		}

		$result = wp_insert_post( $post_data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success();
	}

	/**
	 * Remove post by ajax.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function remove_post() {
		$post   = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$result = $this->delete_post( $post );

		if ( false === $result || null === $result ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Delete a specific post with ID.
	 *
	 * @param int $id
	 *
	 * @return array|false|WP_Post
	 * @since 2.5.0
	 */
	private function delete_post( $id ) {
		return wp_delete_post( $id, true );
	}

	/**
	 * Returns a wp file system object for further needs.
	 *
	 * @return WP_Filesystem_Base
	 * @since 2.5.0
	 */
	public static function get_wp_filesystem() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		return $wp_filesystem;
	}

	/**
	 * Checks the file extension and if it is ZIP, it will upload it.
	 *
	 * @return mixed
	 * @since 2.5.0
	 */
	private function upload() {
		$files = filter_var_array( $_FILES, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$ext   = pathinfo( $files['file']['name'], PATHINFO_EXTENSION );

		if ( 'zip' !== $ext ) {
			unlink( $files['file']['name'] );
			wp_send_json_error( esc_html__( 'Only zip files are allowed', 'jupiterx-core' ) );
		}

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		// Handler upload archive file.
		$upload_result = wp_handle_upload( $files['file'], [ 'test_form' => false ] );
		if ( isset( $upload_result['error'] ) ) {
			unlink( $files['file']['name'] );

			wp_send_json_error( $upload_result['error'] );
		}
		return $upload_result['file'];
	}

	/**
	 * Extracts the zip file in given directory.
	 *
	 * @param $file
	 * @param $to
	 *
	 * @return bool
	 * @since 2.5.0
	 */
	private function extract_zip( $file, $to ) {
		$valid_field_types = [
			'css',
			'eot',
			'html',
			'json',
			'otf',
			'svg',
			'ttf',
			'txt',
			'woff',
			'woff2',
		];

		$zip = new ZipArchive();

		$zip->open( $file );

		$valid_entries = [];

		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
		for ( $i = 0; $i < $zip->numFiles; $i++ ) {
			$zipped_file_name = $zip->getNameIndex( $i );
			$dirname          = pathinfo( $zipped_file_name, PATHINFO_DIRNAME );

			// Skip the OS X-created __MACOSX directory.
			if ( '__MACOSX/' === substr( $dirname, 0, 9 ) ) {
				continue;
			}

			$zipped_extension = pathinfo( $zipped_file_name, PATHINFO_EXTENSION );

			if ( in_array( $zipped_extension, $valid_field_types, true ) ) {
				$valid_entries[] = $zipped_file_name;
			}
		}

		$unzip_result = false;

		if ( ! empty( $valid_entries ) ) {
			$unzip_result = $zip->extractTo( $to, $valid_entries );
		}

		if ( ! $unzip_result ) {
			wp_send_json_error( esc_html__( 'Could not unzip or empty archive.', 'jupiterx-core' ) );
		}

		return $unzip_result;
	}

	/**
	 * Upload zip file in temp and extract the zip file in wp-content/uploads/elementor/custom-icons.
	 *
	 * @return array|mixed
	 * @since 2.5.0
	 */
	private function upload_and_extract_zip() {
		$zip_file = $this->upload();

		if ( is_wp_error( $zip_file ) ) {
			return $zip_file;
		}

		$filesystem = self::get_wp_filesystem();
		$extract_to = trailingslashit( get_temp_dir() . pathinfo( $zip_file, PATHINFO_FILENAME ) );

		$unzipped = $this->extract_zip( $zip_file, $extract_to );

		if ( is_wp_error( $unzipped ) ) {
			wp_send_json_error( esc_html__( 'Unable to extract.', 'jupiterx-core' ) );
		}

		// Find the right folder.
		$source_files = array_keys( $filesystem->dirlist( $extract_to ) );

		if ( count( $source_files ) === 0 ) {
			wp_send_json_error( esc_html__( 'Incompatible archive.', 'jupiterx-core' ) );
		}

		$directory = $extract_to;
		if ( 1 === count( $source_files ) && $filesystem->is_dir( $extract_to . $source_files[0] ) ) {
			$directory = $extract_to . trailingslashit( $source_files[0] );
		}

		return [
			'directory' => $directory,
			'extracted_to' => $extract_to,
		];
	}

	/**
	 * Handles the ajax upload.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function upload_handler() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have access to upload files. Please contact website administrator.', 'jupiterx-core' ) );
		}

		$files = filter_var_array( $_FILES, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( $files['file']['error'] > 0 ) {
			wp_send_json_error( esc_html__( 'Package is not supported.', 'jupiterx-core' ) );
		}

		$results = $this->upload_and_extract_zip();

		if ( is_wp_error( $results ) ) {
			wp_send_json_error( $results->get_error_message() );
		}

		$supported_icon_sets = $this->get_supported_icon_sets();

		foreach ( $supported_icon_sets as $key => $handler ) {

			/**
			 * @var Icon_Sets\Icon_Set_Base $icon_set_handler
			 */
			require_once 'icon-sets/' . $key . '.php';
			$icon_set_handler = new $handler( $results['directory'] );

			if ( ! $icon_set_handler ) {
				continue;
			}

			if ( ! $icon_set_handler->is_valid() ) {
				continue;
			}

			$icon_set_handler->handle_new_icon_set();
			$config   = $icon_set_handler->build_config();
			$dir_path = $icon_set_handler->move_files();

			$config['path'] = $dir_path;

			// Notify about duplicate prefix
			if ( self::icon_set_prefix_exists( $config['prefix'] ) ) {
				$config['duplicatePrefix'] = true;
			}

			wp_send_json_success( [
				'status' => 'success',
				'message' => esc_html__( 'success', 'jupiterx-core' ),
				'config' => $config,
			] );
		}

		wp_send_json_error( esc_html__( 'Missing data files.', 'jupiterx-core' ) );
	}

	/**
	 * Checks of duplicate prefix inorder to show user a proper message.
	 *
	 * @param $prefix
	 *
	 * @return bool
	 * @since 2.5.0
	 */
	public static function icon_set_prefix_exists( $prefix ) {
		$config = self::get_custom_icons_config();

		if ( empty( $config ) ) {
			return false;
		}

		foreach ( $config as $icon_config ) {
			if ( $prefix === $icon_config['prefix'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets custom icons configurations.
	 * First, check options for configurations, if not existed, get POST_TYPE posts and create config.
	 *
	 * @return array|false|mixed|void
	 * @since 2.5.0
	 */
	public static function get_custom_icons_config() {
		$config = get_option( self::OPTION_NAME, false );

		if ( false === $config ) {
			$icons = new WP_Query( [
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
			] );

			$config = [];
			foreach ( $icons->posts as $icon_set ) {
				$set_config                        = json_decode( $icon_set->post_content, true );
				$set_config['custom_icon_post_id'] = $icon_set->ID;
				$set_config['label']               = $icon_set->post_title;

				$config[ $set_config['name'] ] = $set_config;
			}

			update_option( self::OPTION_NAME, $config );
		}

		return $config;
	}

	/**
	 * Currently supporting Fontastic, Fontello and IcoMoon.
	 * Returns the namespace of each supporting Icon set package.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public static function get_supported_icon_sets() {
		return [
			'fontastic' => __NAMESPACE__ . '\Icon_Sets\Fontastic',
			'fontello' => __NAMESPACE__ . '\Icon_Sets\Fontello',
			'icomoon' => __NAMESPACE__ . '\Icon_Sets\Icomoon',
		];
	}
}

JupiterX_Core_Control_Panel_Custom_Icons::get_instance();
