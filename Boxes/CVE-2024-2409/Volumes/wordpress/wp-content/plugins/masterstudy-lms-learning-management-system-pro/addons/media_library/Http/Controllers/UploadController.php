<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\media_library\Http\Serializer\AttachmentSerializer;
use MasterStudy\Lms\Pro\addons\media_library\MediaStorage;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

final class UploadController {
	public function __invoke( WP_REST_Request $request ): WP_REST_Response {
		do_action( 'stm_lms_upload_files' );

		// @todo validate mimi type and file size
		$validator = new Validator(
			$request->get_file_params(),
			array(
				'file' => 'required_file|extension,' . implode( ';', MediaStorage::allowed_extensions() ),
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$file = $request->get_file_params()['file'];

		// @todo move this to Validator
		$max_file_size = MediaStorage::max_upload_size();
		$filesize      = isset( $file['size'] ) ? $file['size'] : filesize( $file['tmp_name'] );

		if ( $filesize > $max_file_size ) {
			return WpResponseFactory::validation_failed(
				array(
					'file' => array(
						esc_html__( 'File is too large.', 'masterstudy-lms-learning-management-system-pro' ),
					),
				)
			);
		}

		$media_storage = new MediaStorage();

		$result = $media_storage->upload( $request->get_file_params()['file'] );

		if ( is_wp_error( $result ) ) {
			return WpResponseFactory::error(
				array(
					'error'   => true,
					'message' => $result->get_error_message(),
				)
			);
		}

		return new WP_REST_Response(
			array(
				'file' => ( new AttachmentSerializer() )->toArray( $result ),
			)
		);
	}
}
