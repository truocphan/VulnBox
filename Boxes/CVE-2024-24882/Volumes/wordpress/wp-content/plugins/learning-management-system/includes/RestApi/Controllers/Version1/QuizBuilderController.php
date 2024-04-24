<?php
/**
 * Quiz builder REST API.
 *
 * Handles requests to the quizzes/builder endpoint.
 *
 * @author   mi5t4n
 * @category API
 * @package Masteriyo\RestApi
 * @since   1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;
use Masteriyo\RestApi\Controllers\Version1\PostsController;

/**
 * Quiz builder REST API. controller class.
 *
 * @package Masteriyo\RestApi
 * @extends CrudController
 */
class QuizBuilderController extends PostsController {

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
	protected $rest_base = 'quizbuilder';

	/**
	 * Object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_builder';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-quiz';

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
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( 'mto-quiz', 'read' ) ) {
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
	 * Get the quiz builder schema, conforming to JSON Schema.
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
				'questions' => array(
					'description' => __( 'Quiz contents (question IDs)', 'masteriyo' ),
					'type'        => 'array',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get the quiz contents.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function get_item( $request ) {
		$response = array();
		$quiz     = get_post( absint( $request['id'] ) );

		if ( is_null( $quiz ) || $this->post_type !== $quiz->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$objects = $this->get_quiz_contents( $request );

		foreach ( $objects as $object ) {
			if ( ! $this->check_item_permission( $this->post_type, 'read', $object->get_id() ) ) {
				continue;
			}

			$data       = $this->prepare_object_for_response( $object, $request );
			$response[] = $this->prepare_response_for_collection( $data );
		}

		return $response;
	}

	/**
	 * Get quiz contents(Questions).
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return Masteriyo\Models\Question\Question[]
	 */
	protected function get_quiz_contents( $request ) {
		$results = $this->get_objects(
			array(
				'post_parent'    => $request['id'],
				'post_type'      => 'mto-question',
				'orderby'        => 'menu_order',
				'order'          => 'asc',
				'posts_per_page' => -1,
			)
		);

		/**
		 * Filters quiz contents objects (i.e. questions).
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Question\Question[] $questions Quiz contents objects.
		 */
		return apply_filters( "masteriyo_{$this->object_type}_objects", $results['objects'] );
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
			$type     = get_post_meta( $post->ID, '_type', true );
			$question = masteriyo( "question.${type}" );
			$question->set_id( $post->ID );
			$question_repo = masteriyo( 'question.store' );
			$question_repo->read( $question );
		} catch ( \Exception $e ) {
			return false;
		}

		return $question;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 *
	 * @param  \Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_question_data( $object, $context );

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
	 * Get quiz child data.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.3
	 *
	 * @param Model $quiz_item Quiz instance.
	 * @param string     $context Request context.
	 *                            Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_child_data( $quiz_item, $context = 'view' ) {
		$data = array(
			'id'         => $quiz_item->get_id(),
			'name'       => $quiz_item->get_name( $context ),
			'permalink'  => $quiz_item->get_permalink( $context ),
			'type'       => $quiz_item->get_type( $context ),
			'menu_order' => $quiz_item->get_menu_order( $context ),
			'parent_id'  => $quiz_item->get_parent_id( $context ),
		);

		return $data;
	}

	/**
	 * Get quiz child data.
	 *
	 * @since 1.5.3
	 *
	 * @param \Masteriyo\Models\Question $question Question object.
	 * @param string     $context Request context.
	 *                            Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_question_data( $question, $context = 'view' ) {
		/**
		 * Filters question description.
		 *
		 * @since 1.5.3
		 *
		 * @param string $description Question description.
		 */
		$description = 'view' === $context ? apply_filters( 'masteriyo_description', $question->get_description() ) : $question->get_description();

		$data = array(
			'id'                     => $question->get_id(),
			'name'                   => wp_specialchars_decode( $question->get_name( $context ) ),
			'permalink'              => $question->get_permalink(),
			'status'                 => $question->get_status( $context ),
			'description'            => $description,
			'type'                   => $question->get_type( $context ),
			'parent_id'              => $question->get_parent_id( $context ),
			'course_id'              => $question->get_course_id( $context ),
			'menu_order'             => $question->get_menu_order( $context ),
			'answer_required'        => $question->get_answer_required( $context ),
			'randomize'              => $question->get_randomize( $context ),
			'points'                 => $question->get_points( $context ),
			'positive_feedback'      => $question->get_positive_feedback( $context ),
			'negative_feedback'      => $question->get_negative_feedback( $context ),
			'feedback'               => $question->get_feedback( $context ),
			'answers'                => $question->get_answers( $context ),
			'answers_decode_success' => $question->is_answers_decoded(),
		);

		/**
		 * Filter question rest response data.
		 *
		 * @since 1.5.3
		 *
		 * @param array $data Question data.
		 * @param Masteriyo\Models\Question $question Question object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuestionsController $controller REST Questions controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $question, $context, $this );
	}

	/**
	 * Format the quiz items according to the builder format.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.3
	 *
	 * @param \Masteriyo\Models\Question[] $questions Questions
	 * @return array
	 */
	protected function process_objects_collection( $questions ) {
		$results['questions']      = $questions;
		$question_ids              = wp_list_pluck( $questions, 'id' );
		$results['question_order'] = $question_ids;

		return $results;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return $this->permission->rest_check_post_permissions( $object_type, 'read', $object_id );
	}

	/**
	 * Check if a given request has access to create an item.
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

		if ( ! $this->permission->rest_check_post_permissions( 'mto-quiz', 'update', $request['id'] ) ) {
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
	 * Save an object data.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @return Model|WP_Error
	 */
	public function update_item( $request ) {
		$quiz = get_post( $request['id'] );

		if ( is_null( $quiz ) || $this->post_type !== $quiz->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		// Save section order.
		$this->save_question_order( $request );

		return $this->get_item(
			array(
				'id' => $quiz->ID,
			)
		);
	}

	/**
	 * Filter question.
	 *
	 * @since 1.0.0
	 *
	 * @param int $question
	 * @return array
	 */
	protected function filter_questions( $question ) {
		$post = get_post( absint( $question ) );

		return $post && 'mto-question' === $post->post_type;
	}

	/**
	 * Save question order.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	protected function save_question_order( $request ) {
		$questions = isset( $request['questions'] ) ? $request['questions'] : array();
		$questions = array_filter( $questions, array( $this, 'filter_questions' ) );

		foreach ( $questions as $menu_order => $question ) {
			$this->update_post( $question, $menu_order, $request['id'] );
		}
	}

	/**
	 * Update post if the parent id or menu order is changed.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Post ID.
	 */
	private function update_post( $id, $menu_order, $parent_id ) {
		$post = get_post( $id );

		if ( is_null( $post ) ) {
			return;
		}

		if ( $post->menu_order !== $menu_order || $post->post_parent !== $parent_id ) {
			wp_update_post(
				array(
					'ID'          => $post->ID,
					'menu_order'  => $menu_order,
					'post_parent' => $parent_id,
				)
			);
		}
	}

}
