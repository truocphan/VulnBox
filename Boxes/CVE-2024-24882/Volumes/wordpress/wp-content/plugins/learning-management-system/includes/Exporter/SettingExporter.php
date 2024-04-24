<?php
/**
 * Setting exporter class.
 *
 * @since 1.6.14
 * @package Masteriyo\Exporter
 */

namespace Masteriyo\Exporter;

defined( 'ABSPATH' ) || exit;

use ZipArchive;

/**
 * Export class.
 *
 * @since 1.6.14
 */
class SettingExporter {


	/**
	 * Export data.
	 *
	 *
	 * @since 1.6.14
	 * @return \WP_Error|array Array of data (filename, download_url) on success else WP_Error on failure.
	 */
	public function export() {
		wp_raise_memory_limit( 'admin' );

		$export_file = $this->create_export_file();

		if ( false === $export_file ) {
			return new \WP_Error(
				'export_error',
				__( 'Unable to create export file.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$meta_data = $this->get_export_meta_data();

		$data = array_merge(
			array(
				'manifest' => $meta_data,
			),
			masteriyo_get_setting( '' )
		);

		$this->write( $export_file['filepath'], $data );

		return $export_file;
	}

	/**
	 * Write.
	 *
	 * @since 1.6.14
	 *
	 * @param string $filepath
	 * @param array $contents
	 */
	protected function write( $filepath, $contents ) {
		$filesystem = masteriyo_get_filesystem();

		if ( $filesystem ) {
			$filesystem->put_contents( $filepath, wp_json_encode( $contents ) );
		}
	}

	/**
	 * Create and return export file path.
	 *
	 * @since 1.6.14
	 * @return bool|array Array of data on success or false on failure.
	 */
	protected function create_export_file() {
		$upload_dir = wp_upload_dir();
		$filesystem = masteriyo_get_filesystem();

		if ( ! $filesystem ) {
			return false;
		}

		if ( ! $filesystem->is_dir( $upload_dir['basedir'] . '/masteriyo' ) ) {
			$filesystem->mkdir( $upload_dir['basedir'] . '/masteriyo' );
		}

		$export_files = $filesystem->dirlist( $upload_dir['basedir'] . '/masteriyo' );

		// Remove old export file.
		foreach ( $export_files as $file ) {
			$prefix = sprintf( 'masteriyo-export-settings-%s-', get_current_user_id() );
			if ( masteriyo_starts_with( $file['name'], $prefix ) ) {
				$filesystem->delete( $upload_dir['basedir'] . '/masteriyo/' . $file['name'] );
			}
		}

		$filename = sprintf( 'masteriyo-export-settings-%s-%s.json', get_current_user_id(), gmdate( 'Y-m-d-H-i-s' ) );
		$filepath = $upload_dir['basedir'] . '/masteriyo/' . $filename;

		if ( ! $filesystem->touch( $filepath ) ) {
			return false;
		}

		return array(
			'filepath'     => $filepath,
			'filename'     => $filename,
			'download_url' => $upload_dir['baseurl'] . '/masteriyo/' . $filename,
		);
	}

	/**
	 * Return export meta data.
	 *
	 * @since 1.6.14
	 *
	 * @return array
	 */
	protected function get_export_meta_data() {
		return array(
			'version'    => masteriyo_get_version(),
			'created_at' => gmdate( 'D, d M Y H:i:s +0000' ),
			'base_url'   => home_url(),
		);
	}
}
