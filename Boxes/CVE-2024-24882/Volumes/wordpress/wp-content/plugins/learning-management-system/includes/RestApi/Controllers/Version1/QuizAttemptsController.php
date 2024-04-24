<?php
/**
 * Quiz attempt rest controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;
use Masteriyo\Models\QuizAttempt;
use Masteriyo\Query\QuizAttemptQuery;
use Masteriyo\RestApi\Controllers\Version1\CrudController;

class QuizAttemptsController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $rest_base = 'quizes/attempts';

	/**
	 * Route base.
	 *
	 * @since 1.5.37
	 *
	 * @var string
	 */
	protected $object_type = 'quiz-attempt';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.3.2
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

	/**
	 * Permission class.
	 *
	 * @since 1.3.2
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.3.2
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
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => function() {
						return is_user_logged_in() || masteriyo( 'session' )->get_user_id();
					},
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
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => 'is_user_logged_in',
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
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/last-attempt',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_last_attempt' ),
					'permission_callback' => function() {
						return is_user_logged_in() || masteriyo( 'session' )->get_user_id();
					},
					'args'                => array(
						'quiz_id' => array(
							'description'       => __( 'Quiz ID', 'masteriyo' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
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
						'ids' => array(
							'required'    => true,
							'description' => __( 'Quiz attempt IDs.', 'masteriyo' ),
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
	 * @since 1.3.2
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = array(
			'quiz_id'  => array(
				'description'       => __( 'Quiz ID', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'user_id'  => array(
				'description'       => __( 'User ID', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'status'   => array(
				'description'       => __( 'Quiz attempt status.', 'masteriyo' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_title',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'orderby'  => array(
				'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
				'type'              => 'string',
				'default'           => 'id',
				'enum'              => array(
					'id',
					'course_id',
					'quiz_id',
					'attempt_started_at',
					'attempt_ended_at',
				),
				'validate_callback' => 'rest_validate_request_arg',
			),
			'order'    => array(
				'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
				'type'              => 'string',
				'default'           => 'desc',
				'enum'              => array( 'asc', 'desc' ),
				'validate_callback' => 'rest_validate_request_arg',
			),
			'page'     => array(
				'description'       => __( 'Paginate the quiz attempts.', 'masteriyo' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
				'minimum'           => 1,
			),
			'per_page' => array(
				'description'       => __( 'Limit items per page.', 'masteriyo' ),
				'type'              => 'integer',
				'default'           => 10,
				'minimum'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
		);

		return $params;
	}

	/**
	 * Get the item schema, conforming to JSON Schema.
	 *
	 * @since 1.5.4
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'                       => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'course_id'                => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'quiz_id'                  => array(
					'description' => __( 'Quiz ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'user_id'                  => array(
					'description' => __( 'User ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'total_questions'          => array(
					'description' => __( 'Number of questions.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'total_answered_questions' => array(
					'description' => __( 'Number of answered questions.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'total_marks'              => array(
					'description' => __( 'Total marks.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'total_attempts'           => array(
					'description' => __( 'Number of attempts.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'total_correct_answers'    => array(
					'description' => __( 'Number of correct answers.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'total_incorrect_answers'  => array(
					'description' => __( 'Number of incorrect answers.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'earned_marks'             => array(
					'description' => __( 'Total earned marks/points.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'answers'                  => array(
					'description' => __( 'Answers given by user.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
				),
				'attempt_status'           => array(
					'description' => __( 'Quiz attempt status.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'attempt_started_at'       => array(
					'description' => __( 'Quiz attempt started time.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'attempt_ended_at'         => array(
					'description' => __( 'Quiz attempt ended time.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get object.
	 *
	 * @since 1.3.2
	 *
	 * @param  int $id Object ID.
	 * @return \Masteriyo\Models\QuizAttempt|false Model object or false.
	 */
	protected function get_object( $id ) {
		try {
			$id           = $id instanceof \stdClass ? $id->id : $id;
			$id           = $id instanceof QuizAttempt ? $id->get_id() : $id;
			$quiz_attempt = masteriyo( 'quiz-attempt' );
			$quiz_attempt->set_id( $id );
			$quiz_attempt_repo = masteriyo( 'quiz-attempt.store' );
			$quiz_attempt_repo->read( $quiz_attempt );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz_attempt;
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.6
	 *
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		if ( is_user_logged_in() ) {
			$result = $this->get_quiz_attempts_from_db( $query_args );
		} else {
			$result = $this->get_quiz_attempts_from_session( $query_args );
		}

		return $result;
	}

	/**
	 * Get quiz attempts from session.
	 *
	 * @since 1.3.8
	 *
	 * @param array $query_args
	 * @return array
	 */
	protected function get_quiz_attempts_from_session( $query_args ) {
		$session = masteriyo( 'session' );

		$quiz_id      = absint( $query_args['quiz_id'] );
		$all_attempts = $session->get( 'quiz_attempts', array() );
		$attempts     = isset( $all_attempts[ $quiz_id ] ) ? $all_attempts[ $quiz_id ] : array();
		$total_items  = count( $attempts );

		$attempts = array_map(
			function( $attempt ) {
				$quiz_attempt = masteriyo( 'quiz-attempt' );
				$quiz_attempt->set_id( 0 );
				$quiz_attempt->set_props( $attempt );

				return $quiz_attempt;
			},
			$attempts
		);

		return array(
			'objects' => array_reverse( $attempts ),
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Get quiz attempts from database.
	 *
	 * @since 1.3.8
	 *
	 * @param array $query_args
	 * @return array
	 */
	protected function get_quiz_attempts_from_db( $query_args ) {
		global $wpdb;

		$query   = new QuizAttemptQuery( $query_args );
		$objects = $query->get_quiz_attempts();

		/**
		 * Query for counting all quiz attempts rows.
		 */
		if ( ! empty( $query_args['user_id'] ) && ! empty( $query_args['quiz_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE user_id = %d
					AND quiz_id = %d",
					$query_args['user_id'],
					$query_args['quiz_id']
				)
			);
		} elseif ( ! empty( $query_args['user_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE user_id = %d",
					$query_args['user_id']
				)
			);
		} elseif ( ! empty( $query_args['quiz_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE quiz_id = %d",
					$query_args['quiz_id']
				)
			);
		} else {
			$total_items = $wpdb->get_var( "SELECT COUNT( * ) FROM {$wpdb->prefix}masteriyo_quiz_attempts" );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $objects ) ),
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.3.2
	 *
	 * @param array $objects Orders data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Orders query result data.
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
				'per_page'     => $query_args['per_page'],
			),
		);
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.3.2
	 *
	 * @param string $post_type Post type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $post_type, $context = 'read', $object_id = 0 ) {
		return true;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.0.6
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'per_page' => $request['per_page'],
			'paged'    => $request['page'],
			'order'    => $request['order'],
			'orderby'  => $request['orderby'],
		);

		if ( isset( $request['quiz_id'] ) ) {
			$args['quiz_id'] = absint( $request['quiz_id'] );
		}

		if ( isset( $request['user_id'] ) ) {
			$args['user_id'] = absint( $request['user_id'] );
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 1.0.6
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( 'masteriyo_rest_quiz_attempts_object_query', $args, $request );

		return $args;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.6
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_quiz_attempt_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.3.2
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get quiz attempt question answers data.
	 *
	 * @since 1.5.1
	 *
	 * @param mixed $attempt_answers
	 * @return array
	 */
	protected function get_answers_data( $attempt_answers ) {
		if ( empty( $attempt_answers ) || ! is_array( $attempt_answers ) ) {
			return null;
		}

		$new_attempt_answers = array();
		foreach ( $attempt_answers as $question_id => $attempt_answer ) {
			$question = masteriyo_get_question( $question_id );

			if ( ! $question ) {
				continue;
			}

			/**
			 * For backward compatibility when attempt_answers was store in following format.
			 * Old format: "answers" : [ '$question_id' => '$given_answered' ]
			 * New format: "answers" : [ '$question_id' => [ 'answered' => '$given_answered', 'correct' => 'boolean' ]  ]
			 */
			$given_answers = isset( $attempt_answer['answered'] ) ? $attempt_answer['answered'] : $attempt_answer;

			$new_attempt_answers[ $question_id ]['answered']       = $given_answers;
			$new_attempt_answers[ $question_id ]['correct']        = $question->check_answer( $given_answers );
			$new_attempt_answers[ $question_id ]['question']       = $question->get_name();
			$new_attempt_answers[ $question_id ]['points']         = $question->get_points();
			$new_attempt_answers[ $question_id ]['type']           = $question->get_type();
			$new_attempt_answers[ $question_id ]['correct_answer'] = $question->get_correct_answers();

		}

		/**
		 * Filter quiz attempt answers data.
		 *
		 * @since 1.5.1
		 *
		 * @param array $new_attempt_answers New attempt answers.
		 * @param mixed $attempt_answers Stored attempt answers.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizAttemptsController $controller REST quiz attempts controller object.
		 */
		return apply_filters( 'masteriyo_quiz_attempt_answers', $new_attempt_answers, $attempt_answers, $this );
	}

	/**
	 * Get quiz attempt data.
	 *
	 * @since 1.3.2
	 *
	 * @param Masteriyo\Models\QuizAttempt $quiz_attempt quiz attempt instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_attempt_data( $quiz_attempt, $context = 'view' ) {
		$data = array(
			'id'                       => $quiz_attempt->get_id( $context ),
			'total_questions'          => $quiz_attempt->get_total_questions( $context ),
			'total_answered_questions' => $quiz_attempt->get_total_answered_questions( $context ),
			'total_marks'              => $quiz_attempt->get_total_marks( $context ),
			'total_attempts'           => $quiz_attempt->get_total_attempts( $context ),
			'total_correct_answers'    => $quiz_attempt->get_total_correct_answers( $context ),
			'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers( $context ),
			'earned_marks'             => $quiz_attempt->get_earned_marks( $context ),
			'answers'                  => $this->get_answers_data( $quiz_attempt->get_answers( $context ) ),
			'attempt_status'           => $quiz_attempt->get_attempt_status( $context ),
			'attempt_started_at'       => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_started_at( $context ) ),
			'attempt_ended_at'         => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_ended_at( $context ) ),
			'course'                   => null,
			'quiz'                     => null,
			'user'                     => null,
		);

		$course = masteriyo_get_course( $quiz_attempt->get_course_id( $context ) );
		$quiz   = masteriyo_get_quiz( $quiz_attempt->get_quiz_id( $context ) );
		$user   = masteriyo_get_user( $quiz_attempt->get_user_id( $context ) );

		if ( $course ) {
			$data['course'] = array(
				'id'   => $course->get_id(),
				'name' => $course->get_name(),
			);
		}

		if ( $quiz ) {
			$data['quiz'] = array(
				'id'        => $quiz->get_id(),
				'name'      => $quiz->get_name(),
				'pass_mark' => $quiz->get_pass_mark(),
				'duration'  => $quiz->get_duration(),
			);
		}

		if ( ! is_null( $user ) && ! is_wp_error( $user ) ) {
			$data['user'] = array(
				'id'           => $user->get_id(),
				'display_name' => $user->get_display_name(),
				'first_name'   => $user->get_first_name(),
				'last_name'    => $user->get_last_name(),
				'email'        => $user->get_email(),
			);
		}

		/**
		 * Filter quiz attempt rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Quiz attempt data.
		 * @param Masteriyo\Models\QuizAttempt $quiz_attempt Quiz attempt object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizAttemptsController $controller REST quiz attempts controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $quiz_attempt, $context, $this );
	}

	/**
	 * Prepare a single quiz attempt object for create or update.
	 *
	 * @since 1.5.4
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @param boolean $creating If is creating a new object.
	 *
	 * @return \WP_Error|\Masteriyo\Models\QuizAttempt
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id           = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$quiz_attempt = masteriyo( 'quiz-attempt' );

		if ( 0 !== $id ) {
			$quiz_attempt->set_id( $id );
			$quiz_attempt_repo = masteriyo( 'quiz-attempt.store' );
			$quiz_attempt_repo->read( $quiz_attempt );
		}

		if ( isset( $request['course_id'] ) ) {
			$quiz_attempt->set_course_id( $request['course_id'] );
		}

		if ( isset( $request['quiz_id'] ) ) {
			$quiz_attempt->set_quiz_id( $request['quiz_id'] );
		}

		if ( isset( $request['user_id'] ) ) {
			$quiz_attempt->set_user_id( $request['user_id'] );
		}

		if ( isset( $request['total_questions'] ) ) {
			$quiz_attempt->set_total_questions( $request['total_questions'] );
		}

		if ( isset( $request['total_answered_questions'] ) ) {
			$quiz_attempt->set_total_answered_questions( $request['total_answered_questions'] );
		}

		if ( isset( $request['total_marks'] ) ) {
			$quiz_attempt->set_total_marks( $request['total_marks'] );
		}

		if ( isset( $request['total_attempts'] ) ) {
			$quiz_attempt->set_total_attempts( $request['total_attempts'] );
		}

		if ( isset( $request['total_correct_answers'] ) ) {
			$quiz_attempt->set_total_correct_answers( $request['total_correct_answers'] );
		}

		if ( isset( $request['total_incorrect_answers'] ) ) {
			$quiz_attempt->set_total_incorrect_answers( $request['total_incorrect_answers'] );
		}

		if ( isset( $request['earned_marks'] ) ) {
			$quiz_attempt->set_earned_marks( $request['earned_marks'] );
		}

		if ( isset( $request['answers'] ) ) {
			$quiz_attempt->set_answers( $request['answers'] );
		}

		if ( isset( $request['attempt_status'] ) ) {
			$quiz_attempt->set_attempt_status( $request['attempt_status'] );
		}

		if ( isset( $request['attempt_started_at'] ) ) {
			$quiz_attempt->set_attempt_started_at( $request['attempt_started_at'] );
		}

		if ( isset( $request['attempt_ended_at'] ) ) {
			$quiz_attempt->set_attempt_ended_at( $request['attempt_ended_at'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.5.4
		 *
		 * @param Masteriyo\Models\QuizAttempt $quiz_attempt Quiz attempt object.
		 * @param WP_REST_Request $request Request object.
		 * @param boolean $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $quiz_attempt, $request, $creating );
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 1.4.7
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$instructor = masteriyo_get_current_instructor();

		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$attempt_id = absint( $request['id'] );
		$attempt    = $this->get_object( $attempt_id );

		if ( ! $attempt ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->object_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		$course = masteriyo_get_course( $attempt->get_course_id() );

		if ( ! $course ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->object_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		if ( masteriyo_is_current_user_post_author( $course->get_id() ) ) {
			return true;
		}

		return new \WP_Error(
			'masteriyo_rest_cannot_delete',
			__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
			array(
				'status' => rest_authorization_required_code(),
			)
		);
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @since 1.5.4
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$quiz_attempt = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $quiz_attempt ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$instructor = masteriyo_get_current_instructor();

		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, your account has not been approved.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! masteriyo_is_current_user_post_author( $quiz_attempt->get_course_id() ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update this resource.', 'masteriyo' ),
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
	 * @since 1.5.4
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		$quiz = masteriyo_get_quiz( absint( $request['quiz_id'] ) );

		if ( is_null( $quiz ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Quiz does not exist.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		$course = masteriyo_get_course( $quiz->get_course_id() );

		if ( is_null( $course ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Course does not exist.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( ! empty( $request['course_id'] ) && $quiz->get_course_id() !== absint( $request['course_id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid course ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$instructor = masteriyo_get_current_instructor();

		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, your account has not been approved.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! masteriyo_is_current_user_post_author( $quiz->get_course_id() ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resource for others.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Return last attempt of quiz.
	 *
	 * @since 1.5.28
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_last_attempt( $request ) {
		$quiz_id = isset( $request['quiz_id'] ) ? absint( $request['quiz_id'] ) : 0;
		$quiz    = masteriyo_get_quiz( $quiz_id );

		if ( is_null( $quiz ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_quiz_id',
				__( 'Invalid quiz ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		$attempts = $this->get_objects(
			array(
				'quiz_id'  => $quiz_id,
				'user_id'  => masteriyo_get_current_user_id(),
				'order'    => 'desc',
				'per_page' => 1,
			)
		);

		$last_attempt = current( $attempts['objects'] );

		if ( empty( $last_attempt ) ) {
			return new \WP_Error(
				'masteriyo_rest_last_quiz_attempt_not_found',
				__( 'Last quiz attempt not found.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		return $this->prepare_object_for_response( $last_attempt, $request );
	}

	/**
	 * Check if a given request has access to delete items.
	 *
	 * @since 1.6.5
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function delete_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$instructor = masteriyo_get_current_instructor();
		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		return false;
	}

	/**
	 * Delete multiple items.
	 *
	 * @since 1.6.5
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function delete_items( $request ) {
		$objects         = array_map( 'masteriyo_get_quiz_attempt', $request['ids'] );
		$deleted_objects = array();

		foreach ( $objects as $object ) {
			$data = $this->prepare_object_for_response( $object, $request );

			$object->delete( true, $request->get_params() );

			if ( 0 === $object->get_id() ) {
				$deleted_objects[] = $this->prepare_response_for_collection( $data );
			}
		}

		if ( empty( $deleted_objects ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_bulk_delete',
				/* translators: %s: post type */
				sprintf( __( 'The %s cannot be bulk deleted.', 'masteriyo' ), $this->object_type ),
				array( 'status' => 500 )
			);
		}

		/**
		 * Fires after a multiple objects is deleted or trashed via the REST API.
		 *
		 * @since 1.6.5
		 *
		 * @param array $deleted_objects Objects collection which are deleted.
		 * @param array $objects Objects which are supposed to be deleted.
		 * @param \WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "masteriyo_rest_bulk_delete_{$this->object_type}_objects", $deleted_objects, $objects, $request );

		return rest_ensure_response( $deleted_objects );
	}
}
