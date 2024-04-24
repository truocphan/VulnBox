<?php
/**
 * Analytics controller.
 *
 * @since 1.6.7
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Helper\Permission;
use Masteriyo\PostType\PostType;
use Masteriyo\DateTime;
use Masteriyo\Enums\CommentStatus;
use Masteriyo\Enums\CommentType;
use Masteriyo\Roles;

class AnalyticsController extends CrudController {

	/**
	 * Route base.
	 *
	 * @since 1.6.7
	 *
	 * @var string
	 */
	protected $rest_base = 'analytics';

	/**
	 * Permission class.
	 *
	 * @since 1.6.7
	 *
	 * @var Permission
	 */
	protected $permission;

	/**
	 * Object type.
	 *
	 * @since 1.6.7
	 *
	 * @var string
	 */
	protected $object_type = 'analytics';

	/**
	 * Constructor.
	 *
	 * @since 1.6.7
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.7
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
					'args'                => array(
						'start_date' => array(
							'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'masteriyo' ),
							'type'              => 'string',
							'format'            => 'date-time',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'end_date'   => array(
							'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'masteriyo' ),
							'type'              => 'string',
							'format'            => 'date-time',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			)
		);
	}

	/**
	 * Get a collection of courses data.
	 *
	 * @since 1.6.7
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$items    = $this->prepare_items_for_response( $request );
		$response = rest_ensure_response( $items );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.6.7
		 *
		 * @param \WP_REST_Response $response The response object.
		 * @param array             $items Analytics data.
		 * @param \WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $items, $request );
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @since 1.6.7
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		return current_user_can( 'manage_options' ) || current_user_can( 'manage_masteriyo_settings' ) || current_user_can( 'edit_courses' );
	}

	/**
	 * Prepare items for response.
	 *
	 * @since 1.6.7
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_REST_Response
	 */
	protected function prepare_items_for_response( \WP_REST_Request $request ) {
		$start_date = $request->get_param( 'start_date' );
		$end_date   = $request->get_param( 'end_date' );
		$items      = array();

		$courses_data = $this->get_courses_data();
		$course_ids   = $courses_data['ids'];

		$items['courses'] = array(
			'total' => $courses_data['total'],
		);

		$items['lessons']           = $this->get_lessons_data( $course_ids );
		$items['quizzes']           = $this->get_quizzes_data( $course_ids );
		$items['questions']         = $this->get_questions_data( $course_ids );
		$items['questions_answers'] = $this->get_questions_answers_data( $course_ids );
		$items['reviews']           = $this->get_reviews_data( $course_ids );
		$items['instructors']       = $this->get_instructors_data();
		$items['user_courses']      = $this->get_enrolled_courses_data( $course_ids, $start_date, $end_date );

		/**
		 * Filters rest prepared analytics items.
		 *
		 * @since 1.6.7
		 *
		 * @param array $items Items data.
		 * @param \WP_REST_Request $request Request.
		 */
		return apply_filters( 'masteriyo_rest_prepared_analytics_items', $items, $request );
	}

	/**
	 * Get courses data.
	 *
	 * @since 1.6.7
	 *
	 * @return array
	 */
	protected function get_courses_data() {
		$query = new \WP_Query(
			array(
				'post_status'    => PostStatus::PUBLISH,
				'post_type'      => PostType::COURSE,
				'posts_per_page' => -1,
				'author'         => masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ? null : get_current_user_id(),
				'fields'         => 'ids',
			)
		);

		return array(
			'ids'   => $query->posts,
			'total' => $query->post_count,
		);
	}

	/**
	 * Get lessons count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_lessons_data( $course_ids ) {
		$data = array(
			'total' => 0,
		);

		if ( $course_ids ) {
			$query         = new \WP_Query(
				array(
					'post_status'    => PostStatus::PUBLISH,
					'post_type'      => PostType::LESSON,
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => '_course_id',
							'value'   => $course_ids,
							'compare' => 'IN',
						),
					),
					'fields'         => 'ids',
				)
			);
			$data['total'] = $query->found_posts;
		}

		return $data;
	}

	/**
	 * Get quizzes count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_quizzes_data( $course_ids ) {
		$data = array(
			'total' => 0,
		);

		if ( $course_ids ) {
			$query         = new \WP_Query(
				array(
					'post_status'    => PostStatus::PUBLISH,
					'post_type'      => PostType::QUIZ,
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => '_course_id',
							'value'   => $course_ids,
							'compare' => 'IN',
						),
					),
					'fields'         => 'ids',
				)
			);
			$data['total'] = $query->found_posts;
		}

		return $data;
	}

	/**
	 * Get questions count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_questions_data( $course_ids ) {
		$data = array(
			'total' => 0,
		);

		if ( $course_ids ) {
			$query         = new \WP_Query(
				array(
					'post_status'    => PostStatus::PUBLISH,
					'post_type'      => PostType::QUESTION,
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => '_course_id',
							'value'   => $course_ids,
							'compare' => 'IN',
						),
					),
					'fields'         => 'ids',
				)
			);
			$data['total'] = $query->found_posts;
		}

		return $data;
	}

	/**
	 * Get instructors count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_instructors_data() {
		$query = new \WP_User_Query(
			array(
				'role'        => Roles::INSTRUCTOR,
				'number'      => 1,
				'fields'      => 'ids',
				'count_total' => true,
			)
		);

		return array(
			'total' => $query->get_total(),
		);
	}


	/**
	 * Get reviews count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_reviews_data( $course_ids ) {
		$data = array(
			'total' => 0,
		);

		if ( $course_ids ) {
			$query         = new \WP_Comment_Query(
				array(
					'type'     => CommentType::COURSE_REVIEW,
					'status'   => CommentStatus::APPROVE_STR,
					'post__in' => $course_ids,
					'count'    => true,
					'number'   => 1,
				)
			);
			$data['total'] = $query->get_comments();
		}

		return $data;
	}

	/**
	 * Get question/answers count.
	 *
	 * @since 1.6.7
	 *
	 * @param array $course_ids Course IDs.
	 *
	 * @return array
	 */
	protected function get_questions_answers_data( $course_ids ) {
		$data = array(
			'total' => 0,
		);

		if ( $course_ids ) {
			$query         = new \WP_Comment_Query(
				array(
					'type'     => CommentType::COURSE_QA,
					'status'   => CommentStatus::APPROVE_STR,
					'count'    => true,
					'post__in' => $course_ids,
					'number'   => 1,
				)
			);
			$data['total'] = $query->get_comments();
		}

		return $data;
	}

	/**
	 * Get enrolled courses data.
	 *
	 * @since 1.6.7
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return array
	 */
	protected function get_enrolled_courses_data( $course_ids, $start_date, $end_date ) {
		global $wpdb;

		$data = array();

		$data['total']    = masteriyo_get_user_courses_count_by_course( $course_ids );
		$data['students'] = masteriyo_count_enrolled_users( $course_ids );

		if ( $course_ids ) {
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$data['data'] = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT DATE(date_start) as date, COUNT(*) as count
					FROM {$wpdb->prefix}masteriyo_user_items
					WHERE item_id IN (" . implode( ',', $course_ids ) . ')
					AND DATE(date_start) >= %s AND DATE(date_start) <= %s
					GROUP BY DATE(date_start)',
					$start_date,
					$end_date
				),
				ARRAY_A
			);
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		}

		$data['data'] = $this->format_series_data( $data['data'] ?? array(), $start_date, $end_date, '1 day' );

		return $data;
	}

	/**
	 * Format series data.
	 *
	 * Prefills empty data with 0.
	 *
	 * @since 1.6.7
	 *
	 * @param array $data Table name.
	 * @param DateTime $start Start date.
	 * @param DateTime $end End date.
	 * @param string $interval Interval.
	 */
	protected function format_series_data( $data, $start, $end, $interval ) {
		$start = new \DateTime( $start );
		$end   = new \DateTime( $end );

		$end->modify( '+1 day' );

		$interval       = \DateInterval::createFromDateString( $interval );
		$period         = new \DatePeriod( $start, $interval, $end );
		$formatted_data = array();

		foreach ( $period as $date ) {
			$date  = $date->format( 'Y-m-d' );
			$found = array_search( $date, array_column( $data, 'date' ), true );

			if ( false !== $found ) {
				$data[ $found ]['count'] = absint( $data[ $found ]['count'] );
			}

			$formatted_data[] = wp_parse_args(
				false !== $found ? $data[ $found ] : array(),
				array(
					'date'  => $date,
					'count' => 0,
				)
			);
		}

		return $formatted_data;
	}
}
