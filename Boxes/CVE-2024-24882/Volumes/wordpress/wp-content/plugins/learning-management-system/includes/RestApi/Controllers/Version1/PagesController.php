<?php
/**
 * Pages controller class.
 *
 * @since 1.5.9
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;

class PagesController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.5.9
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.5.9
	 *
	 * @var string
	 */
	protected $rest_base = 'pages';

	/**
	 * Post type.
	 *
	 * @since 1.5.9
	 *
	 * @var string
	 */
	protected $object_type = 'page';

	/**
	 * Post type.
	 *
	 * @since 1.5.9
	 *
	 * @var string
	 */
	protected $post_type = 'page';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.5.9
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.5.9
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.5.9
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
			)
		);
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @since 1.5.9
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		return true;
	}

	/**
	 * Get object.
	 *
	 * @since 1.5.9
	 *
	 * @param  \WP_Post $post Post.
	 * @return \WP_Post WP_Post object or WP_Error object.
	 */
	protected function get_object( $post ) {
		return $post;
	}

	/**
	 * Get a collection of posts.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$query_args    = $this->prepare_objects_query( $request );
		$query_results = $this->get_objects( $query_args );

		$objects = array();
		foreach ( $query_results['objects'] as $object ) {
			$data      = $this->prepare_object_for_response( $object, $request );
			$objects[] = $this->prepare_response_for_collection( $data );
		}

		/**
		 * Filters objects collection before processing.
		 *
		 * @since 1.0.0
		 *
		 * @param array $objects Objects collection.
		 * @param array $query_vars Query vars.
		 * @param array $query_results Query results.
		 */
		$objects = apply_filters( 'masteriyo_before_process_objects_collection', $objects, $query_args, $query_results );

		if ( is_callable( array( $this, 'process_objects_collection' ) ) ) {
			$objects = $this->process_objects_collection( $objects, $query_args, $query_results );
		}

		/**
		 * Filters objects collection after processing.
		 *
		 * @since 1.0.0
		 *
		 * @param array $objects Objects collection.
		 * @param array $query_vars Query vars.
		 * @param array $query_results Query results.
		 */
		$objects = apply_filters( 'masteriyo_after_process_objects_collection', $objects, $query_args, $query_results );

		$page      = (int) $query_args['paged'];
		$max_pages = $query_results['pages'];

		$response = rest_ensure_response( $objects );
		$response->header( 'X-WP-Total', $query_results['total'] );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$base = $this->rest_base;
		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $base ) ) );

		if ( $page > 1 ) {
			$prev_page = $page - 1;
			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since 1.5.9
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_page_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * @since 1.5.9
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.5.9
	 *
	 * @param array $objects Pages data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Pages query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'        => $query_results['total'],
				'pages'        => $query_results['pages'],
				'current_page' => $query_args['paged'],
				'per_page'     => $query_args['posts_per_page'],
			),
		);
	}

	/**
	 * Get post data.
	 *
	 * @since 1.5.9
	 *
	 * @param \WP_Post $post Post instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_page_data( $post, $context = 'view' ) {
		$data = array(
			'id'    => $post->ID,
			'title' => $post->post_title,
		);

		/**
		 * Filter post rest response data.
		 *
		 * @since 1.5.9
		 *
		 * @param array $data post data.
		 * @param \WP_Post $post post object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\PostsController $controller REST posts controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $post, $context, $this );
	}
}
