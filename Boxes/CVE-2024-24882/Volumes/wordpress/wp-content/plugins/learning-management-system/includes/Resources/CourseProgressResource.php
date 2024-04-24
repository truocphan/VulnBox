<?php
/**
 * Resource handler for CourseProgress data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

use Masteriyo\Enums\CourseChildrenPostType;
use Masteriyo\Enums\CourseProgressPostType;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Query\CourseProgressItemQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for CourseProgress data.
 *
 * @since 1.6.9
 */
class CourseProgressResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $course_progress, $context = 'view' ) {
		$course  = masteriyo_get_course( $course_progress->get_course_id( $context ) );
		$summary = self::get_course_progress_summary( $course_progress );

		if ( 0 === $summary['total']['pending'] ) {
			$course_progress->set_status( CourseProgressStatus::COMPLETED );
		}

		$data = array(
			'id'               => $course_progress->get_id( $context ),
			'user_id'          => $course_progress->get_user_id( $context ),
			'course_id'        => $course_progress->get_course_id( $context ),
			'course_permalink' => get_the_permalink( $course_progress->get_course_id( $context ) ),
			'name'             => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'status'           => $course_progress->get_status( $context ),
			'started_at'       => masteriyo_rest_prepare_date_response( $course_progress->get_started_at( $context ) ),
			'modified_at'      => masteriyo_rest_prepare_date_response( $course_progress->get_modified_at( $context ) ),
			'completed_at'     => masteriyo_rest_prepare_date_response( $course_progress->get_completed_at( $context ) ),
			'items'            => self::get_course_progress_items( $course_progress ),
			'summary'          => $summary,
		);

		/**
		 * Filter course progress data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data CourseProgress data.
		 * @param \Masteriyo\Models\CourseProgress $course_progress CourseProgress object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_course_progress_resource_array', $data, $course_progress, $context );
	}

	/**
	 * Get course progress item data.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgressItem  $course_progress_item Course progress item object.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected static function get_course_progress_item_data( $course_progress_item, $context = 'view' ) {
		$video = '';

		if ( 'lesson' === $course_progress_item->get_item_type() ) {
			$video = get_post_meta( $course_progress_item->get_item_id( $context ), '_video_source_url', true );
		}

		$data = array(
			'item_id'    => $course_progress_item->get_item_id( $context ),
			'item_title' => wp_specialchars_decode( $course_progress_item->get_item_title( $context ) ),
			'item_type'  => $course_progress_item->get_item_type( $context ),
			'completed'  => $course_progress_item->get_completed( $context ),
			'video'      => ! empty( trim( $video ) ),
		);

		return $data;
	}

	/**
	 * Get course progress items.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress
	 *
	 * @return array
	 */
	protected static function get_course_progress_items( $course_progress ) {
		if ( is_user_logged_in() ) {
			$progress_items = self::get_course_progress_items_from_db( $course_progress );
		} else {
			$progress_items = self::get_course_progress_items_from_session( $course_progress );
		}

		return $progress_items;
	}

	/**
	 * Get course progress items from database.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @return array
	 */
	protected static function get_course_progress_items_from_db( $course_progress ) {
		$progress_items = array();

		foreach ( $course_progress->get_items() as $progress_item ) {
			$progress_items[ $progress_item->get_item_id() ] = $progress_item;
		}

		$query = new \WP_Query(
			array(
				'post_type'      => CourseChildrenPostType::all(),
				'post_status'    => PostStatus::PUBLISH,
				'posts_per_page' => -1,
				'meta_key'       => '_course_id',
				'meta_value'     => $course_progress->get_course_id( 'edit' ),
			)
		);

		$sections = self::filter_course_sections( $query->posts );

		foreach ( $sections as $id => $section ) {
			$sections[ $id ]['contents'] = self::filter_course_lessons_quizzes( $query->posts, $section['item_id'] );
		}

		return $sections;
	}

