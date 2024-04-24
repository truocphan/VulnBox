<?php
/**
 * Quiz rest controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Enums\QuizAttemptStatus;
use Masteriyo\Enums\SectionChildrenPostType;
use Masteriyo\Helper\Utils;
use Masteriyo\Helper\Permission;
use Masteriyo\Models\QuizAttempt;
use Masteriyo\RestApi\Controllers\Version1\QuestionsController;

class QuizesController extends PostsController {
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
	protected $rest_base = 'quizes';

	/**
	 * Object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-quiz';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

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
					'args'                => array(
						'force'    => array(
							'default'     => true,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
						'children' => array(
							'default'     => true,
							'description' => __( 'Whether to delete the children(questions) under the quiz.', 'masteriyo' ),
							'type'        => 'boolean',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/start_quiz',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'start_quiz' ),
					'permission_callback' => array( $this, 'start_quiz_permissions_check' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/check_answers',
			array(
				'args' => array(
					'id'   => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
						'required'    => true,
					),
					'data' => array(
						'description' => __( 'Data for checking answers (chosen answers list).', 'masteriyo' ),
						'type'        => 'object',
						'required'    => true,
					),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'check_answers' ),
					'permission_callback' => array( $this, 'check_answers_permission_check' ),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to start quiz.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function start_quiz_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$quiz_id = (int) $request['id'];
		$quiz    = masteriyo_get_quiz( $quiz_id );

		if ( ! $quiz ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_quiz',
				__( 'Invalid Quiz ID.', 'masteriyo' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		if ( $this->is_quiz_attempt_limit_reached( $quiz ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->object_type}_attempt_reached",
				__( 'The maximum number of attempts for the quiz have been reached.', 'masteriyo' ),
				array( 'status' => 403 )
			);
		}

		$course = masteriyo_get_course( $quiz->get_course_id() );

		if ( ! $course ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_course',
				__( 'Invalid course ID', 'masteriyo' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		if ( ! is_user_logged_in() && CourseAccessMode::OPEN !== $course->get_access_mode() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_logged_in',
				__( 'Please sign in to start the quiz.', 'masteriyo' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		$is_user_enrolled = masteriyo_can_start_course( $course->get_id(), get_current_user_id() );

		if ( ! $is_user_enrolled ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_start_quiz',
				__( 'Sorry, you are not allowed to start quiz. Please enroll a course first.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check and validate the quiz answers.
	 *
	 * @since 1.3.8
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool
	 */
	public function check_answers_permission_check( $request ) {
		$quiz_id      = absint( $request['id'] );
		$attempt_data = $this->is_quiz_started( $quiz_id );

		$quiz = masteriyo_get_quiz( $quiz_id );
		if ( ! $quiz ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_quiz',
				__( 'Invalid Quiz.', 'masteriyo' ),
				array( 'status' => 403 )
			);
		}

		if ( ! $attempt_data ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->object_type}_is_not_started",
				__( 'Quiz is not started.', 'masteriyo' ),
				array( 'status' => 403 )
			);
		}

		return true;
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

		// The quizzes should be order by menu which is the sort order.
		$params['order']['default']   = 'asc';
		$params['orderby']['default'] = 'menu_order';

		$params['slug']       = array(
			'description'       => __( 'Limit result set to quizzes with a specific slug.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['status']     = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to quizzes assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['course_id']  = array(
			'description'       => __( 'Limit results by course id.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['category']   = array(
			'description'       => __( 'Limit result set to quizzes assigned a specific category ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tag']        = array(
			'description'       => __( 'Limit result set to quizzes assigned a specific tag ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['difficulty'] = array(
			'description'       => __( 'Limit result set to quizzes assigned a specific difficulty ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|WP_Post|Masteriyo\Models\Quiz $object Object ID or WP_Post or Quiz object.
	 *
	 * @return Masteriyo\Models\Quiz|WP_Error Quiz object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}

			$quiz = masteriyo( 'quiz' );
			$quiz->set_id( $id );
			$quiz_repo = masteriyo( 'quiz.store' );
			$quiz_repo->read( $quiz );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz;
	}

	/**
	 * Get Question object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|WP_Post|Model $object Object ID or WP_Post or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_question_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}
			$type     = get_post_meta( $id, '_type', true );
			$question = masteriyo( "question.${type}" );
			$question->set_id( $id );
			$question_repo = masteriyo( 'question.store' );
			$question_repo->read( $question );
		} catch ( \Exception $e ) {
			return false;
		}

		return $question;
	}

	/**
	 * Collect data after starting quiz.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function start_quiz( $request ) {
		if ( is_user_logged_in() ) {
			$response = $this->save_quiz_attempts_in_db( $request );
		} else {
			$response = $this->save_quiz_attempts_in_session( $request );
		}

		/**
		 * Filters start-quiz API response.
		 *
		 * @since 1.3.0
		 *
		 * @param WP_REST_Request $response The response object.
		 */
		return apply_filters( 'masteriyo_start_quiz_rest_response', rest_ensure_response( $response ) );
	}

	/**
	 * Check whether the attempt limit is reached or not.
	 *
	 * @since 1.3.8
	 *
	 * @param Masteriyo\Models\Quiz $quiz
	 * @return boolean
	 */
	protected function is_quiz_attempt_limit_reached( $quiz ) {
		$reached = false;

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();

			$reached = masteriyo_is_quiz_attempt_limit_reached( $quiz, $user_id );
		} else {
			$session = masteriyo( 'session' );

			$all_attempts = $session->get( 'quiz_attempts', array() );
			$attempts     = isset( $all_attempts[ $quiz->get_id() ] ) ? $all_attempts[ $quiz->get_id() ] : array();

			$reached = 0 !== $quiz->get_attempts_allowed() && count( $attempts ) >= $quiz->get_attempts_allowed();

			/**
			 * Filters boolean: whether the attempt limit is reached or not, true if yes.
			 *
			 * @since 1.3.8
			 *
			 * @param boolean $bool Whether the attempt limit is reached or not, true if yes.
			 * @param Masteriyo\models\Quiz $quiz Quiz object.
			 * @param integer $user_id The user ID.
			 */
			$reached = apply_filters( 'masteriyo_is_quiz_attempt_limit_reached', $reached, $quiz, $session->get_user_id() );
		}

		/**
		 * Filters boolean: whether the attempt limit is reached or not, true if yes.
		 *
		 * @since 1.3.8
		 *
		 * @param boolean $bool Whether the attempt limit is reached or not, true if yes.
		 * @param Masteriyo\models\Quiz $quiz Quiz object.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizesController $controller Quiz controller.
		 */
		return apply_filters( 'masteriyo_rest_is_quiz_attempt_limit_reached', $reached, $quiz, $this );
	}

	/**
	 * Save quiz attempts in session.
	 *
	 * @since 1.3.8
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function save_quiz_attempts_in_session( $request ) {
		$session = masteriyo( 'session' );

		$quiz         = masteriyo_get_quiz( absint( $request['id'] ) );
		$all_attempts = $session->get( 'quiz_attempts', array() );
		$attempts     = isset( $all_attempts[ $quiz->get_id() ] ) ? $all_attempts[ $quiz->get_id() ] : array();

		$attempt_data = array(
			'id'                       => count( $all_attempts ) + 1,
			'course_id'                => $quiz->get_course_id(),
			'quiz_id'                  => $quiz->get_id(),
			'user_id'                  => $session->get_user_id(),
			'total_attempts'           => count( $attempts ) + 1,
			'total_answered_questions' => 0,
			'attempt_status'           => 'attempt_started',
			'attempt_started_at'       => current_time( 'mysql', true ),
		);

		$all_attempts[ $quiz->get_id() ][] = $attempt_data;

		$session->put( 'quiz_attempts', $all_attempts );

		return $attempt_data;
	}

	/**
	 * Save quiz attempts in database.
	 *
	 * @since 1.3.8
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function save_quiz_attempts_in_db( $request ) {
		$user_id         = get_current_user_id();
		$quiz_id         = absint( $request['id'] );
		$attempted_count = masteriyo_get_quiz_attempt_count( $quiz_id, $user_id );
		$quiz            = masteriyo_get_quiz( $quiz_id );

		$last_attempt = $this->is_quiz_started( $quiz_id );
		$last_attempt = $last_attempt ? $last_attempt : masteriyo_create_quiz_attempt_object();

		$last_attempt->set_props(
			array(
				'course_id'      => $quiz->get_course_id(),
				'quiz_id'        => $quiz->get_id(),
				'user_id'        => $user_id,
				'total_attempts' => ++$attempted_count,
			)
		);

		$last_attempt->save();

		return $this->get_quiz_attempt_data( $last_attempt );
	}

	/**
	 * Check whether the quiz is started or not.
	 *
	 * @since 1.3.8
	 *
	 * @param int $quiz_id Quiz ID
	 * @return boolean|stdClass Return false if the quiz is not started else return last attempt data.
	 */
	protected function is_quiz_started( $quiz_id ) {
		$is_quiz_started = false;

		if ( is_user_logged_in() ) {
			$is_quiz_started = masteriyo_is_quiz_started( $quiz_id );
		} else {
			$session = masteriyo( 'session' );

			$all_attempts = $session->get( 'quiz_attempts', array() );
			$attempts     = isset( $all_attempts[ $quiz_id ] ) ? $all_attempts[ $quiz_id ] : array();
			$attempts     = array_reverse( $attempts );

			foreach ( $attempts as $attempt ) {
				if ( $attempt['quiz_id'] === $quiz_id && QuizAttemptStatus::STARTED === $attempt['attempt_status'] ) {
					$is_quiz_started = masteriyo_create_quiz_attempt_object();

					$data = wp_parse_args(
						$attempt,
						array(
							'course_id'                => 0,
							'quiz_id'                  => 0,
							'user_id'                  => 0,
							'total_questions'          => 0,
							'total_answered_questions' => 0,
							'total_marks'              => 0,
							'total_attempts'           => 0,
							'total_correct_answers'    => 0,
							'total_incorrect_answers'  => 0,
							'earned_marks'             => 0,
							'attempt_status'           => QuizAttemptStatus::STARTED,
							'attempt_started_at'       => null,
							'attempt_ended_at'         => null,
						)
					);
					$is_quiz_started->set_props( $data );

					break;
				}
			}
		}

		/**
		 * Filters boolean: whether the quiz is started or not, true if yes.
		 *
		 * @since 1.3.8
		 *
		 * @param boolean $is_quiz_started Whether the quiz is started or not, true if yes.
		 * @param integer $quiz_id Quiz ID.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizesController $controller Quiz controller.
		 */
		return apply_filters( 'masteriyo_rest_is_quiz_started', $is_quiz_started, $quiz_id, $this );
	}

	/**
	 * Check given answer and collect the results.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function check_answers( $request ) {
		$answers = $request['data'];
		$quiz_id = absint( $request['id'] );

		if ( isset( $answers['id'] ) ) {
			unset( $answers['id'] );
		}

		$last_attempt   = $this->is_quiz_started( $quiz_id );
		$attempt_detail = $this->grade_quiz( $quiz_id, $answers );

		if ( is_user_logged_in() ) {
			$last_attempt->set_props( $attempt_detail );
			$last_attempt->save();
		} else {
			$session      = masteriyo( 'session' );
			$all_attempts = $session->get( 'quiz_attempts', array() );
			$attempts     = isset( $all_attempts[ $quiz_id ] ) ? $all_attempts[ $quiz_id ] : array();

			$attempt_detail['total_attempts'] = count( $attempts );

			$last_attempt_arr = array_pop( $attempts );
			$last_attempt     = masteriyo_create_quiz_attempt_object();
			$last_attempt->set_props( $last_attempt_arr );

			$attempts[]               = wp_parse_args( $attempt_detail, $last_attempt_arr );
			$all_attempts[ $quiz_id ] = $attempts;

			$session->put( 'quiz_attempts', $all_attempts );
		}

		$response = $this->prepare_quiz_attempts_for_response( $last_attempt );

		/**
		 * Filters answer-check API response.
		 *
		 * @since 1.3.8
		 *
		 * @param WP_REST_Request $response The response object.
		 */
		return apply_filters( 'masteriyo_answer_check_rest_response', $response );
	}

	/**
	 * Grade quiz.
	 *
	 * @since 1.3.8
	 *
	 * @param array $answers
	 * @return array
	 */
	protected function grade_quiz( $quiz_id, $answers ) {
		$total_earned_marks      = 0;
		$attempt_questions       = 0;
		$total_correct_answers   = 0;
		$total_incorrect_answers = 0;
		$answers_data            = array();

		$quiz           = masteriyo_get_quiz( $quiz_id );
		$quiz_questions = masteriyo_get_quiz_questions( $quiz_id );

		foreach ( $answers as $question_id => $value ) {
			$object = $this->get_question_object( absint( $question_id ) );

			if ( ! $object || 0 === $object->get_id() ) {
				return new \WP_Error(
					"masteriyo_rest_{$this->post_type}_invalid_id",
					__( 'Invalid ID', 'masteriyo' ),
					array( 'status' => 404 )
				);
			}

			$is_correct = $object->check_answer( $value, 'view' );

			$answers_data[ $question_id ] = array(
				'answered' => $value,
				'correct'  => $is_correct,
			);

			$is_correct ? $total_correct_answers++ : $total_incorrect_answers++;

			$question_mark       = $is_correct ? $object->get_points() : 0;
			$total_earned_marks += $question_mark;
			$attempt_questions++;
		}

		return array(
			'total_marks'              => $quiz->get_full_mark(),
			'earned_marks'             => $total_earned_marks,
			'total_questions'          => count( $quiz_questions ),
			'total_answered_questions' => $attempt_questions,
			'total_correct_answers'    => $total_correct_answers,
			'total_incorrect_answers'  => $total_incorrect_answers,
			'answers'                  => $answers_data,
			'attempt_status'           => QuizAttemptStatus::ENDED,
			'attempt_ended_at'         => time(),
		);
	}

	/**
	 * Get all quiz attempts according to user_id and quiz_id.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function get_attempts( $request ) {
		$parameters = $request->get_params();

		$quiz_id = (int) $parameters['quiz_id'];
		$user_id = isset( $parameters['user_id'] ) ? (int) $parameters['user_id'] : masteriyo_get_current_user_id();

		$query_vars = array(
			'user_id'  => $user_id,
			'quiz_id'  => $quiz_id,
			'status'   => isset( $parameters['status'] ) ? $parameters['status'] : '',
			'per_page' => $parameters['per_page'],
			'paged'    => $parameters['paged'],
			'orderby'  => $parameters['orderby'],
			'order'    => $parameters['order'],
		);

		$multi_attempts_data = array();
		$attempts            = masteriyo_get_quiz_attempts( $query_vars );

		if ( empty( $attempts ) ) {
			return array();
		}

		foreach ( $attempts as $attempt ) {
			$multi_attempts_data[] = (array) $attempt;
		}

		$response = $this->prepare_quiz_attempts_for_response( $multi_attempts_data );

		return $response;
	}

	/**
	 * Get quiz attempt according to id, user_id and quiz_id.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function get_attempt( $request ) {
		$id      = (int) $request['id'];
		$attempt = masteriyo_get_quiz_attempt( $id );

		if ( is_null( $attempt ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_attempt_not_found",
				__( 'Quiz attempt not found.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() || masteriyo_is_current_user_instructor() ) {
			return $attempt;
		}

		$course = get_post_meta( $attempt->get_quiz_id(), '_course_id', true );

		if ( ! masteriyo_can_start_course( $course ) ) {
			return new \WP_Error(
				'masteriyo_rest_course_empty_id',
				__( 'Something went wrong with the course. Please check if a quiz attached with the course.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		if ( get_current_user_id() !== absint( $attempt->get_user_id() ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_attempt_not_found",
				__( 'You are not allowed to read other\'s quiz attempts', 'masteriyo' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return $this->prepare_quiz_attempts_for_response( $attempt );
	}

	/**
	 * Changed value from string to integer for response.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt
	 * @return array
	 */
	protected function prepare_quiz_attempts_for_response( $quiz_attempt ) {
		return array(
			'id'                       => $quiz_attempt->get_id(),
			'course_id'                => $quiz_attempt->get_course_id(),
			'quiz_id'                  => $quiz_attempt->get_quiz_id(),
			'total_questions'          => $quiz_attempt->get_total_questions(),
			'total_answered_questions' => $quiz_attempt->get_total_answered_questions(),
			'total_marks'              => $quiz_attempt->get_total_marks(),
			'total_attempts'           => $quiz_attempt->get_total_attempts(),
			'total_correct_answers'    => $quiz_attempt->get_total_correct_answers(),
			'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers(),
			'earned_marks'             => $quiz_attempt->get_earned_marks(),
			'answers'                  => $quiz_attempt->get_answers(),
			'attempt_status'           => $quiz_attempt->get_attempt_status(),
			'attempt_started_at'       => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_started_at() ),
			'attempt_ended_at'         => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_ended_at() ),
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
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_quiz_data( $object, $context );

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
	 * Get quiz data.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Quiz   $quiz Quiz instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_data( $quiz, $context = 'view' ) {
		$section = masteriyo_get_section( $quiz->get_parent_id( $context ) );
		$course  = masteriyo_get_course( $quiz->get_course_id( $context ) );

		/**
		 * Filters short description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $short_description The short description.
		 */
		$short_description = 'view' === $context ? apply_filters( 'masteriyo_short_description', $quiz->get_short_description() ) : $quiz->get_short_description();

		$data = array(
			'id'                                => $quiz->get_id(),
			'name'                              => wp_specialchars_decode( $quiz->get_name( $context ) ),
			'slug'                              => $quiz->get_slug( $context ),
			'permalink'                         => $quiz->get_permalink(),
			'preview_link'                      => $quiz->get_preview_link(),
			'parent_id'                         => $quiz->get_parent_id( $context ),
			'course_id'                         => $quiz->get_course_id( $context ),
			'course_name'                       => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'menu_order'                        => $quiz->get_menu_order( $context ),
			'parent_menu_order'                 => $section ? $section->get_menu_order( $context ) : 0,
			'status'                            => $quiz->get_status( $context ),
			'description'                       => 'view' === $context ? wpautop( do_shortcode( $quiz->get_description() ) ) : $quiz->get_description( $context ),
			'short_description'                 => $short_description,
			'date_created'                      => masteriyo_rest_prepare_date_response( $quiz->get_date_created( $context ) ),
			'date_modified'                     => masteriyo_rest_prepare_date_response( $quiz->get_date_modified( $context ) ),
			'pass_mark'                         => $quiz->get_pass_mark( $context ),
			'full_mark'                         => $quiz->get_full_mark( $context ),
			'duration'                          => $quiz->get_duration( $context ),
			'attempts_allowed'                  => $quiz->get_attempts_allowed( $context ),
			'questions_display_per_page'        => $quiz->get_questions_display_per_page( $context ),
			'questions_display_per_page_global' => masteriyo_get_setting( 'quiz.styling.questions_display_per_page' ),
			'questions_count'                   => $quiz->get_questions_count(),
			'navigation'                        => $this->get_navigation_items( $quiz, $context ),
		);

		/**
		 * Filter quiz rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Quiz data.
		 * @param Masteriyo\Models\Quiz $quiz Quiz object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\QuizesController $controller REST Quizzes controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $quiz, $context, $this );
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
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = $request['status'];

		if ( ! empty( $request['course_id'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => '_course_id',
					'value'   => absint( $request['course_id'] ),
					'compare' => '=',
				),
			);
		}

		// Taxonomy query to filter quizzes by type, category,
		// tag, shipping class, and attribute.
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'quiz_cat'        => 'category',
			'quiz_tag'        => 'tag',
			'quiz_difficulty' => 'difficulty',
		);

		// Set tax_query for each passed arg.
		foreach ( $taxonomies as $taxonomy => $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $request[ $key ],
				);
			}
		}

		// Build tax_query if taxonomies are set.
		if ( ! empty( $tax_query ) ) {
			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array_merge( $tax_query, $args['tax_query'] ); // WPCS: slow query ok.
			} else {
				$args['tax_query'] = $tax_query; // WPCS: slow query ok.
			}
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'quiz_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
			);
		}

		return $args;
	}

	/**
	 * Get the quizzes schema, conforming to JSON Schema.
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
				'id'                         => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'                       => array(
					'description' => __( 'Quiz name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'                       => array(
					'description' => __( 'Quiz slug', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent_id'                  => array(
					'description' => __( 'Quiz parent ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'                  => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_name'                => array(
					'description' => __( 'Course name', 'masteriyo' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'menu_order'                 => array(
					'description' => __( 'Menu order, used to custom sort quizzes.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'                  => array(
					'description' => __( 'Quiz URL', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'               => array(
					'description' => __( "The date the quiz was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'           => array(
					'description' => __( 'The date the quiz was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'              => array(
					'description' => __( "The date the quiz was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'          => array(
					'description' => __( 'The date the quiz was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'                     => array(
					'description' => __( 'Quiz status (post status).', 'masteriyo' ),
					'type'        => 'string',
					'default'     => PostStatus::PUBLISH,
					'enum'        => array_merge( array_keys( get_post_statuses() ), array( 'future' ) ),
					'context'     => array( 'view', 'edit' ),
				),
				'description'                => array(
					'description' => __( 'Quiz description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description'          => array(
					'description' => __( 'Quiz short description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'pass_mark'                  => array(
					'description' => __( 'Quiz pass points', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'full_mark'                  => array(
					'description' => __( 'Quiz total points', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'duration'                   => array(
					'description' => __( 'Quiz duration (seconds)', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'attempts_allowed'           => array(
					'description' => __( 'Quiz attempts allowed.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'questions_display_per_page' => array(
					'description' => __( 'Quiz questions per page.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'                  => array(
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
	 * Prepare a single quiz for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$quiz = masteriyo( 'quiz' );

		if ( 0 !== $id ) {
			$quiz->set_id( $id );
			$quiz_repo = masteriyo( 'quiz.store' );
			$quiz_repo->read( $quiz );
		}

		// Quiz title.
		if ( isset( $request['name'] ) ) {
			$quiz->set_name( sanitize_text_field( $request['name'] ) );
		}

		// Quiz content.
		if ( isset( $request['description'] ) ) {
			$quiz->set_description( wp_slash( $request['description'] ) );
		}

		// Quiz excerpt.
		if ( isset( $request['short_description'] ) ) {
			$quiz->set_short_description( wp_kses_post( $request['short_description'] ) );
		}

		// Quiz status.
		if ( isset( $request['status'] ) ) {
			$quiz->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Quiz slug.
		if ( isset( $request['slug'] ) ) {
			$quiz->set_slug( $request['slug'] );
		}

		// Quiz parent id.
		if ( isset( $request['parent_id'] ) ) {
			$quiz->set_parent_id( $request['parent_id'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$quiz->set_course_id( $request['course_id'] );
		}

		// Quiz menu order.
		if ( isset( $request['menu_order'] ) ) {
			$quiz->set_menu_order( $request['menu_order'] );
		}

		// Automatically set the menu order if it's not set and the operation is POST.
		if ( ! isset( $request['menu_order'] ) && $creating ) {
			$query = new \WP_Query(
				array(
					'post_type'      => SectionChildrenPostType::all(),
					'post_status'    => PostStatus::all(),
					'posts_per_page' => 1,
					'post_parent'    => $request['parent_id'],
				)
			);

			$quiz->set_menu_order( $query->found_posts );
		}

		// Quiz pass mark.
		if ( isset( $request['pass_mark'] ) ) {
			$quiz->set_pass_mark( $request['pass_mark'] );
		}

		// Quiz full mark.
		if ( isset( $request['full_mark'] ) ) {
			$quiz->set_full_mark( $request['full_mark'] );
		}

		// Quiz duration.
		if ( isset( $request['duration'] ) ) {
			$quiz->set_duration( $request['duration'] );
		}

		// Quiz attempts allowed.
		if ( isset( $request['attempts_allowed'] ) ) {
			$quiz->set_attempts_allowed( $request['attempts_allowed'] );
		}

		// Quiz questions display per page.
		if ( isset( $request['questions_display_per_page'] ) ) {
			$quiz->set_questions_display_per_page( $request['questions_display_per_page'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$quiz->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
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
		 * @param Masteriyo\Database\Model $quiz Quiz object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $quiz, $request, $creating );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @since 1.0.0
	 *
	 * @param Model           $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links           = parent::prepare_links( $object, $request );
		$next_prev_links = $this->get_navigation_links( $object, $request );

		return $links + $next_prev_links;
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
		$course_id = absint( $request['course_id'] );
		$course    = get_post( $course_id );

		if ( is_null( $course ) || 'mto-course' !== $course->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$result = parent::create_item_permissions_check( $request );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}

	/**
	 * Return quiz attempt data.
	 *
	 * @since 1.3.8
	 *
	 * @since 1.5.37 Changed $quiz_attempt parameter from stdClass to \Masteriyo\Models\QuizAttempt
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt
	 * @return array
	 */
	protected function get_quiz_attempt_data( $quiz_attempt ) {
		return array(
			'id'                       => $quiz_attempt->get_id(),
			'course_id'                => $quiz_attempt->get_course_id(),
			'quiz_id'                  => $quiz_attempt->get_quiz_id(),
			'user_id'                  => $quiz_attempt->get_user_id(),
			'total_questions'          => $quiz_attempt->get_total_questions(),
			'total_answered_questions' => $quiz_attempt->get_total_answered_questions(),
			'total_marks'              => $quiz_attempt->get_total_marks(),
			'total_attempts'           => $quiz_attempt->get_total_attempts(),
			'total_correct_answers'    => $quiz_attempt->get_total_correct_answers(),
			'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers(),
			'earned_marks'             => $quiz_attempt->get_earned_marks(),
			'answers'                  => $quiz_attempt->get_answers(),
			'attempt_status'           => $quiz_attempt->get_attempt_status(),
			'attempt_started_at'       => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_started_at() ),
			'attempt_ended_at'         => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_ended_at() ),
		);
	}
}
