<?php
/**
 * CommentController class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Utils;
use Masteriyo\Helper\Permission;

/**
 * Main class for CommentController.
 */
class CourseQuestionAnswersController extends CommentsController {
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
	protected $rest_base = 'courses/questions-answers';

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected $object_type = 'mto_course_qa';

	/**
	 * Comment Type.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	protected $comment_type = 'mto_course_qa';

	/**
	 * If object is hierarchial.
	 *
	 * @var bool
	 */
	protected $hierarchial = false;

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
	 * @param Permission $permission Permission instance.
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register Routes.
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
					'args'                => array(
						'force' => array(
							'default'     => false,
							'type'        => 'boolean',
							'description' => __( 'Required to be true, as the resource does not support trashing.', 'masteriyo' ),
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
			'/' . $this->rest_base . '/delete',
			array(
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_items' ),
					'permission_callback' => array( $this, 'delete_items_permissions_check' ),
					'args'                => array(
						'ids'   => array(
							'required'    => true,
							'description' => __( 'Question & Answers IDs.', 'masteriyo' ),
							'type'        => 'array',
						),
						'force' => array(
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/restore',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'restore_items' ),
					'permission_callback' => array( $this, 'delete_items_permissions_check' ),
					'args'                => array(
						'ids' => array(
							'required'    => true,
							'description' => __( 'Question & Answers IDs.', 'masteriyo' ),
							'type'        => 'array',
						),
					),
				),
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
		$params = parent::get_collection_params();

		// Remove the post argument.
		unset( $params['post'] );

		// Add course argument.
		$params['course_id'] = array(
			'default'     => array(),
			'description' => __( 'Limit result set to comments assigned to specific course IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		return $params;

	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|WP_Comment|Model $object Object ID or WP_Comment or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Comment' ) ? $object->comment_ID : $object->get_id();
			}

			$mto_course_qa = masteriyo( 'course-qa' );
			$mto_course_qa->set_id( $id );
			$mto_course_qa_repo = masteriyo( 'course-qa.store' );
			$mto_course_qa_repo->read( $mto_course_qa );
		} catch ( \Exception $e ) {
			return false;
		}

		return $mto_course_qa;
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$mto_course_qas = new \WP_Comment_Query( $query_args );
		$mto_course_qas = $mto_course_qas->comments;

		$total_posts = count( $mto_course_qas );

		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$mto_course_qas = new \WP_Comment_Query( $query_args );
			$mto_course_qas = $mto_course_qas->comments;
			$total_posts    = count( $mto_course_qas );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $mto_course_qas ) ),
			'total'   => (int) $total_posts,
			'pages'   => (int) ceil( $total_posts / (int) 10 ),
		);
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
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->get_course_qa_data( $object, $context );
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
	 * Get course question-answer data.
	 *
	 * @param Masteriyo\Models\CourseQuestionAnswer $course_qa Course question-answer instance.
	 * @param string       $context Request context.
	 *                             Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_qa_data( $course_qa, $context = 'view' ) {
		$data = array(
			'id'              => $course_qa->get_id(),
			'course_id'       => $course_qa->get_course_id(),
			'course_name'     => '',
			'user_name'       => $course_qa->get_user_name( $context ),
			'user_email'      => $course_qa->get_user_email( $context ),
			'user_url'        => $course_qa->get_user_url( $context ),
			'user_avatar'     => $course_qa->get_avatar_url( $context ),
			'ip_address'      => $course_qa->get_ip_address( $context ),
			'created_at'      => masteriyo_rest_prepare_date_response( $course_qa->get_created_at( $context ) ),
			'content'         => $course_qa->get_content( $context ),
			'status'          => $course_qa->get_status( $context ),
			'agent'           => $course_qa->get_agent( $context ),
			'parent'          => $course_qa->get_parent( $context ),
			'user_id'         => $course_qa->get_user_id( $context ),
			'by_current_user' => $course_qa->is_created_by_current_user(),
			'sender'          => $course_qa->is_created_by_student() ? 'student' : 'instructor',
		);

		if ( 0 === $course_qa->get_parent( $context ) ) {
			$data['answers_count'] = $course_qa->get_answers_count();
		}

		$course = $course_qa->get_course();

		if ( $course ) {
			$data['course_name'] = $course->get_name();
		}

		/**
		 * Filter course question answer rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Course question answer data.
		 * @param Masteriyo\Models\CourseQuestionAnswer $course_qa Course question answer object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\CoursesController $controller REST course question answer controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $course_qa, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.6.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		$args['post__in'] = $request['course_id'];

		return $args;
	}


	/**
	 * Get the Course question-answer's schema, conforming to JSON Schema.
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
				'id'              => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'course_id'       => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'name'            => array(
					'description' => __( 'Course question answerer user\'s name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email'           => array(
					'description' => __( 'Course question-answerer user Email.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'url'             => array(
					'description' => __( 'Course question answerer user URL.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'ip_address'      => array(
					'description' => __( 'The IP address of the question answerer.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'created_at'      => array(
					'description' => __( "The date the course was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'content'         => array(
					'description' => __( 'Course question answer content.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'status'          => array(
					'description' => __( 'Course question answer status.', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'approve',
					'enum'        => array( 'approve', 'hold', 'trash', 'spam' ),
					'context'     => array( 'view', 'edit' ),
				),
				'agent'           => array(
					'description' => __( 'Course question answer agent.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent'          => array(
					'description' => __( 'Course question answer parent.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'user_id'         => array(
					'description' => __( 'The user ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'by_current_user' => array(
					'description' => __( 'True if this course qa belongs to the current user.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Prepare a single course question-answer object for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Models\CourseQuestionAnswer
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {

		$id            = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$mto_course_qa = masteriyo( 'course-qa' );

		if ( 0 !== $id ) {
			$mto_course_qa->set_id( $id );
			$mto_course_qa_repo = masteriyo( 'course-qa.store' );
			$mto_course_qa_repo->read( $mto_course_qa );
		}

		// Course question-answer user.
		if ( isset( $request['user_name'] ) ) {
			$mto_course_qa->set_user_name( $request['user_name'] );
		}

		// Course question-answer user Email.
		if ( isset( $request['user_email'] ) ) {
			$mto_course_qa->set_user_email( $request['user_email'] );
		}

		// Course question-answer user URL.
		if ( isset( $request['user_url'] ) ) {
			$mto_course_qa->set_user_url( $request['user_url'] );
		}

		// Course question-answer user IP.
		if ( isset( $request['ip_address'] ) ) {
			$mto_course_qa->set_ip_address( $request['ip_address'] );
		}

		// Course question-answer Date.
		if ( isset( $request['created_at'] ) ) {
			$mto_course_qa->set_created_at( $request['created_at'] );
		}

		// Course question-answer Content.
		if ( isset( $request['content'] ) ) {
			$mto_course_qa->set_content( $request['content'] );
		}

		// Course question-answer Approved.
		if ( isset( $request['status'] ) ) {
			$mto_course_qa->set_status( $request['status'] );
		}

		// Course question-answer Agent.
		if ( isset( $request['agent'] ) ) {
			$mto_course_qa->set_agent( $request['agent'] );
		}

		// Course question-answer Type.
		if ( isset( $request['type'] ) ) {
			$mto_course_qa->set_type( $request['type'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$mto_course_qa->set_course_id( $request['course_id'] );
		}

		// Course question-answer Parent.
		if ( isset( $request['parent'] ) ) {
			$mto_course_qa->set_parent( $request['parent'] );
		}

		// User ID.
		if ( isset( $request['user_id'] ) ) {
			$mto_course_qa->set_user_id( $request['user_id'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$mto_course_qa->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
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
		 * @param Masteriyo\Models\CourseQuestionAnswer $comment  Course question-answer object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $mto_course_qa, $request, $creating );
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

		if ( ! is_user_logged_in() ) {
			return new \WP_Error(
				'masteriyo_user_not_logged_in',
				__( 'You must be logged in to ask question.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_course_qas_permissions( 'read' ) ) {
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

		if ( ! $this->permission->rest_check_course_qas_permissions( 'read', absint( $request['id'] ) ) ) {
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

		if ( ! $this->permission->rest_check_course_qas_permissions( 'create' ) ) {
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

		$question_answer = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $question_answer ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( get_current_user_id() !== $question_answer->get_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete this resource.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_course_qas_permissions( 'delete', absint( $request['id'] ) ) ) {
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

		$question_answer = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $question_answer ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_course_qas_permissions( 'edit', absint( $request['id'] ) ) ) {
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

	/**
	 * Restore course questions and answers.
	 *
	 * @since 1.6.5
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function restore_item( $request ) {
		$course_review = $this->get_object( (int) $request['id'] );

		if ( ! $course_review || 0 === $course_review->get_id() ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->comment_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		wp_untrash_comment( $course_review->get_id() );

		// Read course review again.
		$course_review = $this->get_object( (int) $request['id'] );

		$data     = $this->prepare_object_for_response( $course_review, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.6.7
	 *
	 * @param array $objects Courses data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Courses query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'            => $query_results['total'],
				'pages'            => $query_results['pages'],
				'current_page'     => $query_args['paged'],
				'per_page'         => $query_args['number'],
				'course_qas_count' => $this->get_comments_count(),
			),
		);
	}
}
