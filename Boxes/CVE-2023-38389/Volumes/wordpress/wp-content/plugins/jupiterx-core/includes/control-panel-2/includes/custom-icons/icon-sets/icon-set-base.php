<?php
namespace JupiterX_Core\Control_Panel_2\Custom_Icons\Icon_Sets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Filesystem_Base;

/**
 * An abstract class for handling different icon set packages, checking and recognizing and processing them.
 *
 * @package JupiterX_Core\Control_Panel_2\Custom_Icons
 *
 * @since 2.5.0
 */
abstract class Icon_Set_Base {

	protected $dir_name = '';

	protected $directory = '';

	protected $data_file = '';

	protected $stylesheet_file = '';

	protected $allowed_zipped_files = [];

	protected $files_to_save = [];

	protected $allowed_webfont_extensions = [ 'woff', 'woff2', 'ttf', 'svg', 'otf', 'eot' ];

	abstract protected function extract_icon_list();

	abstract protected function prepare();

	abstract protected function get_type();

	abstract public function get_name();

	private function is_path_dir( $path ) {
		return '/' === substr( $path, -1 );
	}

	/**
	 * Checks if it is valid file, and it is allowed.
	 *
	 * @param $path_name
	 *
	 * @return bool
	 * @since 2.5.0
	 */
	private function is_file_allowed( $path_name ) {
		$check = $this->directory . $path_name;

		if ( ! file_exists( $check ) ) {
			return false;
		}

		if ( $this->is_path_dir( $path_name ) ) {
			return is_dir( $check );
		}

		return true;
	}

	/**
	 * Is icon set.
	 * Validate that the current uploaded zip is in this icon set format.
	 *
	 * @return bool
	 * @since 2.5.0
	 */
	public function is_icon_set() {
		foreach ( $this->allowed_zipped_files as $file ) {
			if ( ! $this->is_file_allowed( $file ) ) {
				return false;
			}
		}

		return true;
	}

	public function is_valid() {
		return false;
	}

	protected function get_display_prefix() {
		return '';
	}

	protected function get_prefix() {
		return '';
	}

	public function handle_new_icon_set() {
		return $this->prepare();
	}

	/**
	 * Cleans the Temp files.
	 *
	 * @param WP_Filesystem_Base $wp_filesystem
	 * @since 2.5.0
	 */
	protected function cleanup_temp_files( $wp_filesystem ) {
		$wp_filesystem->rmdir( $this->directory, true );
	}

	/**
	 * Gets the URL to uploaded file.
	 *
	 * @param $file_name
	 *
	 * @return string
	 * @since 2.5.0
	 */
	protected function get_file_url( $file_name ) {
		$wp_upload_dir = wp_upload_dir();

		return $wp_upload_dir['baseurl'] . '/elementor/custom-icons/' . $file_name;
	}

	protected function get_icon_sets_dir() {
		$wp_upload_dir = wp_upload_dir();
		$path          = $wp_upload_dir['basedir'] . '/elementor/custom-icons';

		$this->get_ensure_upload_dir( $path );
		return $path;
	}

	/**
	 * Ensures that there is a directory to move the icon set package in it.
	 * It creates a .htaccess file and index.php file for security reasons.
	 *
	 * @param $path
	 *
	 * @return mixed
	 * @since 2.5.0
	 */
	public static function get_ensure_upload_dir( $path ) {
		if ( file_exists( $path . '/index.php' ) ) {
			return $path;
		}

		wp_mkdir_p( $path );

		$files = [
			[
				'file' => 'index.php',
				'content' => [
					'<?php',
					'// Silence is golden.',
				],
			],
			[
				'file' => '.htaccess',
				'content' => [
					'Options -Indexes',
					'<ifModule mod_headers.c>',
					'	<Files *.*>',
					'       Header set Content-Disposition attachment',
					'	</Files>',
					'</IfModule>',
				],
			],
		];

		foreach ( $files as $file ) {
			if ( ! file_exists( trailingslashit( $path ) . $file['file'] ) ) {
				$content       = implode( PHP_EOL, $file['content'] );
				$wp_filesystem = self::get_wp_filesystem();

				$wp_filesystem->put_contents( trailingslashit( $path ) . $file['file'], $content );
			}
		}

		return $path;
	}

	protected function get_upload_dir( $dir = '' ) {
		$path = $this->get_icon_sets_dir();
		if ( ! empty( $dir ) ) {
			$path .= '/' . $dir;
		}

		return $path;
	}

	public function move_files() {
		$wp_filesystem = $this->get_wp_filesystem();
		$to            = $this->get_upload_dir( $this->dir_name ) . DIRECTORY_SEPARATOR;

		foreach ( $wp_filesystem->dirlist( $this->directory, false, true ) as $file ) {
			$full_path = $this->directory . $file['name'];

			if ( $wp_filesystem->is_dir( $full_path ) ) {
				$wp_filesystem->mkdir( $to );
				$wp_filesystem->mkdir( $to . $file['name'] );

				foreach ( $file['files'] as $filename => $sub_file ) {
					$new_path = $to . $file['name'] . DIRECTORY_SEPARATOR . $filename;
					$wp_filesystem->move( $full_path . DIRECTORY_SEPARATOR . $filename, $new_path );
				}
			} else {
				$new_path = $to . $file['name'];
				$wp_filesystem->move( $full_path, $new_path );
			}
		}

		$this->cleanup_temp_files( $wp_filesystem );
		$this->directory = $to;

		return $to;
	}

	/**
	 * Returns a unique name for directory of icon set.
	 *
	 * @return string
	 * @since 2.5.0
	 */
	public function get_unique_name() {
		$name     = $this->get_name();
		$basename = $name;
		$counter  = 1;

		while ( ! $this->is_name_unique( $name ) ) {
			$name = $basename . '-' . $counter;
			$counter++;
		}

		return $name;
	}

	/**
	 * Checks if the name of the directory of the icon set is unique.
	 *
	 * @param $name
	 *
	 * @return bool
	 * @since 2.5.0
	 */
	private function is_name_unique( $name ) {
		return ! is_dir( $this->get_icon_sets_dir() . '/' . $name );
	}

	protected function get_url( $filename = '' ) {
		return $this->get_file_url( $this->dir_name . $filename );
	}

	protected function get_stylesheet() {
		return '';
	}

	protected function get_version() {
		return '1.0.0';
	}

	protected function get_enqueue() {
		return false;
	}

	/**
	 * Build configurations of the custom fonts to save them.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public function build_config() {
		$icon_set_config = [
			'name' => $this->dir_name,
			'label' => ucwords( str_replace( [ '-', '_' ], ' ', $this->dir_name ) ),
			'url' => $this->get_stylesheet(),
			'enqueue' => $this->get_enqueue(),
			'prefix' => $this->get_prefix(),
			'displayPrefix' => $this->get_display_prefix(),
			'ver' => $this->get_version(),
			'custom_icon_type' => $this->get_type(),
		];

		$icons                    = $this->extract_icon_list();
		$icon_set_config['count'] = count( $icons );
		$icon_set_config['icons'] = $icons;

		return $icon_set_config;
	}

	public static function _unstable_file_get_contents( $file, ...$args ) {
		if ( ! is_file( $file ) || ! is_readable( $file ) ) {
			return false;
		}

		//phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return file_get_contents( $file, ...$args );
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
	 * Icon Set Base constructor.
	 *
	 * @param $directory
	 * @since 2.5.0
	 */
	public function __construct( $directory ) {
		$this->directory = $directory;

		return $this->is_icon_set() ? $this : false;
	}
}
