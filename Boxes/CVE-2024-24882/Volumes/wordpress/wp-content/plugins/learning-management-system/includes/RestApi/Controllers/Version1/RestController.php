<?php
/**
 * REST Controller
 *
 * This class extend `WP_REST_Controller` in order to include /batch endpoint
 * for almost all endpoints in Masteriyo REST API.
 *
 * It's required to follow "Controller Classes" guide before extending this class:
 * <https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/>
 *
 * NOTE THAT ONLY CODE RELEVANT FOR MOST ENDPOINTS SHOULD BE INCLUDED INTO THIS CLASS.
 * If necessary extend this class and create new abstract classes like `CrudController` or `TermsController`.
 *
 * @class   RestController
 * @package Masteriyo/RestApi
 * @see     https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Rest Controller Class
 *
 * @package Masteriyo/RestApi
 * @extends  WP_REST_Controller
 * @version  1.0.0
 */
abstract class RestController extends \WP_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $rest_base = '';

	/**
	 * Add the schema from additional fields to an schema array.
	 *
	 * The type of object is inferred from the passed schema.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Schema array.
	 *
	 * @return array
	 */
	protected function add_additional_fields_schema( $schema ) {
		if ( empty( $schema['title'] ) ) {
			return $schema;
		}

		/**
		 * Can't use $this->get_object_type otherwise we cause an inf loop.
		 */
		$object_type = $schema['title'];

		$additional_fields = $this->get_additional_fields( $object_type );

		foreach ( $additional_fields as $field_name => $field_options ) {
			if ( ! $field_options['schema'] ) {
				continue;
			}

			$schema['properties'][ $field_name ] = $field_options['schema'];
		}

		/**
		 * Filters additional fields schema of a REST API.
		 *
		 * @since 1.0.0
		 * @since 1.5.5 Added $this parameter which stores the current REST Controller
		 *
		 * @param array $schema The additional fields schema.
		 */
		$schema['properties'] = apply_filters( 'masteriyo_rest_' . $object_type . '_schema', $schema['properties'], $this );

		return $schema;
	}

	/**
	 * Compatibility functions for WP 5.5, since custom types are not supported anymore.
	 * See @link https://core.trac.wordpress.org/changeset/48306
	 *
	 * @since 1.0.0
	 *
	 * @param string $method Optional. HTTP method of the request.
	 *
	 * @return array Endpoint arguments.
	 */
	public function get_endpoint_args_for_item_schema( $method = \WP_REST_Server::CREATABLE ) {
		$endpoint_args = masteriyo_rest_get_endpoint_args_for_schema( $this->get_item_schema(), $method );

		if ( false === strpos( \WP_REST_Server::EDITABLE, $method ) ) {
			return $endpoint_args;
		}

		$endpoint_args = $this->adjust_wp_5_5_datatype_compatibility( $endpoint_args );

		return $endpoint_args;
	}

	/**
	 * Change datatype `date-time` to string, and `mixed` to composite of all built in types. This is required for maintaining forward compatibility with WP 5.5 since custom post types are not supported anymore.
	 *
	 * See @link https://core.trac.wordpress.org/changeset/48306
	 *
	 * We still use the 'mixed' type, since if we convert to composite type everywhere, it won't work in 5.4 anymore because they require to define the full schema.
	 *
	 * @since 1.0.0
	 *
	 * @param array $endpoint_args Schema with datatype to convert.

	 * @return mixed Schema with converted datatype.
	 */
	protected function adjust_wp_5_5_datatype_compatibility( $endpoint_args ) {
		if ( version_compare( get_bloginfo( 'version' ), '5.5', '<' ) ) {
			return $endpoint_args;
		}

		foreach ( $endpoint_args as $field_id => $params ) {

			if ( ! isset( $params['type'] ) ) {
				continue;
			}

			/**
			 * Custom types are not supported as of WP 5.5, this translates type => 'date-time' to type => 'string'.
			 */
			if ( 'date-time' === $params['type'] ) {
				$params['type'] = array( 'null', 'string' );
			}

			/**
			 * WARNING: Order of fields here is important, types of fields are ordered from most specific to least specific as perceived by core's built-in type validation methods.
			 */
			if ( 'mixed' === $params['type'] ) {
				$params['type'] = array( 'null', 'object', 'string', 'number', 'boolean', 'integer', 'array' );
			}

			if ( isset( $params['properties'] ) ) {
				$params['properties'] = $this->adjust_wp_5_5_datatype_compatibility( $params['properties'] );
			}

			if ( isset( $params['items'] ) && isset( $params['items']['properties'] ) ) {
				$params['items']['properties'] = $this->adjust_wp_5_5_datatype_compatibility( $params['items']['properties'] );
			}

			$endpoint_args[ $field_id ] = $params;
		}
		return $endpoint_args;
	}

	/**
	 * Get normalized rest base.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_normalized_rest_base() {
		return preg_replace( '/\(.*\)\//i', '', $this->rest_base );
	}

	/**
	 * Check batch limit.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Request items.
	 * @return bool|WP_Error
	 */
	protected function check_batch_limit( $items ) {
		/**
		 * Filters batch items limit.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $limit The items limit.
		 * @param string $rest_base The normalized rest base.
		 */
		$limit = apply_filters( 'masteriyo_rest_batch_items_limit', 100, $this->get_normalized_rest_base() );
		$total = 0;

		if ( ! empty( $items['create'] ) ) {
			$total += count( $items['create'] );
		}

		if ( ! empty( $items['update'] ) ) {
			$total += count( $items['update'] );
		}

		if ( ! empty( $items['delete'] ) ) {
			$total += count( $items['delete'] );
		}

		if ( $total > $limit ) {
			/* translators: %s: items limit */
			return new \WP_Error( 'masteriyo_rest_request_entity_too_large', sprintf( __( 'Unable to accept more than %s items for this request.', 'masteriyo' ), $limit ), array( 'status' => 413 ) );
		}

		return true;
	}

	/**
	 * Bulk create, update and delete items.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array Of WP_Error or WP_REST_Response.
	 */
	public function batch_items( $request ) {
		/**
		 * REST Server
		 *
		 * @var WP_REST_Server $wp_rest_server
		 */
		global $wp_rest_server;

		// Get the request params.
		$items    = array_filter( $request->get_params() );
		$query    = $request->get_query_params();
		$response = array();

		// Check batch limit.
		$limit = $this->check_batch_limit( $items );
		if ( is_wp_error( $limit ) ) {
			return $limit;
		}

		if ( ! empty( $items['create'] ) ) {
			foreach ( $items['create'] as $item ) {
				$_item = new \WP_REST_Request( 'POST' );

				// Default parameters.
				$defaults = array();
				$schema   = $this->get_public_item_schema();
				foreach ( $schema['properties'] as $arg => $options ) {
					if ( isset( $options['default'] ) ) {
						$defaults[ $arg ] = $options['default'];
					}
				}
				$_item->set_default_params( $defaults );

				// Set request parameters.
				$_item->set_body_params( $item );

				// Set query (GET) parameters.
				$_item->set_query_params( $query );

				$_response = $this->create_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['create'][] = array(
						'id'    => 0,
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['create'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		if ( ! empty( $items['update'] ) ) {
			foreach ( $items['update'] as $item ) {
				$_item = new \WP_REST_Request( 'PUT' );
				$_item->set_body_params( $item );
				$_response = $this->update_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['update'][] = array(
						'id'    => $item['id'],
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['update'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		if ( ! empty( $items['delete'] ) ) {
			foreach ( $items['delete'] as $id ) {
				$id = (int) $id;

				if ( 0 === $id ) {
					continue;
				}

				$_item = new \WP_REST_Request( 'DELETE' );
				$_item->set_query_params(
					array(
						'id'    => $id,
						'force' => true,
					)
				);
				$_response = $this->delete_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['delete'][] = array(
						'id'    => $id,
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['delete'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		return $response;
	}

	/**
	 * Validate a text value for a text based setting.
	 *
	 * @since 1.0.0
	 * @param string $value Value.
	 * @param array  $setting Setting.
	 * @return string
	 */
	public function validate_setting_text_field( $value, $setting ) {
		$value = is_null( $value ) ? '' : $value;
		return wp_kses_post( trim( stripslashes( $value ) ) );
	}

	/**
	 * Validate select based settings.
	 *
	 * @since 1.0.0
	 * @param string $value Value.
	 * @param array  $setting Setting.
	 * @return string|WP_Error
	 */
	public function validate_setting_select_field( $value, $setting ) {
		if ( array_key_exists( $value, $setting['options'] ) ) {
			return $value;
		} else {
			return new \WP_Error( 'rest_setting_value_invalid', __( 'An invalid setting value was passed.', 'masteriyo' ), array( 'status' => 400 ) );
		}
	}

	/**
	 * Validate multiselect based settings.
	 *
	 * @since 1.0.0
	 * @param array $values Values.
	 * @param array $setting Setting.
	 * @return array|WP_Error
	 */
	public function validate_setting_multiselect_field( $values, $setting ) {
		if ( empty( $values ) ) {
			return array();
		}

		if ( ! is_array( $values ) ) {
			return new \WP_Error( 'rest_setting_value_invalid', __( 'An invalid setting value was passed.', 'masteriyo' ), array( 'status' => 400 ) );
		}

		$final_values = array();
		foreach ( $values as $value ) {
			if ( array_key_exists( $value, $setting['options'] ) ) {
				$final_values[] = $value;
			}
		}

		return $final_values;
	}

	/**
	 * Validate image_width based settings.
	 *
	 * @since 1.0.0
	 * @param array $values Values.
	 * @param array $setting Setting.
	 * @return string|WP_Error
	 */
	public function validate_setting_image_width_field( $values, $setting ) {
		if ( ! is_array( $values ) ) {
			return new \WP_Error( 'rest_setting_value_invalid', __( 'An invalid setting value was passed.', 'masteriyo' ), array( 'status' => 400 ) );
		}

		$current = $setting['value'];
		if ( isset( $values['width'] ) ) {
			$current['width'] = (int) $values['width'];
		}
		if ( isset( $values['height'] ) ) {
			$current['height'] = (int) $values['height'];
		}
		if ( isset( $values['crop'] ) ) {
			$current['crop'] = (bool) $values['crop'];
		}
		return $current;
	}

	/**
	 * Validate radio based settings.
	 *
	 * @since 1.0.0
	 * @param string $value Value.
	 * @param array  $setting Setting.
	 * @return string|WP_Error
	 */
	public function validate_setting_radio_field( $value, $setting ) {
		return $this->validate_setting_select_field( $value, $setting );
	}

	/**
	 * Validate checkbox based settings.
	 *
	 * @since 1.0.0
	 * @param string $value Value.
	 * @param array  $setting Setting.
	 * @return string|WP_Error
	 */
	public function validate_setting_checkbox_field( $value, $setting ) {
		if ( in_array( $value, array( 'yes', 'no' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return $value;
		} elseif ( empty( $value ) ) {
			$value = isset( $setting['default'] ) ? $setting['default'] : 'no';
			return $value;
		} else {
			return new \WP_Error( 'rest_setting_value_invalid', __( 'An invalid setting value was passed.', 'masteriyo' ), array( 'status' => 400 ) );
		}
	}

	/**
	 * Validate textarea based settings.
	 *
	 * @since 1.0.0
	 * @param string $value Value.
	 * @param array  $setting Setting.
	 * @return string
	 */
	public function validate_setting_textarea_field( $value, $setting ) {
		$value = is_null( $value ) ? '' : $value;
		return wp_kses(
			trim( stripslashes( $value ) ),
			array_merge(
				array(
					'iframe' => array(
						'src'   => true,
						'style' => true,
						'id'    => true,
						'class' => true,
					),
				),
				wp_kses_allowed_html( 'post' )
			)
		);
	}

	/**
	 * Add meta query.
	 *
	 * @since 1.0.0
	 * @param array $args       Query args.
	 * @param array $meta_query Meta query.
	 * @return array
	 */
	protected function add_meta_query( $args, $meta_query ) {
		if ( empty( $args['meta_query'] ) ) {
			$args['meta_query'] = array();
		}

		$args['meta_query'][] = $meta_query;

		return $args['meta_query'];
	}

	/**
	 * Get the batch schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_public_batch_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'batch',
			'type'       => 'object',
			'properties' => array(
				'create' => array(
					'description' => __( 'List of created resources.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'object',
					),
				),
				'update' => array(
					'description' => __( 'List of updated resources.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'object',
					),
				),
				'delete' => array(
					'description' => __( 'List of delete resources.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'integer',
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Gets an array of fields to be included on the response.
	 *
	 * Included fields are based on item schema and `_fields=` request argument.
	 * Updated from WordPress 5.3, included into this class to support old versions.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array Fields to be included in the response.
	 */
	public function get_fields_for_response( $request ) {
		$schema     = $this->get_item_schema();
		$properties = isset( $schema['properties'] ) ? $schema['properties'] : array();

		$additional_fields = $this->get_additional_fields();
		foreach ( $additional_fields as $field_name => $field_options ) {
			// For back-compat, include any field with an empty schema
			// because it won't be present in $this->get_item_schema().
			if ( is_null( $field_options['schema'] ) ) {
				$properties[ $field_name ] = $field_options;
			}
		}

		// Exclude fields that specify a different context than the request context.
		$context = isset( $request['context'] ) ? $request['context'] : '';
		if ( $context ) {
			foreach ( $properties as $name => $options ) {
				if ( ! empty( $options['context'] ) && ! in_array( $context, $options['context'], true ) ) {
					unset( $properties[ $name ] );
				}
			}
		}

		$fields = array_keys( $properties );

		if ( ! isset( $request['_fields'] ) ) {
			return $fields;
		}
		$requested_fields = wp_parse_list( $request['_fields'] );
		if ( 0 === count( $requested_fields ) ) {
			return $fields;
		}
		// Trim off outside whitespace from the comma delimited list.
		$requested_fields = array_map( 'trim', $requested_fields );
		// Always persist 'id', because it can be needed for add_additional_fields_to_object().
		if ( in_array( 'id', $fields, true ) ) {
			$requested_fields[] = 'id';
		}
		// Return the list of all requested fields which appear in the schema.
		return array_reduce(
			$requested_fields,
			function( $response_fields, $field ) use ( $fields ) {
				if ( in_array( $field, $fields, true ) ) {
					$response_fields[] = $field;
					return $response_fields;
				}
				// Check for nested fields if $field is not a direct match.
				$nested_fields = explode( '.', $field );
				// A nested field is included so long as its top-level property is
				// present in the schema.
				if ( in_array( $nested_fields[0], $fields, true ) ) {
					$response_fields[] = $field;
				}
				return $response_fields;
			},
			array()
		);
	}

	/**
	 * Handle namespace and rest_base properties access.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		if ( 'namespace' === $property ) {
			return $this->namespace;
		} elseif ( 'rest_base' === $property ) {
			return $this->rest_base;
		}
	}

	/**
	 * Retrieves an array of endpoint arguments from the item schema and endpoint method.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $schema The full JSON schema for the endpoint.
	 * @param string $method Optional. HTTP method of the endpoint. The arguments for `CREATABLE` endpoints are
	 *                       checked for required values and may fall-back to a given default, this is not done
	 *                       on `EDITABLE` endpoints. Default \WP_REST_Server::CREATABLE.
	 * @return array The endpoint arguments.
	 */
	protected function get_endpoint_args_for_schema( $schema, $method = \WP_REST_Server::CREATABLE ) {

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

			if ( \WP_REST_Server::CREATABLE === $method && isset( $params['default'] ) ) {
				$endpoint_args[ $field_id ]['default'] = $params['default'];
			}

			if ( \WP_REST_Server::CREATABLE === $method && ! empty( $params['required'] ) ) {
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
				if ( \WP_REST_Server::CREATABLE !== $method ) {
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
}