	/**
	 * Get course progress items from session.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @return array
	 */
	protected static function get_course_progress_items_from_session( $course_progress ) {
		$session = masteriyo( 'session' );

		$progress_items_from_db = self::get_course_progress_items_from_db( $course_progress );

		foreach ( $progress_items_from_db as $index => $progress_item_from_db ) {
			if ( 'section' !== $progress_item_from_db['item_type'] ) {
				continue;
			}

			$lesson_quizzes = $progress_item_from_db['contents'];

			// Convert the actual all course progress items to dictionary.
			$lesson_quizzes_map = array_reduce(
				$lesson_quizzes,
				function( $result, $lesson_quiz ) use ( $course_progress ) {
					$key            = $lesson_quiz['item_id'] . ':' . $lesson_quiz['item_type'] . ':' . $course_progress->get_course_id();
					$result[ $key ] = $lesson_quiz;

					return $result;
				},
				array()
			);

			// Get the course progress items of the specific course from session.
			$lesson_quizzes_from_session = array_filter(
				$session->get( 'course_progress_items', array() ),
				function( $lesson_quiz ) use ( $course_progress ) {
					return $course_progress->get_course_id() === $lesson_quiz['course_id'];
				}
			);

			// Merge course progress items from session to the actual course progress items list.
			foreach ( $lesson_quizzes_from_session as $key => $lesson_quiz ) {
				if ( isset( $lesson_quizzes_map[ $key ] ) ) {
					$lesson_quizzes_map[ $key ]['completed'] = $lesson_quiz['completed'];
				}
			}

			$progress_item_from_db['contents'] = array_values( $lesson_quizzes_map );
			$progress_items_from_db[ $index ]  = $progress_item_from_db;
		}

		return $progress_items_from_db;
	}

	/**
	 * Filter course sections.
	 *
	 * @since 1.6.9
	 *
	 * @param \WP_Post[] $posts
	 *
	 * @return array(
	 *              'item_id' => (integer)
	 *              'item_title' => (string)
	 *              'item_type' => (string)
	 *          )
	 */
	protected static function filter_course_sections( $posts ) {
		$sections = array_filter(
			$posts,
			function( $post ) {
				return 'mto-section' === $post->post_type;
			}
		);

		// Sort sections by menu order in ascending order.
		usort(
			$sections,
			function( $a, $b ) {
				if ( $a->menu_order === $b->menu_order ) {
					return 0;
				}

				return $a->menu_order > $b->menu_order ? 1 : -1;
			}
		);

		$sections = array_map(
			function( $section ) {
				return array(
					'item_id'    => $section->ID,
					'item_title' => wp_specialchars_decode( $section->post_title ),
					'item_type'  => str_replace( 'mto-', '', $section->post_type ),
				);
			},
			$sections
		);

		return $sections;
	}

	/**
	 * Filter course lessons and quizzes.
	 *
	 * @since 1.6.9
	 *
	 * @param \WP_Post[] $posts
	 * @param int $section_id Section ID.
	 *
	 * @return array
	 */
	protected static function filter_course_lessons_quizzes( $posts, $section_id ) {
		$post_types = CourseProgressPostType::all();

		$lessons_quizzes = array_filter(
			$posts,
			function( $post ) use ( $section_id, $post_types ) {
				return in_array( $post->post_type, $post_types, true ) && $section_id === $post->post_parent;
			}
		);

		// Sort lessons and quizzes by menu order in ascending order.
		usort(
			$lessons_quizzes,
			function( $a, $b ) {
				if ( $a->menu_order === $b->menu_order ) {
					return 0;
				}

				return $a->menu_order > $b->menu_order ? 1 : -1;
			}
		);

		$lessons_quizzes = array_filter(
			array_map(
				function( $lesson_quiz ) {
					$progress_item = self::get_course_progress_item( $lesson_quiz );

					if ( ! $progress_item ) {
						$progress_item = masteriyo( 'course-progress-item' );
						$progress_item->set_item_id( $lesson_quiz->ID );
						$progress_item->set_item_type( str_replace( 'mto-', '', $lesson_quiz->post_type ) );
					}

					return self::get_course_progress_item_data( $progress_item );
				},
				$lessons_quizzes
			)
		);

		return $lessons_quizzes;
	}

