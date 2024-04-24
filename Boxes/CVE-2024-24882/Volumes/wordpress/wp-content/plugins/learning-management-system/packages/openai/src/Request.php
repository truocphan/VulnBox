<?php
/**
 * Request class for sending HTTP requests.
 *
 * @since 1.6.15
 *
 * @package Masteriyo\packages\OpenAI
 */

namespace ThemeGrill\OpenAI;

use WP_Error;

/**
 * Request class for sending HTTP requests.
 *
 * @since 1.6.15
 */
class Request {
	/**
	 * Send an HTTP request.
	 *
	 * @param string $url     Request URL.
	 * @param string $method  Request method.
	 * @param array  $body    Request body.
	 * @param array  $headers Request headers.
	 * @param array  $args    Request args.
	 *
	 * @return string|WP_Error Response or WP_Error object.
	 */
	public static function send( $url, $method = 'GET', $body = array(), $headers = array(), $args = array() ) {
		$headers = array_merge(
			$headers,
			array(
				'Content-Type' => 'application/json',
			)
		);

		$args = array_merge(
			$args,
			array(
				'method'  => $method,
				'body'    => wp_json_encode( $body ),
				'headers' => $headers,
				'timeout' => 30,
			)
		);

		$response = wp_remote_request( $url, $args );

		//If there is an error in the request, return a WP_Error
		if ( is_wp_error( $response ) ) {
			return self::handle_wp_error( $response );
		}

		//Get the response code and body
		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		//If the response code is not 200, handle the HTTP error
		if ( 200 !== $code ) {
			return self::handle_http_error( $code, $body );
		}

		return $body;
	}

	/**
	 * Handle WP_Error.
	 *
	 * Creates a new WP_Error object from the provided error object.
	 *
	 * @param WP_Error $error The WP_Error object to handle.
	 *
	 * @return WP_Error The new WP_Error object with updated error message.
	 */
	private static function handle_wp_error( $error ) {
		$error_message = $error->get_error_message();

		if ( mb_stristr( $error_message, 'curl error 28' ) ) {
			$error_message .= '. ' . esc_html__( 'You might have tried to generate too much content. Please try again with a maximum limit. If the issue persists, please check your server or service status.', 'masteriyo' );
		}

		return new WP_Error( 'wp_error', $error_message );
	}

	/**
	 * Handle HTTP Error.
	 *
	 * Creates a new WP_Error object from the provided HTTP error code and response body.
	 *
	 * @param int    $code The HTTP error code.
	 * @param string $body The response body with error details in JSON format.
	 *
	 * @return WP_Error The new WP_Error object with the error message from the response body.
	 */
	private static function handle_http_error( $code, $body ) {
			$body          = json_decode( $body, true );
			$error_message = 'Unknown error occurred.';

		if ( is_array( $body ) && isset( $body['error']['message'] ) ) {
				$error_message = $body['error']['message'];
		}

			return new WP_Error( $code, $error_message );
	}
}
