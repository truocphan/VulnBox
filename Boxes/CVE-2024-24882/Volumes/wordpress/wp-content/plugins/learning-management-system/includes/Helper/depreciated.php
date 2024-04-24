<?php
/**
 * Depreciated functions.
 *
 * @since 1.5.12
 */

if ( ! function_exists( 'masteriyo_get_course_access_modes' ) ) {
	/**
	 * Get masteriyo access modes.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.12
	 * @return string
	 */
	function masteriyo_get_course_access_modes() {
		masteriyo_deprecated_function( 'masteriyo_get_course_access_modes', '1.5.12', 'CourseAccessMode:all()' );

		/**
		 * Filters course access modes.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $access_modes Course access modes.
		 */
		return apply_filters(
			'masteriyo_course_access_modes',
			array(
				'open',
				'need_registration',
				'one_time',
				'recurring',
				'close',
			)
		);
	}
}
