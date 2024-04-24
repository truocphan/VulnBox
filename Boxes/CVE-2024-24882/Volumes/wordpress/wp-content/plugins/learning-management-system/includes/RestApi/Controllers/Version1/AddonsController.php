<?php
/**
 * Addons rest API controller.
 *
 * @since 1.6.11
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use Masteriyo\Pro\Addons;
use Masteriyo\Enums\AddonStatus;

class AddonsController extends RestController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.6.11
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/pro/v1';

	/**
	 * Route base.
	 *
	 * @since 1.6.11
	 *
	 * @var string
	 */
	protected $rest_base = 'addons';

	/**
	 * Object type.
	 *
	 * @since 1.6.11
	 *
	 * @var string
	 */
	protected $object_type = 'addon';

	/**
	 * Addons class.
	 *
	 * @since 1.6.11
	 *
	 * @var Masteriyo\Pro\Addons
	 */
	protected $addons = null;

	/**
	 * Constructor.
	 *
	 * @since 1.6.11
	 *
	 * @param Masteriyo\Addons $addons
	 */
	public function __construct( Addons $addons = null ) {
		$this->addons = $addons;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.11
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_addons' ),
					'permission_callback' => array( $this, 'get_addons_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/activate',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate' ),
					'permission_callback' => array( $this, 'activate_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/deactivate',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'deactivate' ),
					'permission_callback' => array( $this, 'deactivate_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		/**
		 * Register the route for bulk activation.
		 *
		 * @since 1.6.14
		 */
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/bulk-activate',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'bulk_activate' ),
					'permission_callback' => array( $this, 'activate_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		/**
		 * Register the route for bulk deactivation.
		 *
		 * @since 1.6.14
		 */
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/bulk-deactivate',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'bulk_deactivate' ),
					'permission_callback' => array( $this, 'deactivate_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<slug>[\w\-]+)',
			array(
				'args' => array(
					'slug' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'string',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_addon' ),
					'permission_callback' => array( $this, 'get_addon_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Get the courses'schema, conforming to JSON Schema.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'slug' => array(
					'description' => __( 'Addon slug.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $schema;
	}

	/**
	 * Get a collection of addons.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function activate( $request ) {
		if ( ! isset( $request['slug'] ) || empty( trim( $request['slug'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_addons',
				esc_html__( 'Addon slug is a required field.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$slug = is_array( $request['slug'] ) ? current( $request['slug'] ) : $request['slug'];

		if ( ! $this->addons->is_addon( $slug ) ) {
			return new \WP_Error(
				'masteriyo_rest_addon_invalid',
				__( 'Addon doesn\'t exist.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		/**
		 * Filters addon activate requirements.
		 *
		 * @since 1.6.11
		 *
		 * @param string $slug Addon slug.
		 * @param \WP_Rest_Response $response Response object.
		 * @param WP_Rest_Request $request Request object.
		 * @param Masteriyo\RestApi\Controllers\Version1\AddonsController $this Addons controller object.
		 */
		$requirements = apply_filters( 'masteriyo_pro_addon_activation_requirements', false, $slug, $request, $this );

		/**
		 * Filters addon activate requirements.
		 *
		 * @since 1.6.11
		 *
		 * @param \WP_Rest_Response $response Response object.
		 * @param WP_Rest_Request $request Request object.
		 * @param Masteriyo\RestApi\Controllers\Version1\AddonsController $this Addons controller object.
		 */
		$requirements = apply_filters( "masteriyo_pro_addon_{$slug}_activation_requirements", $requirements, $request, $this );

		if ( is_wp_error( $requirements ) ) {
			return $requirements;
		} elseif ( is_string( $requirements ) ) {
			return new \WP_Error(
				'masteriyo_rest_addon_activation_requirements',
				$requirements,
				array( 'status' => 400 )
			);
		}

		$addon_data = $this->addons->set_active( $slug );

		if ( ! $addon_data ) {
			return new \WP_Error(
				'masteriyo_rest_addon_activation_fail',
				__( 'Something went wrong.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$activated_addon = $this->get_addon_data( $slug, $addon_data );
		$response        = rest_ensure_response( $activated_addon );

		/**
		 * Filters addon activate response.
		 *
		 * @since 1.6.11
		 *
		 * @param \WP_Rest_Response $response Response object.
		 * @param WP_Rest_Request $request Request object.
		 * @param Masteriyo\RestApi\Controllers\Version1\AddonsController $this Addons controller object.
		 */
		return apply_filters( 'masteriyo_rest_addon_activate_response', $response, $request, $this );
	}

	/**
	 * Get a collection of addons.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function deactivate( $request ) {
		if ( ! isset( $request['slug'] ) || empty( trim( $request['slug'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_addons',
				esc_html__( 'Addon slug is a required field', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$slug = is_array( $request['slug'] ) ? current( $request['slug'] ) : $request['slug'];

		if ( ! $this->addons->is_addon( $slug ) ) {
			return new \WP_Error(
				'masteriyo_rest_addon_invalid',
				__( 'Addon doesn\'t exist.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		/**
		 * Filters addon deactivate requirements.
		 *
		 * @since 1.6.11
		 *
		 * @param \WP_Rest_Response $response Response object.
		 * @param WP_Rest_Request $request Request object.
		 * @param Masteriyo\RestApi\Controllers\Version1\AddonsController $this Addons controller object.
		 */
		$requirements = apply_filters( "masteriyo_pro_addon_{$slug}_deactivation_requirements", false, $request, $this );

		if ( is_wp_error( $requirements ) ) {
			return $requirements;
		} elseif ( is_string( $requirements ) ) {
			return new \WP_Error(
				'masteriyo_rest_addon_deactivation_requirements',
				$requirements,
				array( 'status' => 400 )
			);
		}

		$addon_data = $this->addons->set_inactive( $slug );

		if ( ! $addon_data ) {
			return new \WP_Error(
				'masteriyo_rest_addon_deactivation_fail',
				__( 'Something went wrong.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$deactivated_addon = $this->get_addon_data( $slug, $addon_data );
		$response          = rest_ensure_response( $deactivated_addon );

		/**
		 * Filters addon deactivate response.
		 *
		 * @since 1.6.11
		 *
		 * @param \WP_Rest_Response $response Response object.
		 * @param WP_Rest_Request $request Request object.
		 * @param Masteriyo\RestApi\Controllers\Version1\AddonsController $this Addons controller object.
		 */
		return apply_filters( 'masteriyo_rest_addon_deactivate_response', $response, $request, $this );
	}

	/**
	 * Bulk activate add-ons.
	 *
	 * @since 1.6.14
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function bulk_activate( $request ) {
		$slugs = $request['slugs'] ?? array();

		if ( empty( $slugs ) || ! is_array( $slugs ) ) {

			return new \WP_Error(
				'masteriyo_rest_addons',
				esc_html__( 'Addons slugs are required for bulk activation.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$activated_addons = array();

		foreach ( $slugs as $slug ) {
			$result = $this->addons->set_active( $slug );

			if ( $result ) {
				$activated_addons[] = $slug;
			}
		}

		/**
		 * Filters addon bulk activation response.
		 *
		 * @since 1.6.14
		 *
		 * @param WP_REST_Response       $response Response object.
		 * @param WP_REST_Request        $request  Request object.
		 * @param object                 $this     Addons controller object.
		 */
		return apply_filters( 'masteriyo_rest_addon_bulk_activate_response', rest_ensure_response( array( 'activated' => $activated_addons ) ), $request, $this );
	}

	/**
	 * Bulk deactivate add-ons.
	 *
	 * @since 1.6.14
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function bulk_deactivate( $request ) {
		$slugs = $request['slugs'] ?? array();

		if ( empty( $slugs ) || ! is_array( $slugs ) ) {

			return new \WP_Error(
				'masteriyo_rest_addons',
				esc_html__( 'Addons slugs are required for bulk deactivation.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$deactivated_addons = array();

		foreach ( $slugs as $slug ) {
			$result = $this->addons->set_inactive( $slug );

			if ( $result ) {
				$deactivated_addons[] = $slug;
			}
		}

		/**
		 * Filters addon bulk deactivation response.
		 *
		 * @since 1.6.14
		 *
		 * @param WP_REST_Response       $response Response object.
		 * @param WP_REST_Request        $request  Request object.
		 * @param object                 $this     Addons controller object.
		 */
		return apply_filters( 'masteriyo_rest_addon_bulk_deactivate_response', rest_ensure_response( array( 'deactivated' => $deactivated_addons ) ), $request, $this );
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params                       = array();
		$params['context']            = $this->get_context_param();
		$params['context']['default'] = 'view';

		$params['status'] = array(
			'description'       => __( 'Addon status.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'any',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'rest_validate_request_arg',
			'enum'              => AddonStatus::all(),
		);

		return $params;
	}

	/**
	 * Get a collection of addons.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_addons( $request ) {
		$response = array();

		if ( isset( $request['status'] ) && AddonStatus::ACTIVE === $request['status'] ) {
			$addons = $this->addons->get_active_addons();
		} elseif ( isset( $request['status'] ) && AddonStatus::INACTIVE === $request['status'] ) {
			$addons = $this->addons->get_inactive_addons();
		} else {
			$addons = $this->addons->get_addons_data();
		}

		$addons = array_filter(
			$addons,
			function( $slug ) {
				return $this->addons->is_addon( $slug );
			},
			ARRAY_FILTER_USE_KEY
		);

		// Move content drip addon to the end of the array.
		// TODO Remove after number of addons are introduced.
		$addons += array_splice( $addons, array_search( 'content-drip', array_keys( $addons ), true ), 1 );

		$addons = array_map(
			function( $slug ) {
				return $this->addons->get_data( $slug, true );
			},
			array_keys( $addons )
		);

		$response = array_map(
			function( $addon ) {
				$addon_plan      = masteriyo_array_get( $addon, 'plan', '' );
				$locked          = empty( $addon ) ? false : ! $this->addons->is_allowed( masteriyo_array_get( $addon, 'slug' ) );
				$addon['locked'] = $locked;

				return $addon;
			},
			$addons
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Return data by formatting it.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 *
	 * @return array
	 */
	protected function get_addon_data( $slug ) {
		$addon_data = $this->addons->get_data( $slug );

		$addon_keys = array_map(
			function( $addon_key ) {
				return sanitize_key( str_replace( ' ', '_', $addon_key ) );
			},
			array_keys( $addon_data )
		);

		$new_addon = array_combine( $addon_keys, array_values( $addon_data ) );

		$extra_data = array(
			'slug'      => $slug,
			'active'    => $this->addons->is_active( $slug ),
			'thumbnail' => $this->addons->get_thumbnail_url( $slug ),
		);

		return array_merge( $extra_data, $new_addon );
	}

	/**
	 * Get a single addon.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_addon( $request ) {
		$main_file = $this->addons->get_main_file( $request['slug'] );

		if ( ! $main_file ) {
			return new WP_Error(
				'masteriyo_rest_invalid_addon_slug',
				__( 'Addon doesn\'t exist.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$addon_data = $this->get_addon_data( $request['slug'] );

		return rest_ensure_response( $addon_data );
	}

	/**
	 * Checks if a given request has access to list addons.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_addon_permissions_check() {
		return true;
	}

	/**
	 * Checks if a given request has access to list addons.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_addons_permissions_check() {
		return true;
	}

	/**
	 * Checks if a given request has access to activate an addon.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function activate_permissions_check() {
		return current_user_can( 'manage_masteriyo_settings' ) || is_super_admin();
	}

	/**
	 * Checks if a given request has access to deactivate an addon.
	 *
	 * @since 1.6.11
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function deactivate_permissions_check() {
		return current_user_can( 'manage_masteriyo_settings' ) || is_super_admin();
	}
}
