<?php
/**
 * Abstract class controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;

class DataController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'datas'; // Cannot use data, since it throws Internal Server error in shared hosting environment.

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $object_type = 'data';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/countries',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_countries' ),
					'permission_callback' => 'is_user_logged_in',
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/states/(?P<country>[\w]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_states_by_country' ),
					'permission_callback' => 'is_user_logged_in',
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/states/',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_states' ),
					'permission_callback' => 'is_user_logged_in',
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/currencies',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_currencies' ),
					'permission_callback' => 'is_user_logged_in',
				),
			)
		);
	}

	/**
	 * Get countries list.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_countries( $request ) {
		$countries = array_map( 'html_entity_decode', masteriyo( 'countries' )->get_countries() );

		foreach ( $countries as $code => $name ) {
			$countries_arr[] = array(
				'code' => $code,
				'name' => $name,
			);
		}

		$response = rest_ensure_response( $countries_arr );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $countries, $request );
	}

	/**
	 * Get states list.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_states( $request ) {
		$countries = array_keys( masteriyo( 'countries' )->get_countries() );

		foreach ( $countries as $country ) {
			$states = masteriyo( 'countries' )->get_states( $country );

			if ( empty( $states ) ) {
				continue;
			}

			$states_list = array();
			foreach ( $states as $state_code => $state_name ) {
				$states_list[] = array(
					'code' => $state_code,
					'name' => $state_name,
				);
			}

			$states_arr[] = array(
				'country' => $country,
				'states'  => $states_list,
			);
		}

		$response = rest_ensure_response( $states_arr );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $states, $request );
	}

	/**
	 * Get states list by country.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_states_by_country( $request ) {
		if ( isset( $request['country'] ) ) {
			$country = masteriyo_strtoupper( $request['country'] );
		}

		$countries = masteriyo( 'countries' )->get_countries();

		if ( ! isset( $countries[ $country ] ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_country_code',
				__( 'Invalid country code.', 'masteriyo' ),
				array( 'status' => '404' )
			);
		}

		$states = masteriyo( 'countries' )->get_states( $country );

		$states_list = array();
		if ( ! empty( $states ) ) {
			foreach ( $states as $state_code => $state_name ) {
				$states_list[] = array(
					'code' => $state_code,
					'name' => $state_name,
				);
			}
		}

		$states = array(
			'country' => $country,
			'states'  => $states_list,
		);

		$response = rest_ensure_response( $states );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $states, $request );
	}

	/**
	 * Get currencies list.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_currencies( $request ) {
		$currencies = masteriyo_get_currencies();

		foreach ( $currencies as $code => $name ) {
			$currencies_arr[] = array(
				'code'   => $code,
				'name'   => html_entity_decode( $name ),
				'symbol' => html_entity_decode( masteriyo_get_currency_symbol( $code ) ),
			);
		}

		$response = rest_ensure_response( $currencies_arr );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $currencies, $request );
	}
}
