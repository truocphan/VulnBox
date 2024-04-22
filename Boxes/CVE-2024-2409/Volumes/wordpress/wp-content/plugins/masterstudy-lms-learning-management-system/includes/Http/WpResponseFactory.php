<?php

namespace MasterStudy\Lms\Http;

use WP_REST_Response;

final class WpResponseFactory {
	public static function not_found(): WP_REST_Response {
		return self::create(
			404,
			array(
				'error_code' => 'not_found',
				'message'    => 'Not Found!',
			)
		);
	}

	public static function forbidden(): WP_REST_Response {
		return self::create(
			403,
			array(
				'error_code' => 'forbidden',
				'message'    => 'Forbidden!',
			)
		);
	}

	public static function created( array $data ): WP_REST_Response {
		return self::create( 201, $data );
	}

	public static function validation_failed( array $errors ): WP_REST_Response {
		return self::create(
			422,
			array(
				'error_code' => 'validation_error',
				'errors'     => $errors,
			)
		);
	}

	public static function ok(): WP_REST_Response {
		return self::create( 200, array( 'status' => 'ok' ) );
	}

	public static function create( int $status, array $data ): WP_REST_Response {
		return new WP_REST_Response( $data, $status );
	}

	public static function error( string $message ): WP_REST_Response {
		return self::create(
			500,
			array(
				'error_code' => 'internal_error',
				'message'    => $message,
			)
		);
	}

	public static function bad_request( string $message ): WP_REST_Response {
		return self::create(
			400,
			array(
				'error_code' => 'bad_request',
				'message'    => $message,
			)
		);
	}
}
