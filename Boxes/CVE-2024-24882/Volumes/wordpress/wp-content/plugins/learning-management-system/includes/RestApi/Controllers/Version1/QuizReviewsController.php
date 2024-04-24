<?php
/**
 * CommentController class.
 *
 * @since 1.7.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */
namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;



use Masteriyo\Helper\Permission;
use Masteriyo\Enums\CommentStatus;

/**
 * Main class for Quiz CommentController.
 */
class QuizReviewsController extends CommentsController {
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
	protected $rest_base = 'quizes/reviews';

	/**
	 * Object Type.
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_review';

	/**
	 * Comment Type.
	 *
	 * @var string
	 */
	protected $comment_type = 'mto_quiz_review';

	/**
	 * Permission class.
	 *
	 * @since 1.7.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;


	/**
	 * Constructor.
	 *
	 * @since 1.7.0
	 *
	 * @param Permission $permission Permission instance.
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register Routes.
	 *
	 * @since 1.7.0
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
						'force_delete' => array(
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
							'default'     => false,
						),
						'children'     => array(
							'description' => __( 'Whether to delete the replies.', 'masteriyo' ),
							'type'        => 'boolean',
							'default'     => false,
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
						'ids'      => array(
							'required'    => true,
							'description' => __( 'Review IDs.', 'masteriyo' ),
							'type'        => 'array',
						),
						'force'    => array(
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
						'children' => array(
							'default'     => false,
							'description' => __( 'Whether to delete the replies.', 'masteriyo' ),
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
							'description' => __( 'Review Ids', 'masteriyo' ),
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
	 * @since 1.7.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		unset( $params['post'] );

		$params['quiz'] = array(
			'default'     => array(),
			'description' => __( 'Limit result set to quiz reviews assigned to specific quiz IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		/**
		 * Filters REST API collection parameters for the quiz reviews controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Comment_Query parameter. Use the
		 * `rest_comment_query` filter to set WP_Comment_Query parameters.
		 *
		 * @since 1.7.0
		 *
		 * @param array $params JSON Schema-formatted collection parameters.
		 */
		return apply_filters( 'masteriyo_rest_quiz_review_collection_params', $params );
	}

	/**
	 * Get object.
	 *
	 * @since 1.7.0
	 *
	 * @param int|\WP_Comment|\Masteriyo\Models\QuizReview $object Object ID or WP_Comment or Model.
	 *
	 * @return \Masteriyo\Models\QuizReview Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Comment' ) ? $object->comment_ID : $object->get_id();
			}
			$quiz_review = masteriyo( 'quiz_review' );
			$quiz_review->set_id( $id );
			$quiz_review_repo = masteriyo( 'quiz_review.store' );
			$quiz_review_repo->read( $quiz_review );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz_review;
	}

	/**
	 * Get objects.
	 *
	 * @since 1.7.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query          = new \WP_Comment_Query( $query_args );
		$quiz_reviews   = $query->comments;
		$total_comments = $this->get_total_comments( $query_args );

		if ( $total_comments < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			$total_comments = $this->get_total_comments( $query_args );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $quiz_reviews ) ),
			'total'   => (int) $total_comments,
			'pages'   => (int) ceil( $total_comments / (int) $query_args['number'] ),
		);
	}

	/**
	 * Get the total number of comments by comment type.
	 *
	 * @since 1.7.0
	 *
	 * @param array $query_args WP_Comment_Query args.
	 * @return int
	 */
	protected function get_total_comments( $query_args ) {
		if ( isset( $query_args['paged'] ) ) {
			unset( $query_args['paged'] );
		}

		if ( isset( $query_args['number'] ) ) {
			unset( $query_args['number'] );
		}

		if ( isset( $query_args['offset'] ) ) {
			unset( $query_args['offset'] );
		}

		$query_args['fields'] = 'ids';

		$comments = get_comments( $query_args );

		return count( $comments );
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since 1.7.0
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->get_quiz_review_data( $object, $context );
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
		 * @since 1.7.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get quiz review data.
	 *
	 * @since 1.7.0
	 *
	 * @param Masteriyo\Models\QuizReview $quiz_review Quiz Review instance.
	 * @param string       $context Request context.
	 *                             Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_review_data( $quiz_review, $context = 'view' ) {
		$author = masteriyo_get_user( $quiz_review->get_author_id( $context ) );

		$data = array(
			'id'                => $quiz_review->get_id(),
			'author_id'         => $quiz_review->get_author_id( $context ),
			'author_name'       => $quiz_review->get_author_name( $context ),
			'author_email'      => $quiz_review->get_author_email( $context ),
			'author_url'        => $quiz_review->get_author_url( $context ),
			'author_avatar_url' => is_wp_error( $author ) ? '' : $author->get_avatar_url(),
			'ip_address'        => $quiz_review->get_ip_address( $context ),
			'date_created'      => masteriyo_rest_prepare_date_response( $quiz_review->get_date_created( $context ) ),
			'title'             => $quiz_review->get_title( $context ),
			'description'       => $quiz_review->get_content( $context ),
			'rating'            => $quiz_review->get_rating( $context ),
			'status'            => $quiz_review->get_status( $context ),
			'agent'             => $quiz_review->get_agent( $context ),
			'type'              => $quiz_review->get_type( $context ),
			'parent'            => $quiz_review->get_parent( $context ),
			'quiz'              => null,
			'replies_count'     => $quiz_review->total_replies_count(),
			'course'            => null,
			'user'              => null,
		);

		$quiz   = masteriyo_get_quiz( $quiz_review->get_quiz_id() );
		$course = masteriyo_get_course( $quiz->get_course_id() );
		$user   = masteriyo_get_user( absint( $quiz_review->get_author_id() ) );
		if ( $quiz ) {
			$data['quiz'] = array(
				'id'   => $quiz->get_id(),
				'name' => $quiz->get_name(),
			);

			$data['user'] = array(
				'first_name' => $user->get_first_name(),
				'last_name'  => $user->get_last_name(),
			);

			$data['course'] = array(
				'name' => $course->get_name(),
			);
		}

		/**
		 * Filter quiz reviews rest response data.
		 *
		 * @since 1.7.0
		 *
		 * @param array $data Quiz review data.
		 * @param Masteriyo\Models\QuizReview $quiz_review quiz review object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizReviewsController $controller REST quizs controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $quiz_review, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		$args['post__in'] = $request['quiz'];

		return $args;
	}

	/**
	 * Get the Quiz review's schema, conforming to JSON Schema.
	 *
	 * @since 1.7.0
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
				'quiz_id'      => array(
					'description' => __( 'Quiz ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'name'         => array(
					'description' => __( 'Quiz Reviewer Author.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email'        => array(
					'description' => __( 'Quiz Reviewer Author Email.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'url'          => array(
					'description' => __( 'Quiz Reviewer Author URL.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'ip_address'   => array(
					'description' => __( 'The IP address of the reviewer', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created' => array(
					'description' => __( "The date the quiz was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'description'  => array(
					'description' => __( 'Quiz Review Description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'title'        => array(
					'description' => __( 'Quiz Review Title.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'content'      => array(
					'description' => __( 'Quiz Review Content.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
				),
				'rating'       => array(
					'description' => __( 'Quiz Review rating.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'       => array(
					'description' => __( 'Quiz Review Status.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'agent'        => array(
					'description' => __( 'Quiz Review Agent.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'type'         => array(
					'description' => __( 'Quiz Review Type.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent'       => array(
					'description' => __( 'Quiz Review Parent.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'author_id'    => array(
					'description' => __( 'The User ID.', 'masteriyo' ),
					'type'        => 'integer',
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
	 * Prepare a single quiz review object for create or update.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Models\QuizReview
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id          = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$quiz_review = masteriyo( \Masteriyo\Models\QuizReview::class );

		$user = masteriyo_get_current_user();

		if ( 0 !== $id ) {
			$quiz_review->set_id( $id );
			$quiz_review_repo = masteriyo( \Masteriyo\Repository\QuizReviewRepository::class );
			$quiz_review_repo->read( $quiz_review );
		}

		if (
			! is_null( $user ) &&
			! isset( $request['author_id'] ) &&
			! isset( $request['author_name'] ) &&
			! isset( $request['author_email'] )
		) {
			$quiz_review->set_author_id( $user->get_id() );
			$quiz_review->set_author_email( $user->get_email() );
			$quiz_review->set_author_name( $user->get_display_name() );
			$quiz_review->set_author_url( $user->get_url() );
		}

		// Quiz Review Author.
		if ( isset( $request['author_name'] ) ) {
			$quiz_review->set_author_name( $request['author_name'] );
		}

		// Quiz Review Author Email.
		if ( isset( $request['author_email'] ) ) {
			$quiz_review->set_author_email( $request['author_email'] );
		}

		// Quiz Review Author URL.
		if ( isset( $request['author_url'] ) ) {
			$quiz_review->set_author_url( $request['author_url'] );
		}

		// Quiz Review Author IP.
		if ( isset( $request['ip_address'] ) ) {
			$quiz_review->set_ip_address( $request['ip_address'] );
		}

		// Quiz Review Date.
		if ( isset( $request['date_created'] ) ) {
			$quiz_review->set_date_created( $request['date_created'] );
		}

		// Quiz Review Title.
		if ( isset( $request['title'] ) ) {
			$quiz_review->set_title( $request['title'] );
		}

		// Quiz Review Content.
		if ( isset( $request['content'] ) ) {
			$quiz_review->set_content( $request['content'] );
		}

		// Quiz Review Rating.
		if ( isset( $request['rating'] ) ) {
			$quiz_review->set_rating( $request['rating'] );
		}

		// Quiz Review Approved.
		if ( isset( $request['status'] ) ) {
			$quiz_review->set_status( $request['status'] );
		}

		// Quiz Review Agent.
		if ( isset( $request['agent'] ) ) {
			$quiz_review->set_agent( $request['agent'] );
		}

		// Quiz Review Type.
		if ( isset( $request['type'] ) ) {
			$quiz_review->set_type( $request['type'] );
		}

		// Quiz ID.
		if ( isset( $request['quiz_id'] ) ) {
			$quiz_review->set_quiz_id( $request['quiz_id'] );
		}

		// Quiz Review Parent.
		if ( isset( $request['parent'] ) ) {
			$quiz_review->set_parent( $request['parent'] );
		}

		// User ID.
		if ( isset( $request['author_id'] ) ) {
			$quiz_review->set_author_id( $request['author_id'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$quiz_review->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.7.0
		 *
		 * @param Masteriyo\Models\QuizReview $comment Quiz review object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $quiz_review, $request, $creating );
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @since 1.7.0
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
		if ( ! $this->permission->rest_check_quiz_reviews_permissions( 'read' ) ) {
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
	 * @since 1.7.0
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

		if ( ! $this->permission->rest_check_quiz_reviews_permissions( 'read', absint( $request['id'] ) ) ) {
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
	 * @since 1.7.0
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

 		if ( ! $this->permission->rest_check_quiz_reviews_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create quiz reviews.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['author_id'] ) && absint( $request['author_id'] ) === 0 ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, author ID cannot be empty or zero.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['author_id'] ) && absint( $request['author_id'] ) !== get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create quiz reviews for others.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$quiz = masteriyo_get_quiz( absint( $request['quiz_id'] ) );

		if ( is_null( $quiz ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid quiz ID', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( $quiz->get_author_id() === get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you cannot create review for your own quiz.', 'masteriyo' ),
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
	 * @since 1.7.0
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
		// $rating = absint( $request['rating'] );

		// if ( ! $review->is_reply() && $rating <= 0 ) {
		// 	return new \WP_Error(
		// 		'masteriyo_rest_cannot_create',
		// 		__( 'Sorry, rating cannot be zero or less.', 'masteriyo' ),
		// 		array(
		// 			'status' => 400,
		// 		)
		// 	);
		// }
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}
		$review = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $review ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( get_current_user_id() !== $review->get_author_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete this resource.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_quiz_reviews_permissions( 'delete', absint( $request['id'] ) ) ) {
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
	 * @since 1.7.0
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

		$review = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $review ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		// $rating = absint( $request['rating'] );

		// if ( ! $review->is_reply() && $rating <= 0 ) {
		// 	return new \WP_Error(
		// 		'masteriyo_rest_cannot_create',
		// 		__( 'Sorry, rating cannot be zero or less.', 'masteriyo' ),
		// 		array(
		// 			'status' => 400,
		// 		)
		// 	);
		// }

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( get_current_user_id() !== $review->get_author_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update this resource.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_quiz_reviews_permissions( 'edit', absint( $request['id'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['quiz_id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you cannot move a review to another quiz.', 'masteriyo' ),
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
	 * @since 1.7.0
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
	 * Restore quiz review.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function restore_item( $request ) {
		$quiz_review = $this->get_object( (int) $request['id'] );

		if ( ! $quiz_review || 0 === $quiz_review->get_id() ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->comment_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		wp_untrash_comment( $quiz_review->get_id() );

		// Read quiz review again.
		$quiz_review = $this->get_object( (int) $request['id'] );

		$data     = $this->prepare_object_for_response( $quiz_review, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.7.0
	 *
	 * @param array $objects Quiz reviews data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Quiz reviews query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'         => $query_results['total'],
				'pages'         => $query_results['pages'],
				'current_page'  => $query_args['paged'],
				'per_page'      => $query_args['number'],
				'reviews_count' => $this->get_comments_count(),
			),
		);
	}

}
