<?php
/**
 * Order Items controller.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;
use Masteriyo\Models\Order\OrderItem;

/**
 * OrderItemsController class.
 */
class OrderItemsController extends PostsController {

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
	protected $rest_base = 'order-items';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'order_item';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

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
	 * @param Permission $permission Permision object.
	 */
	public function __construct( Permission $permission = null ) {
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
					'args'                => array(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = array();

		$params['order_id'] = array(
			'description'       => __( 'Filter order items by order ID.', 'masteriyo' ),
			'type'              => 'number',
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
			'required'          => true,
		);
		$params['paged']    = array(
			'description'       => __( 'Paginate the order items.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 1,
		);
		$params['per_page'] = array(
			'description'       => __( 'Limit items per page.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 10,
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @param  int|WP_Post $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id         = $id instanceof \stdClass ? $id->order_item_id : $id;
			$id         = $id instanceof OrderItem ? $id->get_id() : $id;
			$order_item = masteriyo( 'order-item.course' );
			$order_item->set_id( $id );
			$order_item_repo = masteriyo( 'order-item.course.store' );
			$order_item_repo->read( $order_item );
		} catch ( \Exception $e ) {
			return false;
		}

		return $order_item;
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
		$data    = $this->get_order_item_data( $object, $context );

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
	 * Get order item data.
	 *
	 * @param Masteriyo\Models\Order\OrderItem  $order_item Order instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_order_item_data( $order_item, $context = 'view' ) {
		$data = array(
			'id'        => $order_item->get_id(),
			'order_id'  => $order_item->get_order_id(),
			'course_id' => $order_item->get_course_id( $context ),
			'name'      => $order_item->get_name( $context ),
			'type'      => $order_item->get_type( $context ),
			'quantity'  => $order_item->get_quantity( $context ),
			'total'     => $order_item->get_total( $context ),
		);

		/**
		 * Filter order item rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Order Item data.
		 * @param Masteriyo\Models\Order\OrderItem $order_item Order item object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\OrderItemsController $controller REST order items controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $order_item, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array();

		if ( ! empty( $request['order_id'] ) ) {
			$args['order_id'] = $request['order_id'];
		}
		if ( ! empty( $request['paged'] ) ) {
			$args['paged'] = $request['paged'];
		}
		if ( ! empty( $request['per_page'] ) ) {
			$args['per_page'] = $request['per_page'];
		}

		return $args;
	}

	/**
	 * Get the orders' schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'        => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'order_id'  => array(
					'description' => __( 'Order ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'course_id' => array(
					'description' => __( 'Product ID', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'name'      => array(
					'description' => __( 'Order item name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'type'      => array(
					'description' => __( 'Order item type', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'quantity'  => array(
					'description' => __( 'Quantity', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'total'     => array(
					'description' => __( 'Total amount of the order item.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data' => array(
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
	 * Prepare a single order for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id         = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$order_item = masteriyo( 'order-item.course' );

		if ( 0 !== $id ) {
			$order_item->set_id( $id );
			$order_item_repo = masteriyo( 'order-item.store' );
			$order_item_repo->read( $order_item );
		}

		// Order ID.
		if ( isset( $request['order_id'] ) ) {
			$order_item->set_order_id( $request['order_id'] );
		}

		// Product ID.
		if ( isset( $request['course_id'] ) ) {
			$order_item->set_course_id( $request['course_id'] );
		}

		// Product Name.
		if ( isset( $request['name'] ) ) {
			$order_item->set_name( $request['name'] );
		}

		// Order Item Type.
		if ( isset( $request['type'] ) ) {
			$order_item->set_type( $request['type'] );
		}

		// Order Items Quantity.
		if ( isset( $request['quantity'] ) ) {
			$order_item->set_quantity( $request['quantity'] );
		}

		// Total Price.
		if ( isset( $request['total'] ) ) {
			$order_item->set_total( $request['total'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$order_item->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Database\Model $order_item Order item object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $order_item, $request, $creating );
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		global $wpdb;

		$table_name = masteriyo( 'order-item.store' )->get_table_name();
		$order_id   = $query_args['order_id'];
		$offset     = 0;
		$per_page   = 10;

		if ( $query_args['per_page'] > 0 ) {
			$per_page = $query_args['per_page'];
		}
		if ( $query_args['paged'] > 0 ) {
			$offset = ( $query_args['paged'] - 1 ) * $per_page;
		}

		/**
		 * Query for order items.
		 */
		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_order_items WHERE order_id = %d LIMIT %d, %d",
				$order_id,
				$offset,
				$per_page
			)
		);

		/**
		 * Query for counting rows.
		 */
		$total_items = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(order_item_id) FROM {$wpdb->prefix}masteriyo_order_items WHERE order_id = %d",
				$order_id
			)
		);

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $result ) ),
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$order_item = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $order_item ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$order = masteriyo_get_order( $order_item->get_order_id() );

		if ( ! is_object( $order ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Could not read the order object.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$cap = masteriyo_is_current_user_post_author( $order_item->get_order_id() ) ? 'read_orders' : 'read_others_orders';

		if ( ! $this->permission->rest_check_order_permissions( $cap, $order_item->get_order_id() ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to read resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to read items.
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

		$order_id = isset( $request['order_id'] ) ? absint( $request['order_id'] ) : 0;
		$order    = masteriyo_get_order( $order_id );

		if ( ! is_object( $order ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Could not read the order object.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_order_permissions( 'read', $order->get_id() ) ) {
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
	 * Check if a given request has access to create an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$order_id = isset( $request['order_id'] ) ? absint( $request['order_id'] ) : 0;
		$order    = masteriyo_get_order( $order_id );

		if (
			! $order_id ||
			! is_object( $order ) ||
			(
				! masteriyo_is_current_user_post_author( $order_id ) &&
				! masteriyo_is_current_user_admin()
			)
		) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid order ID.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_order_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$order_item = $this->get_object( absint( $request['id'] ) );

		if (
			! $order_item ||
			! is_object( $order_item ) ||
			(
				! masteriyo_is_current_user_post_author( $order_item->get_order_id() ) &&
				! masteriyo_is_current_user_admin()
			)
		) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid order ID.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_order_permissions( 'delete', $order_item->get_order_id() ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$order_item = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $order_item ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if (
			! $order_item ||
			! is_object( $order_item ) ||
			(
				! masteriyo_is_current_user_post_author( $order_item->get_order_id() ) &&
				! masteriyo_is_current_user_admin()
			)
		) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid order ID.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_order_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return true;
	}
}
