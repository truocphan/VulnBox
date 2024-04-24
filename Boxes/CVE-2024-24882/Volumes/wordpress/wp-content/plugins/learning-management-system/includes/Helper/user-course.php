<?php
/**
 * User course functions.
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package Masteriyo\Helper
 */

use Masteriyo\Enums\PostStatus;
use Masteriyo\Enums\UserCourseStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Query\UserCourseQuery;
use Masteriyo\Roles;

/**
 * Get user course.
 *
 * @since 1.0.0
 *
 * @param int $user_course_id User course ID.
 * @return Masteriyo\Models\UserCourse|null
 */
function masteriyo_get_user_course( $user_course_id ) {
	try {
		$user_course = masteriyo( 'user-course' );
		$user_course->set_id( $user_course_id );

		$user_course_repo = masteriyo( 'user-course.store' );
		$user_course_repo->read( $user_course );

		return $user_course;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get list of status for user course.
 *
 * @since 1.0.0
 * @deprecated 1.5.3
 *
 * @return array
 */
function masteriyo_get_user_course_statuses() {
	$statuses = array(
		'active' => array(
			'label' => _x( 'Active', 'User Course status', 'masteriyo' ),
		),
	);

	/**
	 * Filters statuses for user course.
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses The statuses for user course.
	 */
	return apply_filters( 'masteriyo_user_course_statuses', $statuses );
}

/**
 * Count enrolled users by course or multiple courses.
 *
 * @since 1.0.0
 * @since 1.6.7 Argument $course supports array.
 *
 * @param int|int[] $course Course Id or Course IDS
 *
 * @return integer
 */
function masteriyo_count_enrolled_users( $course ) {
	global $wpdb;

	$count = 0;

	if ( is_array( $course ) ) {
		$course = array_filter( array_map( 'absint', $course ) );
	}

	if ( $wpdb && $course ) {
		$sql = $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_items WHERE ( status = %s OR status = %s )",
			UserCourseStatus::ACTIVE,
			UserCourseStatus::ENROLLED
		);

		$exclude_users = array_map(
			'absint',
			(array) get_users(
				array(
					'role__in' => array( Roles::ADMIN, Roles::INSTRUCTOR, Roles::MANAGER ),
					'fields'   => 'ID',
				)
			)
		);

		if ( ! empty( $exclude_users ) ) {
			$placeholders = array_fill( 0, count( $exclude_users ), '%d' );
			$sql         .= $wpdb->prepare( ' AND user_id NOT IN (' . implode( ',', $placeholders ) . ')', $exclude_users ); //phpcs:ignore
		}

		if ( is_array( $course ) ) {
			$placeholders = array_fill( 0, count( $course ), '%d' );
			$sql         .= $wpdb->prepare( ' AND item_id IN (' . implode( ',', $placeholders ) . ')', $course ); //phpcs:ignore
		} else {
			$sql .= $wpdb->prepare( ' AND item_id = %d', $course );
		}

		$count = $wpdb->get_var( $sql ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Filters enrolled users count for a course.
	 *
	 * @since 1.0.0
	 * @since 1.5.17 Removed third $query parameter.
	 *
	 * @param integer $count The enrolled users count for the given course.
	 * @param int|int[] $course Course ID or Course object.
	 */
	return apply_filters( 'masteriyo_count_enrolled_users', absint( $count ), $course );
}

/**
 * Get the number of active courses.
 *
 * @since 1.0.0
 *
 * @param Masteriyo\Models\User|int $user User.
 *
 * @return int
 */
function masteriyo_get_active_courses_count( $user ) {
	global $wpdb;

	$user_id = is_a( $user, 'Masteriyo\Models\User' ) ? $user->get_id() : $user;

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_activities
			WHERE user_id = %d AND activity_type = 'course_progress'
			AND ( activity_status = 'started' OR activity_status = 'progress' )  AND parent_id = 0",
			$user_id
		)
	);

	return $count;
}

/**
 * Get the number of user courses.
 *
 * @since 1.0.0
 * @since 1.6.7 Argument $course supports array.
 * @param int|int[] $course Course id or array of course ids.
 *
 * @return int
 */
function masteriyo_get_user_courses_count_by_course( $course ) {
	global $wpdb;

	$count = 0;

	if ( is_array( $course ) ) {
		$course = array_filter( array_map( 'absint', $course ) );
	}

	if ( $wpdb && $course ) {
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_items WHERE item_type = 'user_course'";

		if ( is_array( $course ) ) {
			$placeholders = array_fill( 0, count( $course ), '%d' );
			$sql         .= $wpdb->prepare( 'AND item_id IN (' . implode( ',', $placeholders ) . ')', $course ); // phpcs:ignore
		} else {
			$sql .= $wpdb->prepare( 'AND item_id = %d', $course );
		}

		$count = $wpdb->get_var( $sql ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Filters user courses count by course.
	 *
	 * @since 1.6.7
	 *
	 * @param integer $count The enrolled users count for the given course.
	 * @param int|int[] $course Course ID or Course object.
	 */
	return apply_filters( 'masteriyo_get_user_courses_count_by_course', absint( $count ), $course );
}

/**
 * Get user/enrolled course by user ID and course ID.
 *
 * @since 1.5.4
 *
 * @param int $user_id User ID.
 * @param int $course_id Course ID.
 * @return Masteriyo\Models\UserCourse
 */
function masteriyo_get_user_course_by_user_and_course( $user_id, $course_id ) {
	$query = new UserCourseQuery(
		array(
			'course_id' => $course_id,
			'user_id'   => $user_id,
		)
	);

	return current( $query->get_user_courses() );
}

if ( ! function_exists( 'masteriyo_count_all_enrolled_users' ) ) {
	/**
	 * Count total enrolled users from all courses.
	 *
	 * @since 1.6.16
	 *
	 * @param int|WP_User|Masteriyo\Database\Model $user User ID, WP_User object, or Masteriyo\Database\Model object.
	 *
	 * @return integer
	 */
	function masteriyo_count_all_enrolled_users( $user ) {
		$total_count = 0;

		$user = masteriyo_get_user( $user );

		if ( is_null( $user ) || is_wp_error( $user ) ) {
			return $total_count;
		}

		// Get all courses.
		$all_courses = get_posts(
			array(
				'post_type'      => PostType::COURSE,
				'post_status'    => PostStatus::PUBLISH,
				'author'         => $user->get_id(),
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		// Iterate through each course and count enrolled users.
		foreach ( $all_courses as $course_id ) {
				$total_count += masteriyo_count_enrolled_users( $course_id );
		}

		return $total_count;
	}
}

if ( ! function_exists( 'masteriyo_count_user_courses' ) ) {
	/**
	 * Get the count of courses created by a user.
	 *
	 * @since 1.6.16
	 *
	 * @param int|WP_User|Masteriyo\Database\Model $user User ID, WP_User object, or Masteriyo\Database\Model object.
	 *
	 * @return int The count of courses created by the user.
	 */
	function masteriyo_count_user_courses( $user ) {
		$user = masteriyo_get_user( $user );

		if ( is_null( $user ) || is_wp_error( $user ) ) {
			return 0;
		}

		$query = new WP_Query(
			array(
				'post_type'      => PostType::COURSE,
				'post_status'    => PostStatus::PUBLISH,
				'author'         => $user->get_id(),
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		return $query->found_posts;
	}
}
