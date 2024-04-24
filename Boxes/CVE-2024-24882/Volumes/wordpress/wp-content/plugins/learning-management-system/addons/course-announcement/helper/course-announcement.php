<?php

/**
 * Helper function of course announcement.
 *
 * @since 1.6.16
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'masteriyo_get_course_announcement' ) ) {

	/**
	 * Get course announcement.
	 *
	 * @since 1.6.16
	 *
	 * @param int|\Masteriyo\Addons\CourseAnnouncement\Models|\WP_Post $course_announcement Course announcement id or Course announcement Model or Post.
	 *
	 * @return \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement|null
	 */
	function masteriyo_get_course_announcement( $course_announcement ) {
		$course_announcement_obj   = masteriyo( 'course-announcement' );
		$course_announcement_store = masteriyo( 'course-announcement.store' );

		if ( is_a( $course_announcement, '\Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement' ) ) {
			$id = $course_announcement->get_id();
		} elseif ( is_a( $course_announcement, 'WP_Post' ) ) {
			$id = $course_announcement->ID;
		} else {
			$id = $course_announcement;
		}

		try {
			$id = absint( $id );
			$course_announcement_obj->set_id( $id );
			$course_announcement_store->read( $course_announcement_obj );
		} catch ( \Exception $e ) {
			return null;
		}

		/**
		 * Filters course announcement object.
		 *
		 * @since 1.6.16
		 *
		 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement_obj course announcement object.
		 * @param int|\Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement|WP_Post $course_announcement course announcement id or course announcement Model or Post.
		 */
		return apply_filters( 'masteriyo_get_course_announcement', $course_announcement_obj, $course_announcement );

	}
}
