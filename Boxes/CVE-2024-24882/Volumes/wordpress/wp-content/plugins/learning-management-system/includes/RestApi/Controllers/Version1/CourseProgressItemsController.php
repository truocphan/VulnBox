<?php
/**
 * Course progress items controller.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\CourseProgressItemType;
use Masteriyo\Enums\CourseProgressPostType;
use Masteriyo\ModelException;
use Masteriyo\Helper\Permission;
use Masteriyo\Exceptions\RestException;
use Masteriyo\Models\CourseProgressItem;
use Masteriyo\Query\CourseProgressItemQuery;

/**
 * Course progress items controller class.
 */
class CourseProgressItemsController extends CrudController {

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
	protected $rest_base = 'course-progress/items';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_progress_item';

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
	 * @param Permission $permission Permission object.
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
			$this->rest_base,
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
			$this->rest_base . '/(?P<id>[\d]+)',
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
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params['page'] = array(
			'description'       => __( 'Paginate the course progress.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);

		$params['per_page'] = array(
			'description'       => __( 'Limit course progress per page.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['course_id'] = array(
			'description'       => __( 'Course progress ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['user_id'] = array(
			'description'       => __( 'User ID', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['item_type'] = array(
			'description'       => __( 'Course progress (lesson, quiz) item type.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_key',
			'enum'              => array( 'lesson', 'quiz' ),
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['completed'] = array(
			'description'       => __( 'Course progress item completed.', 'masteriyo' ),
			'type'              => 'boolean',
			'sanitize_callback' => 'masteriyo_string_to_bool',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['started_at'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['completed_at'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['modified_at'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['order'] = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['orderby'] = array(
			'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'id',
			'enum'              => array(
				'id',
				'type',
				'started_at',
				'modified_at',
				'completed_at',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|CourseProgressItem $id Object ID or object.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $course_progress_item ) {
		try {
			if ( is_int( $course_progress_item ) ) {
				$course_progress_item = masteriyo_get_course_progress_item( $course_progress_item );
			} else {
				$course_progress_item = $this->get_course_progress_item( $course_progress_item );

			}
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_progress_item;
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
		$data    = $this->get_course_progress_item_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

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
	 * Get user activity data.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgressItem  $course_progress_item User activity instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_item_data( $course_progress_item, $context = 'view' ) {
		$progress = \masteriyo_get_course_progress( $course_progress_item->get_progress_id( 'edit' ) );

		$data = array(
			'id'           => $course_progress_item->get_id( $context ),
			'progress_id'  => $course_progress_item->get_progress_id( $context ),
			'course_id'    => is_null( $progress ) ? $course_progress_item->get_item_id( $context ) : $progress->get_course_id( $context ),
			'user_id'      => $course_progress_item->get_user_id( $context ),
			'item_id'      => $course_progress_item->get_item_id( $context ),
			'item_type'    => $course_progress_item->get_item_type( $context ),
			'completed'    => $course_progress_item->get_completed( $context ),
			'started_at'   => masteriyo_rest_prepare_date_response( $course_progress_item->get_started_at( $context ) ),
			'modified_at'  => masteriyo_rest_prepare_date_response( $course_progress_item->get_modified_at( $context ) ),
			'completed_at' => masteriyo_rest_prepare_date_response( $course_progress_item->get_completed_at( $context ) ),
		);

		/**
		 * Filter course progress item rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Course progress item data.
		 * @param Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\CoursesController $controller REST courses controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $course_progress_item, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = wp_parse_args(
			$request->get_params(),
			array(
				'paged'        => 1,
				'per_page'     => 10,
				'user_id'      => masteriyo_get_current_user_id(),
				'status'       => 'any',
				'started_at'   => null,
				'modified_at'  => null,
				'completed_at' => null,
			)
		);

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->object_type}_object_query", $args, $request );

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
				'id'           => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'course_id'    => array(
					'description' => __( 'Course progress ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'user_id'      => array(
					'description' => __( 'User ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'item_id'      => array(
					'description' => __( 'Lesson/Quiz ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'item_type'    => array(
					'description' => __( 'Course progress ( Lesson, Quiz) item type.', 'masteriyo' ),
					'type'        => 'string',
					'enum'        => CourseProgressItemType::all(),
					'context'     => array( 'view', 'edit' ),
				),
				'completed'    => array(
					'description' => __( 'Course progress item completed.', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'started_at'   => array(
					'description' => __( 'Course progress item start date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'completed_at' => array(
					'description' => __( 'Course progress item complete date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'modified_at'  => array(
					'description' => __( 'Course progress item update date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Prepare a single course progress for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Models\CourseProgressItem
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id                   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_progress_item = masteriyo( 'course-progress-item' );

		if ( 0 !== $id ) {
			$course_progress_item->set_id( $id );
			$course_progress_item_repo = masteriyo( 'course-progress-item.store' );
			$course_progress_item_repo->read( $course_progress_item );
		}

		if ( isset( $request['item_type'] ) ) {
			$course_progress_item->set_item_type( $request['item_type'] );
		}

		if ( isset( $request['item_id'] ) ) {
			$course_progress_item->set_item_id( $request['item_id'] );
		}

		try {
			$user_id = $this->validate_user_id( $request, $creating );
			$course_progress_item->set_user_id( $user_id );

			$course_id = $this->validate_course_id( $request, $creating );
			if ( ! is_null( $course_id ) ) {
				$course_progress_item->set_course_id( $course_id );
			}

			$this->validate_course_progress_item( $course_progress_item );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		// Course progress id.
		$course_progress = \masteriyo_get_course_progress_by_user_and_course( $user_id, $course_id );
		if ( $course_progress ) {
			$course_progress_item->set_progress_id( $course_progress->get_id() );
		}

		// Course progress item completion.
		if ( isset( $request['completed'] ) ) {
			$course_progress_item->set_completed( $request['completed'] );
		}

		// Activity start date.
		if ( isset( $request['started_at'] ) ) {
			$course_progress_item->set_started_at( $request['started_at'] );
		}

		// Activity update date.
		if ( isset( $request['modified_at'] ) ) {
			$course_progress_item->set_modified_at( $request['modified_at'] );
		}

		// Activity complete date.
		if ( isset( $request['completed_at'] ) ) {
			$course_progress_item->set_completed_at( $request['completed_at'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\CourseProgressItem $course_progress_item  Course progress item object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $course_progress_item, $request, $creating );
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		if ( is_user_logged_in() ) {
			$query   = new CourseProgressItemQuery( $query_args );
			$objects = $query->get_course_progress_items();
		} else {
			$session = masteriyo( 'session' );

			$objects = array_filter(
				array_map(
					function( $object ) use ( $query_args ) {
						$course_progress_item = null;

						if ( absint( $query_args['item_id'] ) === $object['item_id'] && $object['course_id'] === $query_args['course_id'] ) {
							$course_progress_item = masteriyo( 'course-progress-item' );
							$course_progress_item->set_props( $object );
						}

						return $course_progress_item;
					},
					$session->get( 'course_progress_items', array() )
				)
			);
		}

		$total_items = count( array_values( $objects ) );

		return array(
			'objects' => $objects,
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Check if a given request has access to read item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
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

		return true;
	}

	/**
	 * Check if a given request has access to read items.
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		return true;
	}

	/**
	 * Check if a given request has access to create/update an item.
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

	/**
	 * Validate the user ID in the request.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function validate_user_id( $request, $creating = false ) {
		$user_id = null;

		// User ID.
		if ( isset( $request['user_id'] ) && ! empty( $request['user_id'] ) ) {
			$user_id = $request['user_id'];
		} else {
			$user_id = get_current_user_id();
		}

		// Return the auto generated guest user id.
		if ( ! is_user_logged_in() ) {
			return $user_id;
		}

		// Validate the user ID.
		$user = get_user_by( 'id', $user_id );
		if ( ! $user ) {
			throw new RestException(
				'masteriyo_rest_invalid_user_id',
				__( 'User ID is invalid.', 'masteriyo' ),
				400
			);
		}

		// If the current user is not administrator or manager, then the current
		// user must be same of the request suer id.
		if ( masteriyo_is_current_user_student() && get_current_user_id() !== $user_id ) {
			throw new RestException(
				'masteriyo_rest_access_denied_course_progress',
				__( 'Student cannot access other\'s course progress.', 'masteriyo' ),
				400
			);
		}

		return $user_id;
	}

	/**
	 * Return single object if there is only single object in the array.
	 *
	 * @param array $items Course items.
	 * @return void
	 */
	protected function process_objects_collection( $items ) {
		$items = ( 1 === count( $items ) ) ? $items[0] : $items;

		return $items;
	}

	/**
	 * Validate the course ID in the request.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function validate_course_id( $request, $creating = false ) {
		$course_id = null;

		// Course ID.
		if ( isset( $request['course_id'] ) && ! empty( $request['course_id'] ) ) {
			$course_id = $request['course_id'];

			// Validate course ID.
			$course_post = get_post( $course_id );
			if ( ! $course_post || 'mto-course' !== $course_post->post_type ) {
				throw new RestException(
					'masteriyo_rest_invalid_course_id',
					__( 'Course ID is invalid.', 'masteriyo' ),
					400
				);
			}
		}

		return $course_id;
	}

	/**
	 * Validate the course progress item.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgressItem $course_progress_item
	 * @throw exception
	 */
	protected function validate_course_progress_item( $course_progress_item ) {
		// Bail early if item_id is not either lesson or quiz.
		$item = get_post( $course_progress_item->get_item_id( 'edit' ) );

		if ( is_null( $item ) || ! in_array( $item->post_type, CourseProgressPostType::all(), true ) ) {
			throw new RestException(
				'masteriyo_invalid_item_id',
				__( 'Invalid item ID.', 'masteriyo' ),
				400
			);
		}
	}

	/**
	 * Save an object data.
	 *
	 * @since  1.3.8
	 *
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return Model|WP_Error
	 */
	protected function save_object( $request, $creating = false ) {
		// Save the object to database if the user is logged in.
		if ( is_user_logged_in() ) {
			$object = parent::save_object( $request, $creating );
			return $object;
		}

		return $this->save_object_in_session( $request, $creating );
	}

	/**
	 * Save an object in the session.
	 *
	 * @since  1.3.8
	 *
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return Model|WP_Error
	 */
	protected function save_object_in_session( $request, $creating = false ) {
		try {
			$session = masteriyo( 'session' );

			$progress_item = $this->prepare_object_for_database( $request, $creating );

			if ( is_wp_error( $progress_item ) ) {
				return $progress_item;
			}

			if ( ! $progress_item->get_started_at() ) {
				$progress_item->set_started_at( current_time( 'mysql' ), true );
			}

			$progress_item->set_modified_at( current_time( 'mysql' ), true );

			if ( $progress_item->get_completed() ) {
				$progress_item->set_completed_at( current_time( 'mysql' ), true );
			} else {
				$progress_item->set_completed_at( null );
			}

			$course_progress = $session->get( 'course_progress_items', array() );

			$key                     = $progress_item->get_item_id() . ':' . $progress_item->get_item_type() . ':' . $progress_item->get_course_id();
			$course_progress[ $key ] = $progress_item->get_data();

			$session->put( 'course_progress_items', $course_progress );

			return $progress_item;
		} catch ( ModelException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Get the course progress item.
	 *
	 * @since 1.3.8
	 *
	 * @param Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
	 *
	 * @return Masteriyo\Models\CourseProgressItem
	 */
	protected function get_course_progress_item( $course_progress_item ) {
		$post = get_post( $course_progress_item->get_item_id() );

		if ( ! $post || $course_progress_item->get_item_type() !== str_replace( 'mto-', '', $post->post_type ) ) {
			return new \WP_Error(
				'masteriyo_invalid_course_progress_item',
				__( 'Course progress item ID is invalid.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		// Get the course progress items from the database if the user is logged in
		// else from the session.
		if ( is_user_logged_in() ) {
			$query = new CourseProgressItemQuery(
				array(
					'user_id' => masteriyo_get_current_user_id(),
					'item_id' => $course_progress_item->get_item_id(),
				)
			);

			$course_progress_item = current( $query->get_course_progress_items() );
		} else {
			$session = masteriyo( 'session' );

			$item_id               = $course_progress_item->get_item_id();
			$course_progress_items = $session->get( 'course_progress_items', array() );

			if ( isset( $course_progress_items[ $item_id ] ) ) {
				$course_progress_item = masteriyo( 'course-progress-item' );
				$course_progress_item->set_item_id( $item_id );
				$course_progress_item->set_item_type( str_replace( 'mto-', '', $post->post_type ) );
				$course_progress_item->set_completed( $course_progress_items[ $post->ID ]['completed'] );
			}
		}

		/**
		 * Filters course progress item object.
		 *
		 * @since 1.3.8
		 *
		 * @param Masteriyo\Models\CourseProgressItem $object The course progress item object.
		 * @param Masteriyo\RestApi\Controllers\Version1\CourseProgressItemsController $controller Course progress API controller.
		 */
		return apply_filters( 'masteriyo_rest_get_course_progress_item', $course_progress_item, $this );
	}
}
