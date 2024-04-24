<?php
/**
 * REST API section children controller
 *
 * Handles requests to the sections/children endpoint.
 *
 * @author   mi5t4n
 * @category API
 * @package Masteriyo\RestApi
 * @since    1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;

/**
 * REST API section children controller class.
 *
 * @package Masteriyo\RestApi
 * @extends CrudController
 */
class SectionChildrenController extends CrudController {

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
	protected $rest_base = 'sections/children';

	/**
	 * Object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'section_item';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'any';

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
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register the routes for terms.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check if a given request has access to read the terms.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( 'mto-section', 'read' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Get the query params for collections
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['section'] = array(
			'description'       => __( 'Limit result set to resources assigned to a specific section.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => null,
			'required'          => true,
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args                = parent::prepare_objects_query( $request );
		$args['post_parent'] = $request['section'];
		return $args;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_Post $post Post object.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $post ) {
		try {
			$item = masteriyo( $post->post_type );
			$item->set_id( $post->ID );
			$item_repo = masteriyo( "{$post->post_type}.store" );
			$item_repo->read( $item );
		} catch ( \Exception $e ) {
			return false;
		}

		return $item;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_section_items( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

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
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}


	/**
	 * Get section data.
	 *
	 * @param Model  $section Section instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_section_items( $section_item, $context = 'view' ) {
		$data = array(
			'id'         => $section_item->get_id(),
			'name'       => $section_item->get_name( $context ),
			'type'       => $section_item->get_object_type(),
			'parent_id'  => $section_item->get_parent_id(),
			'course_id'  => $section_item->get_course_id(),
			'menu_order' => $section_item->get_menu_order(),
		);

		return $data;
	}
}