	/**
	 * Get the course progress item.
	 *
	 * @since 1.6.9
	 *
	 * @param \WP_Post $lesson_quiz Either lesson or quiz post type.
	 *
	 * @return \Masteriyo\Models\CourseProgressItem
	 */
	protected static function get_course_progress_item( $lesson_quiz ) {
		$course_progress_item = null;

		if ( is_user_logged_in() ) {
			$query = new CourseProgressItemQuery(
				array(
					'user_id' => masteriyo_get_current_user_id(),
					'item_id' => $lesson_quiz->ID,
				)
			);

			$course_progress_item = current( $query->get_course_progress_items() );
		} else {
			$session = masteriyo( 'session' );

			$course_progress_items = $session->get( 'course_progress_items', array() );

			if ( isset( $course_progress_items[ $lesson_quiz->ID ] ) ) {
				$course_progress_item = masteriyo( 'course-progress-item' );
				$course_progress_item->set_item_id( $lesson_quiz->ID );
				$course_progress_item->set_item_type( str_replace( 'mto-', '', $lesson_quiz->post_type ) );
				$course_progress_item->set_completed( $course_progress_items[ $lesson_quiz->ID ]['completed'] );
			}
		}

		return $course_progress_item;
	}

	/**
	 * Get course progress summary.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @return array
	 */
	protected static function get_course_progress_summary( $course_progress ) {
		if ( is_user_logged_in() ) {
			$summary = $course_progress->get_summary( 'all' );
		} else {
			$summary = self::get_course_progress_summary_from_session( $course_progress );
		}

		return $summary;
	}

	/**
	 * Get the summary of course progress items by type.
	 *
	 * @since 1.6.9
	 *
	 * @param array $course_progress_items Array of course progress items.
	 * @param string $type Course progress item type. (lesson and quiz).
	 *
	 * @return array
	 */
	protected static function get_course_progress_item_summary( $course_progress_items, $type ) {
		// Get the specific type of course progress items only.
		$course_progress_items = array_filter(
			$course_progress_items,
			function( $course_progress_item ) use ( $type ) {
				return $type === $course_progress_item['item_type'];
			}
		);

		// Get the completed course progress items.
		$completed_course_progress_items = array_filter(
			$course_progress_items,
			function( $course_progress_item ) {
				return $course_progress_item['completed'];
			}
		);

		return array(
			'completed' => count( $completed_course_progress_items ),
			'pending'   => count( $course_progress_items ) - count( $completed_course_progress_items ),
			'total'     => count( $course_progress_items ),
		);
	}

	/**
	 * Get course progress summary from session for guest user.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @return array
	 */
	protected static function get_course_progress_summary_from_session( $course_progress ) {
		$course_progress_items = self::get_course_progress_items_from_session( $course_progress );

		// Get the lessons and quizzes from the course progress items.
		$lesson_quizzes_items = array_reduce(
			$course_progress_items,
			function( $result, $course_progress_item ) {
				if ( ! empty( $course_progress_item['contents'] ) ) {
					$result[] = $course_progress_item['contents'];

				}

				return $result;
			},
			array()
		);

		$lesson_quizzes_items = masteriyo_array_flatten( $lesson_quizzes_items, 1 );

		$summary['lesson'] = self::get_course_progress_item_summary( $lesson_quizzes_items, 'lesson' );
		$summary['quiz']   = self::get_course_progress_item_summary( $lesson_quizzes_items, 'quiz' );

		$summary['total'] = array(
			'completed' => $summary['lesson']['completed'] + $summary['quiz']['completed'],
			'pending'   => $summary['lesson']['pending'] + $summary['quiz']['pending'],
		);

		return $summary;
	}
}
