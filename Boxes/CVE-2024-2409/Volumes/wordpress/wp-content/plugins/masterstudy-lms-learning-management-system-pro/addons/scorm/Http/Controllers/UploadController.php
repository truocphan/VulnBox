<?php

namespace MasterStudy\Lms\Pro\addons\scorm\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Pro\addons\scorm\Uploader;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class UploadController {
	public function __invoke( int $course_id, WP_REST_Request $request ) {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_file_params(),
			array(
				'file' => 'required',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$result = Uploader::upload( $request->get_file_params()['file'] );

		if ( is_wp_error( $result ) ) {
			return WpResponseFactory::bad_request( $result->get_error_message() );
		}

		$scorm = array(
			'error'         => '',
			'path'          => sanitize_text_field( $result['path'] ),
			'url'           => sanitize_text_field( $result['url'] ),
			'scorm_version' => sanitize_text_field( $result['scorm_version'] ),
		);

		$update = update_post_meta( $course_id, 'scorm_package', wp_json_encode( $scorm ) );

		if ( ! $update ) {
			return WpResponseFactory::bad_request( 'Failed to save scorm package' );
		}

		return WpResponseFactory::created( $scorm );
	}
}
