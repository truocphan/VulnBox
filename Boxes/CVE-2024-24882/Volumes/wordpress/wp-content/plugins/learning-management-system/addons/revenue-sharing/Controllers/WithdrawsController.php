<?php
/**
 * Withdraws controller.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Controllers
 */


namespace Masteriyo\Addons\RevenueSharing\Controllers;

defined( 'ABSPATH' ) || exit;

use WP_Post;
use WP_REST_Server;
use WP_Rest_Request;
use WP_Error;
use Masteriyo\Database\Model;
use Masteriyo\Helper\Permission;
use Masteriyo\RestApi\Controllers\Version1\PostsController;
use Masteriyo\Addons\RevenueSharing\Models\Withdraw;
use Masteriyo\Addons\RevenueSharing\Repository\WithdrawRepository;
use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\PostType\PostType;

/**
 * Withdraws controller class.
 *
 * @since 1.6.14
 */
class WithdrawsController extends PostsController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $rest_base = 'withdraws';

	/**
	 * Object type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $object_type = 'withdraw';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

	/**
	 * Permission class.
	 *
	 * @since 1.6.14
	 *
	 * @var Permission;
	 */
	protected $permission = null;

	/**
	 * Post type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $post_type = PostType::WITHDRAW;

	/**
	 * Constructor.
	 *
	 * @since 1.6.14
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
			)
		);
	}

	/**
	 * Get the query params for collections of withdraws.
	 *
	 * @since  2.5.20
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.6.14
	 *
	 * @param  int|Model|WP_Post $obj Object ID or Model or WP_Post object.
	 * @return object|false Model object or false on failure.
	 */
	protected function get_object( $obj ) {
		try {
			$id = $obj instanceof WP_Post ? $obj->ID : ( $obj instanceof Model ? $obj->get_id() : $obj );

			/** @var Withdraw */
			$withdraw = masteriyo( 'withdraw' );
			$withdraw->set_id( $id );

			/** @var WithdrawRepository */
			$withdraw_repo = masteriyo( 'withdraw.store' );
			$withdraw_repo->read( $withdraw );
		} catch ( \Exception $e ) {
			return false;
		}

		return $withdraw;
	}


	/**
	 * Get items permissions check.
	 *
	 * @since 1.6.14
	 *
	 * @param WP_Rest_Request $request Full detail about request.
	 *
	 * @return bool|WP_Error True if the user has permission, false otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! $this->permission ) {
			return new WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_instructor() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_order_permissions( 'read_orders' ) ) {
			return new WP_Error(
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
	 * Check permissions for an item.
	 *
	 * @since 1.6.14
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_instructor() ) {
			return true;
		}

		if ( 'read' === $context ) {
			$order = masteriyo_get_withdraw( $object_id );
			$cap   = $order->get_user_id() === get_current_user_id() ? 'read_orders' : 'read_others_orders';

			if ( ! $this->permission->rest_check_withdraw_permissions( $cap, $object_id ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Create item permissions check.
	 *
	 * @since 1.6.14
	 *
	 * @param WP_Rest_Request $request Full detail about request.
	 *
	 * @return bool|WP_Error True if the user has permission, false otherwise.
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! $this->permission ) {
			return new WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! masteriyo_is_current_user_instructor() ) {
			return new WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you cannot create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Update item permissions check.
	 *
	 * @since 1.6.14
	 *
	 * @param WP_Rest_Request $request Full detail about request.
	 *
	 * @return bool|WP_Error True if the user has permission, false otherwise.
	 */
	public function update_item_permissions_check( $request ) {
		if ( ! $this->permission ) {
			return new WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! masteriyo_is_current_user_admin() ) {
			return new WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you cannot update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.6.14
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args                = parent::prepare_objects_query( $request );
		$args['post_author'] = isset( $request['instructor'] ) ? absint( $request['instructor'] ) : null;
		$args['post_status'] = isset( $request['status'] ) ? $request['status'] : WithdrawStatus::ANY;
		return $args;
	}

	/**
	 * Get withdraw data.
	 *
	 * @since 1.6.14
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 * @param string $context
	 * @return array
	 */
	protected function get_withdraw_data( $withdraw, $context = 'view' ) {
		$data = array(
			'id'               => $withdraw->get_id(),
			'status'           => $withdraw->get_status(),
			'withdraw_amount'  => masteriyo_price(
				$withdraw->get_withdraw_amount(),
				array(
					'html' => false,
				)
			),
			'withdraw_method'  => $withdraw->get_withdraw_method(),
			'date_created'     => $withdraw->get_date_created()->format( 'Y-m-d, h:i A' ),
			'date_modified'    => $withdraw->get_date_modified()->format( 'Y-m-d, h:i A' ),
			'rejection_detail' => $withdraw->get_rejection_detail(),
			'withdrawer'       => masteriyo_array_only(
				\Masteriyo\Resources\UserResource::to_array( $withdraw->get_withdrawer() ),
				array(
					'id',
					'username',
					'email',
					'display_name',
					'avatar_url',
					'first_name',
					'last_name',
				)
			),
		);

		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $withdraw, $context, $this );
	}

	/**
	 * @param \Masteriyo\Database\Model $withdraw_obj
	 * @param \WP_REST_Request $request
	 */
	protected function prepare_object_for_response( $withdraw_obj, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_withdraw_data( $withdraw_obj, $context );

		$data     = $this->add_additional_fields_schema( $data );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $withdraw_obj, $request ) );

		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $withdraw_obj, $request );
	}

	/**
	 * Get withdraws count.
	 *
	 * @return array
	 */
	protected function get_withdraws_count() {
		$post_count = parent::get_posts_count();

		$post_count                        = masteriyo_array_only( $post_count, WithdrawStatus::all() );
		$post_count[ WithdrawStatus::ANY ] = array_sum( $post_count );

		return $post_count;
	}

	/**
	 * Process objects collection.
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $object Withdraw object.
	 * @param array $query_args Query args.
	 * @param array $query_results Query results.
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		$data = array(
			'data' => $objects,
			'meta' => array(
				'total'           => $query_results['total'],
				'pages'           => $query_results['pages'],
				'current_page'    => $query_args['paged'],
				'per_page'        => $query_args['posts_per_page'],
				'withdraws_count' => $this->get_withdraws_count(),
			),
		);

		return $data;
	}

	/**
	 * Prepare a withdraw object for database.
	 *
	 * @since 1.6.14
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|\Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$withdraw = masteriyo_create_withdraw_object();
		$id       = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		if ( 0 !== $id ) {
			$withdraw->set_id( $id );
			$withdraw_repo = masteriyo_get_withdraw_store();
			$withdraw_repo->read( $withdraw );
		}

		if ( isset( $request['withdraw_amount'] ) ) {
			$withdraw->set_withdraw_amount( $request['withdraw_amount'] );
		}

		if ( isset( $request['status'] ) ) {
			$withdraw->set_status( $request['status'] );
		}

		if ( isset( $request['rejection_detail'] ) ) {
			$withdraw->set_rejection_detail( $request['rejection_detail'] );
		}

		if ( isset( $request['withdraw_method'] ) ) {
			$withdraw->set_withdraw_method( $request['withdraw_method'] );
		}

		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $withdraw, $request, $creating );
	}
}
