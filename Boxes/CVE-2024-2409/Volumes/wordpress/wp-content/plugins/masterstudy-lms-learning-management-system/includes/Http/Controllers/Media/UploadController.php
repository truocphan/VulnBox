<?php

namespace MasterStudy\Lms\Http\Controllers\Media;

use MasterStudy\Lms\Plugin\Media;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

class UploadController {
	public function __invoke( WP_REST_Request $request ) {
		$extensions = implode( ';', array_keys( apply_filters( 'upload_mimes', Media::MIMES ) ) );
		$validator  = new Validator(
			$request->get_file_params(),
			array(
				'file' => 'required|extension,' . str_replace( '|', ';', $extensions ),
			)
		);

		if ( $validator->fails() ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'media_upload_validation_error',
					'errors'     => $validator->get_errors_array(),
				),
				422
			);
		}

		return ( new \WP_REST_Attachments_Controller( 'attachment' ) )->create_item( $request );
	}
}
