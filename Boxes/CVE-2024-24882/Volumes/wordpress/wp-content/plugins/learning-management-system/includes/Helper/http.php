<?php
/**
 * HTTP helper functions.
 *
 * @since 1.5.5
 * @package Masteriyo\Helper
 */

if ( ! function_exists( 'masteriyo_current_http_request' ) ) {
	/**
	 * Return current HTTP request.
	 *
	 * @since 1.5.5
	 *
	 * @return WP_HTTP_Request
	 */
	function masteriyo_current_http_request() {
		global $wp_rest_server;

		if ( null === $wp_rest_server ) {
			return null;
		}

		// phpcs:disable
		if ( empty( $path ) ) {
			if ( isset( $_SERVER['PATH_INFO'] ) ) {
				$path = $_SERVER['PATH_INFO'];
			} else {
				$path = '/';
			}
		}

		$request = new \WP_REST_Request( $_SERVER['REQUEST_METHOD'], $path );

		$request->set_query_params( wp_unslash( $_GET ) );
		$request->set_body_params( wp_unslash( $_POST ) );
		$request->set_file_params( $_FILES );
		$request->set_headers( $wp_rest_server->get_headers( wp_unslash( $_SERVER ) ) );
		$request->set_body( \WP_REST_Server::get_raw_data() );

		// Initialize json params properly.
		$request->get_json_params();

		/*
		 * HTTP method override for clients that can't use PUT/PATCH/DELETE. First, we check
		 * $_GET['_method']. If that is not set, we check for the HTTP_X_HTTP_METHOD_OVERRIDE
		 * header.
		 */
		if ( isset( $_GET['_method'] ) ) {
			$request->set_method( $_GET['_method'] );
		} elseif ( isset( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ) {
			$request->set_method( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] );
		}

		// phpcs:enable
		return $request;
	}
}

/**
 * Retrieves an array of endpoint arguments from the item schema and endpoint method.
 *
 * Copy of the WP's rest_get_endpoint_args_for_schema() but has fixed to include custom
 * sanitize_callback and validate_callback
 *
 * @since 1.6.12
 *
 * @param array  $schema The full JSON schema for the endpoint.
 * @param string $method Optional. HTTP method of the endpoint. The arguments for `CREATABLE` endpoints are
 *                       checked for required values and may fall-back to a given default, this is not done
 *                       on `EDITABLE` endpoints. Default WP_REST_Server::CREATABLE.
 * @return array The endpoint arguments.
 */
function masteriyo_rest_get_endpoint_args_for_schema( $schema, $method = WP_REST_Server::CREATABLE ) {

	$schema_properties       = ! empty( $schema['properties'] ) ? $schema['properties'] : array();
	$endpoint_args           = array();
	$valid_schema_properties = rest_get_allowed_schema_keywords();
	$valid_schema_properties = array_diff( $valid_schema_properties, array( 'default', 'required' ) );

	foreach ( $schema_properties as $field_id => $params ) {

		// Arguments specified as `readonly` are not allowed to be set.
		if ( ! empty( $params['readonly'] ) ) {
			continue;
		}

		$endpoint_args[ $field_id ] = array(
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'rest_sanitize_request_arg',
		);

		if ( isset( $params['validate_callback'] ) && is_callable( $params['validate_callback'] ) ) {
			$endpoint_args[ $field_id ]['validate_callback'] = $params['validate_callback'];
		}

		if ( isset( $params['sanitize_callback'] ) && is_callable( $params['sanitize_callback'] ) ) {
			$endpoint_args[ $field_id ]['sanitize_callback'] = $params['sanitize_callback'];
		}

		if ( WP_REST_Server::CREATABLE === $method && isset( $params['default'] ) ) {
			$endpoint_args[ $field_id ]['default'] = $params['default'];
		}

		if ( WP_REST_Server::CREATABLE === $method && ! empty( $params['required'] ) ) {
			$endpoint_args[ $field_id ]['required'] = true;
		}

		foreach ( $valid_schema_properties as $schema_prop ) {
			if ( isset( $params[ $schema_prop ] ) ) {
				$endpoint_args[ $field_id ][ $schema_prop ] = $params[ $schema_prop ];
			}
		}

		// Merge in any options provided by the schema property.
		if ( isset( $params['arg_options'] ) ) {

			// Only use required / default from arg_options on CREATABLE endpoints.
			if ( WP_REST_Server::CREATABLE !== $method ) {
				$params['arg_options'] = array_diff_key(
					$params['arg_options'],
					array(
						'required' => '',
						'default'  => '',
					)
				);
			}

			$endpoint_args[ $field_id ] = array_merge( $endpoint_args[ $field_id ], $params['arg_options'] );
		}
	}

	return $endpoint_args;
}
