<?php

namespace MasterStudy\Lms\Pro\addons\scorm;

use STM_LMS_Scorm_Packages;
use WP_Error;

final class Uploader {

	/**
	 * @param array $file $_FILES['file']
	 */
	public static function upload( array $file ) {

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$upload_dir_filter = function ( $upload_dir ) {
			$subdir = '/masterstudy-lms/scorm';
			return array_merge(
				$upload_dir,
				array(
					'subdir' => $subdir,
					'path'   => $upload_dir['basedir'] . $subdir,
					'url'    => $upload_dir['baseurl'] . $subdir,
				)
			);
		};

		add_filter( 'upload_dir', $upload_dir_filter );
		$result = wp_handle_upload(
			$file,
			array(
				'test_form'                => false,
				'test_size'                => true,
				'test_type'                => true,
				'mimes'                    => array(
					'zip' => 'application/zip',
				),
				'unique_filename_callback' => function ( $dir, $name, $ext ) {
					return md5( time() ) . $name;
				},
			)
		);
		remove_filter( 'upload_dir', $upload_dir_filter );

		if ( ! empty( $result['error'] ) ) {
			return new WP_Error( 'upload_error', $result['error'] );
		}

		// need for compatibility
		$result['path'] = $result['file'];

		$result = STM_LMS_Scorm_Packages::unzip_scorm_file( $result, basename( $result['file'] ) );

		if ( ! empty( $result['error'] ) ) {
			return new WP_Error( 'scorm_unzip', $result['error'] );
		}

		return $result;
	}
}
