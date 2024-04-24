<?php
/**
 * Webhooks controller class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\WebhookStatus;
use Masteriyo\Helper\Permission;
use Masteriyo\PostType\PostType;
use Masteriyo\Resources\WebhookResource;

class WebhooksController extends PostsController {
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
	protected $rest_base = 'webhooks';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $object_type = 'webhook';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = PostType::WEBHOOK;

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.6.9
	 *
	 * @var \Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.6.9
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.9
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
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => array(
						'force' => array(
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)/restore',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'restore_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/events',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_listeners' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.6.9
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['status'] = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to webhooks assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any' ), WebhookStatus::all() ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.6.9
	 *
	 * @param  \Masteriyo\Models\Webhook|\WP_Post $object Model or WP_Post object.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = $object instanceof \WP_Post ? $object->ID : $object->get_id();
			}

			$webhook = masteriyo( 'webhook' );
			$webhook->set_id( $id );
			$webhook_repo = masteriyo( 'webhook.store' );
			$webhook_repo->read( $webhook );
		} catch ( \Exception $e ) {
			return false;
		}

		return $webhook;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $object Model object.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_webhook_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * @since 1.6.9
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param \Masteriyo\Models\Webhook $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.6.9
	 *
	 * @param array $objects Webhooks data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Webhooks query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'          => $query_results['total'],
				'pages'          => $query_results['pages'],
				'current_page'   => $query_args['paged'],
				'per_page'       => $query_args['posts_per_page'],
				'webhooks_count' => $this->get_webhooks_count(),
			),
		);
	}

	/**
	 * Get webhooks count by status.
	 *
	 * @since 1.6.9
	 *
	 * @return Array
	 */
	protected function get_webhooks_count() {
		$post_count = parent::get_posts_count();

		return masteriyo_array_only( $post_count, array_merge( array( 'any' ), WebhookStatus::all() ) );
	}

	/**
	 * Get webhook data.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Webhook instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_webhook_data( $webhook, $context = 'view' ) {
		/**
		 * Filter webhook rest response data.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Webhook data.
		 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\WebhooksController $controller REST webhooks controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", WebhookResource::to_array( $webhook ), $webhook, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.6.9
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = $request['status'];

		if ( ! masteriyo_is_current_user_admin() ) {
			$args['author'] = get_current_user_id();
		}

		return $args;
	}

	/**
	 * Get the webhooks'schema, conforming to JSON Schema.
	 *
	 * @since 1.6.9
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'           => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'         => array(
					'description' => __( 'Webhook name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'status'       => array(
					'description' => __( 'Webhook status', 'masteriyo' ),
					'type'        => 'string',
					'default'     => WebhookStatus::INACTIVE,
					'enum'        => WebhookStatus::all(),
					'context'     => array( 'view', 'edit' ),
				),
				'events'       => array(
					'description' => __( 'Webhook events', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'string',
					),
				),
				'delivery_url' => array(
					'description'       => __( 'Webhook delivery URL', 'masteriyo' ),
					'type'              => 'string',
					'validate_callback' => 'wp_http_validate_url',
					'context'           => array( 'view', 'edit' ),
				),
				'description'  => array(
					'description' => __( 'Webhook description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'secret'       => array(
					'description' => __( 'Webhook secret', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'author_id'    => array(
					'description' => __( 'Webhook author ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'author'       => array(
					'description' => __( 'Webhook author', 'masteriyo' ),
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
					'type'        => 'object',
					'properties'  => array(
						'id'           => array(
							'description' => __( 'Author ID', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'display_name' => array(
							'description' => __( 'Display name of the author', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'avatar_url'   => array(
							'description' => __( 'Avatar URL of the author', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
					),
				),
				'created_at'   => array(
					'description' => __( 'The date the course was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'modified_at'  => array(
					'description' => __( 'The date the course was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'meta_data'    => array(
					'description' => __( 'Meta data', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value', 'masteriyo' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Prepare a single webhook for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|\Masteriyo\Models\Webhook
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id      = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$webhook = masteriyo( 'webhook' );

		if ( 0 !== $id ) {
			$webhook->set_id( $id );
			$webhook_repo = masteriyo( \Masteriyo\Repository\WebhookRepository::class );
			$webhook_repo->read( $webhook );
		}

		// Webhook title.
		if ( isset( $request['name'] ) ) {
			$webhook->set_name( sanitize_text_field( $request['name'] ) );
		}

		// Webhook description.
		if ( isset( $request['description'] ) ) {
			$webhook->set_description( $request['description'] );
		}

		// Webhook status.
		if ( isset( $request['status'] ) ) {
			$webhook->set_status( $request['status'] );
		}

		// Webhook events.
		if ( isset( $request['events'] ) ) {
			$webhook->set_events( $request['events'] );
		}

		// Webhook delivery_url.
		if ( isset( $request['delivery_url'] ) ) {
			$webhook->set_delivery_url( $request['delivery_url'] );
		}

		// Secret.
		if ( isset( $request['secret'] ) ) {
			$webhook->set_secret( $request['secret'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$webhook->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\Webhook $webhook  Webhook object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $webhook, $request, $creating );
	}

	/**
	 * Restore webhook.
	 *
	 * @since 1.6.9
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function restore_item( $request ) {
		$object = $this->get_object( (int) $request['id'] );

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->post_type}_invalid_id", __( 'Invalid ID.', 'masteriyo' ), array( 'status' => 404 ) );
		}

		wp_untrash_post( $object->get_id() );

		// Read object again.
		$object = $this->get_object( (int) $request['id'] );

		$data     = $this->prepare_object_for_response( $object, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', $this->get_permalink( $object ), array( 'type' => 'text/html' ) );
		}

		return $response;
	}

	/**
	 * Get available webhook events.
	 *
	 * @since 1.6.9
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_listeners( $request ) {
		$listeners = masteriyo_get_webhook_listeners();
		$user      = masteriyo_get_current_user();
		$results   = array();

		foreach ( $listeners as $name => $listener ) {
			if ( $user && $listener->is_allowed( $user ) ) {
				$results[] = array(
					'name'  => $listener->get_name(),
					'label' => $listener->get_label(),
				);
			}
		}

		return rest_ensure_response( $results );
	}
}
