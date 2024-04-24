<?php

use Masteriyo\ModelException;
use Masteriyo\Query\UserCourseQuery;
use Masteriyo\Query\CourseProgressQuery;
/**
 * Course progress functions.
 *
 * @since 1.0.0
 * @package Masteriyo\Helper
 */


/**
 * Get course progress.
 *
 * @since 1.0.0
 *
 * @param Masteriyo\Models\CourseProgress|int $course_progress_id Course progress ID.
 *
 * @return Masteriyo\Models\CourseProgress|\WP_Error
 */
function masteriyo_get_course_progress( $course_progress ) {
	if ( is_a( $course_progress, 'Masteriyo\Database\Model' ) ) {
		$id = $course_progress->get_id();
	} else {
		$id = absint( $course_progress );
	}

	try {
		$course_progress_obj = masteriyo( 'course-progress' );
		$course_progress_obj->set_id( $id );
		$course_progress_obj_repo = masteriyo( 'course-progress.store' );
		$course_progress_obj_repo->read( $course_progress_obj );
	} catch ( \Exception $e ) {
		$course_progress_obj = null;
	}

	/**
	 * Filters course progress object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseProgress $course_progress_obj course progress object.
	 * @param int|Masteriyo\Models\CourseProgress|WP_Post $course_progress course progress id or course progress Model or Post.
	 */
	return apply_filters( 'masteriyo_get_course_progress', $course_progress_obj, $course_progress );
}

/**
 * Get course progress item.
 *
 * @since 1.0.0
 *
 * @param int|Masteriyo\Models\CourseProgressItem $course_progress_item Course progress ID.
 *
 * @return Masteriyo\Models\CourseProgressItem|WP_Error
 */
function masteriyo_get_course_progress_item( $course_progress_item ) {
	if ( is_a( $course_progress_item, 'Masteriyo\Database\Model' ) ) {
		$item_id = $course_progress_item->get_id();
	} else {
		$item_id = (int) $course_progress_item;
	}

	try {
		$item = masteriyo( 'course-progress-item' );
		$item->set_id( $item_id );

		$item_repo = masteriyo( 'course-progress-item.store' );
		$item_repo->read( $item );

		return $item;
	} catch ( ModelException $e ) {
		$item = new \WP_Error( $e->getCode(), $e->getMessage(), $e->getErrorData() );
	}

	/**
	 * Filters course progress item object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseProgressItem $course_progress_item_obj course progress item object.
	 * @param int|Masteriyo\Models\CourseProgressItem|WP_Post $course_progress_item course progress item id or course progress item Model or Post.
	 */
	return apply_filters( 'masteriyo_get_course_progress_item', $item, $course_progress_item );
}

/**
 * Get course progress.
 *
 * @since 1.0.0
 *
 * @param Masteriyo\Models\Course|WP_Post|int $course Course object.
 * @param Masteriyo\Models\User|WP_Post|int $user User object.
 *
 * @return Masteriyo\Models\CourseProgress|WP_Error
 */
function masteriyo_get_course_progress_by_user_and_course( $user, $course ) {
	if ( is_a( $course, 'Masteriyo\Database\Model' ) ) {
		$id = $course->get_id();
	} elseif ( is_a( $course, '\WP_Post' ) ) {
		$id = $course->ID;
	} else {
		$id = absint( $course );
	}

	if ( is_a( $user, 'Masteriyo\Database\Model' ) ) {
		$id = $user->get_id();
	} elseif ( is_a( $user, '\WP_User' ) ) {
		$id = $user->ID;
	} else {
		$id = absint( $user );
	}

	$query = new CourseProgressQuery(
		array(
			'course_id' => $course,
			'user_id'   => $user,
			'per_page'  => 1,
		)
	);

	$course_progress = current( $query->get_course_progress() );

	/**
	 * Filters course progress object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseProgress|WP_Error $course_progress Course progress object.
	 * @param Masteriyo\Models\CourseProgress|WP_Error $course_progress Course progress object.
	 */
	return apply_filters( 'masteriyo_get_course_progress', $course_progress, $course_progress );
}

/**
 * Get active courses.
 *
 * @since 1.0.0
 *
 * @param Masteriyo\Models\User|WP_Post|int $user User object.
 * @return Masteriyo\Model\Course[]
 */
function masteriyo_get_active_courses( $user ) {
	if ( is_a( $user, 'Masteriyo\Database\User' ) ) {
		$id = $user->get_id();
	} elseif ( is_a( $user, '\WP_User' ) ) {
		$id = $user->ID;
	} else {
		$id = absint( $user );
	}

	$query = new CourseProgressQuery(
		array(
			'user_id' => get_current_user_id(),
			'status'  => array( 'started', 'progress' ),
		)
	);

	$progresses = $query->get_course_progress();

	$active_courses = array_filter(
		array_map(
			function( $progress ) {
				$course = masteriyo_get_course( $progress->get_course_id() );

				if ( is_null( $course ) ) {
					return null;
				}

				$course->progress = $progress;
				return $course;
			},
			$progresses
		)
	);

	return $active_courses;
}
